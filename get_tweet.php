<?php
require_once('config.php');

$twitterAAPI = "";

$responseVar = array();

if($_POST && isset($_POST['mode'])){

	if($_POST['mode'] == "saveTweet"){

	$logfile = false;
	$resfileName = "log_".date('d_m_Y_H_i_s').".txt";
	$myfile = fopen("log/".$resfileName, "w") or die("Unable to open file!");	
		
	require_once('tweetConfig.php');

	$tweetText = $_POST['tweetText'];	

	writeLog("tweetText: ".$tweetText);

	$tweetTiD = getTweetByText($tweetText);

	$responseText = "";
	set_time_limit(300);

	if($tweetTiD) {
		writeLog("tweetId done");
		
		$query = $conn->prepare("INSERT INTO `yaro_tweet_question` (`question`,`tweet_id`) VALUES (:question,:tweet_id)");
		$query->execute(array(":question"=>$tweetText,":tweet_id"=>$tweetTiD));
		
		$responseData = fetchTweetResponse($tweetTiD);
		$responseVar['dataResponse'] = $responseData;
		$responseVar['status'] = true;

	}
	else {
		$responseVar['message'] = "Unable to find tweet.";
		$responseVar['status'] = false;
	}
	fclose($myfile);
	set_time_limit(30);
	echo json_encode($responseVar);
	exit();

	}
	else if($_POST['mode'] == "slideTweet") {

		$next = $_POST['next'];
		try{
			$stmt = $conn->prepare("SELECT user_screen_name, profile_photo, question, ans, reply_img FROM yaro_tweets_data order by id desc LIMIT :incrementValue,1"); 
			$stmt->bindValue(':incrementValue', intval($next), PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_NUM);
			
			if(!$result) {

			$next = 0;
			$stmt = $conn->prepare("SELECT user_screen_name, profile_photo, question, ans, reply_img FROM yaro_tweets_data order by id desc LIMIT :incrementValue,1"); 
			$stmt->bindValue(':incrementValue', intval($next), PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_NUM);

			}
			$result[] = $next+1;
			echo json_encode($result);
			exit();

		}
		catch(PDOException $e){
			echo json_encode(array($e->getMessage()));
		}

	}

}
else {
	echo json_encode(array());
	exit();
}



function getTweetByText($tweetText) {


	try {

		$getfield = '?q='.urlencode($tweetText);

		writeLog("tweetQuery: ".$getfield);

		$response = $GLOBALS['twitterAPI']->setGetfield($getfield)
        ->buildOauth('https://api.twitter.com/1.1/search/tweets.json', 'GET')
        ->performRequest();
        $response = json_decode($response);

        if(!empty($response) && count($response->statuses)) {

			$statusesVar = $response->statuses;
			writeLog("tweetId: ".$statusesVar[0]->id);
			return $statusesVar[0]->id;
        }
        else {
        	writeLog("tweetQuery No result");
        	return getTweetByText($tweetText);
        }

	}
	catch(Exception $e) {
		writeLog("tweetQuery Error");
		return getTweetByText($tweetText);
	}

}

function fetchTweetResponse($tweetID) {

		$stmt = $GLOBALS['conn']->prepare("SELECT reply_id,ans FROM yaro_tweets_data where tweet_id = :tweet_id ORDER BY `timestamp` DESC LIMIT 1"); 
		$stmt->execute(array(":tweet_id"=>$tweetID));
		$result = $stmt->fetch(PDO::FETCH_NUM);
		writeLog("fetchTweetResponse");
		if(!isset($result)){
			writeLog("fetchTweetResponse before sleep");
			sleep(5);
			writeLog("fetchTweetResponse after sleep");
			return fetchTweetResponse($tweetID);
		}
		else if($result == false) {
			writeLog("fetchTweetResponse before ok false");
			sleep(5);
			writeLog("fetchTweetResponse after ok false");
			return fetchTweetResponse($tweetID);
		}
		else {
			writeLog("fetchTweetResponse result ok");
			writeLog("fetchTweetResponse result ok".json_encode($result));
			return $result;
		}

}
function writeLog($logText) {

	if($GLOBALS['logfile'] == true) {
		fwrite($GLOBALS['myfile'],"\n".$logText." ".date('d_m_Y_H_i_s'));
	}
}
?>