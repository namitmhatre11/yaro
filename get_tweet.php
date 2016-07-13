<?php
require_once('config.php');

$twitterAAPI = "";

$responseVar = array();

if($_POST && isset($_POST['mode'])){

	if($_POST['mode'] == "saveTweet"){

	$logfile = true;	
	$resfileName = "log_".date('d_m_Y_H_i_s').".txt";
	$myfile = fopen("log/".$resfileName, "w") or die("Unable to open file!");	
		
	require_once('tweetConfig.php');

	$loc = "http://localhost/yaro/";
	
	$tweetText = $_POST['tweetText']." ".$loc;	

	writeLog("tweetText: ".$tweetText);

	$tweetTiD = getTweetByText($tweetText);

	$responseText = "";
	set_time_limit(300);

	if($tweetTiD) {
		writeLog("tweetId done");
		$variable = array();

		$variable['question'] = $tweetText;
		$variable['tweet_id'] = $tweetTiD;

		$query = $conn->prepare("INSERT INTO `yaro_tweet_question` (`".implode("`,`", array_keys($variable))."`) VALUES ('".implode("','", $variable)."')");

		$query->execute();
		//fetchtweets();
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

		$next = $_REQUEST['next'];
		try{
			$stmt = $conn->prepare("SELECT user_screen_name, profile_photo, question, ans, reply_img FROM yaro_tweets_data LIMIT ".($next+1).",1"); 
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_NUM);
			
			if(!$result) {

			$next = 0;
			$stmt = $conn->prepare("SELECT user_screen_name, profile_photo, question, ans, reply_img FROM yaro_tweets_data LIMIT ".($next+1).",1"); 
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

		$stmt = $GLOBALS['conn']->prepare("SELECT reply_id,ans FROM yaro_tweets_data where tweet_id = '".$tweetID."'  ORDER BY `timestamp` DESC LIMIT 1"); 
		$stmt->execute();
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
function fetchtweets() {


	try {

			$stmt = $GLOBALS['conn']->prepare("SELECT reply_id FROM yaro_tweets_data ORDER BY reply_id DESC LIMIT 1"); 
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_NUM);

			$since = isset($result) ? '&since_id='.$result[0] : '';

			$que_url = 'https://api.twitter.com/1.1/statuses/show.json';


			$response = $GLOBALS['twitterAPI']->setGetfield('?q=#SABKaYARO YARO Says&count=100'.$since)
			->buildOauth('https://api.twitter.com/1.1/search/tweets.json', 'GET')
			->performRequest();

			$result = json_decode($response);

			$data=array();

			foreach ($result as $key => $value) {

			if(is_array($value)){
			foreach ($value as $key1 => $value1) {
				
				$reply_img="";
				if(property_exists($value1->entities, 'media')){
					$reply_img = $value1->entities->media[0]->media_url;
				}

				
				$que_getfield = '?id='.$value1->in_reply_to_status_id;
				if($que_getfield){
					$que_response = $GLOBALS['twitterAPI']->setGetfield($que_getfield)
											->buildOauth($que_url, 'GET')
											->performRequest();

					$que_response = json_decode($que_response);

					if(!property_exists($que_response, 'errors')){
						$data[]=array('user_id'=>$que_response->user->id,
						'user_name'=>$que_response->user->name,
						'user_screen_name'=>$que_response->user->screen_name,
						'profile_photo'=>$que_response->user->profile_image_url,
						'question'=>$que_response->text,
						'ans'=>$value1->text,
						'reply_img'=>$reply_img,
						'tweet_id'=>$value1->in_reply_to_status_id,
						'tweet_time'=>$que_response->created_at,
						'reply_id'=>$value1->id,
						'reply_time'=>$value1->created_at);
					}
				}
			}
			}
			}

			if(!empty($data)){

				$values = "";
				foreach ($data as $key => $value) {
				$newValue = "('".implode("','", $value)."')";
				$values .= !empty($values) ? ",".$newValue : $newValue;
				}

				$query = $GLOBALS['conn']->prepare("INSERT INTO `yaro_tweets_data` (`".implode("`,`", array_keys($data[0]))."`) VALUES $values");

				$query->execute();
			}

	}
	catch(Exception $e) {}

}
function writeLog($logText) {

	if($GLOBALS['logfile'] == true) {
		fwrite($GLOBALS['myfile'],"\n".$logText." ".date('d_m_Y_H_i_s'));
	}
}
?>