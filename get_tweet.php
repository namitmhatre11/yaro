<?php
require_once('config.php');

$twitterAAPI = "";

if($_POST && isset($_POST['mode'])){

	if($_POST['mode'] == "saveTweet"){

	require_once('TwitterAPIExchange.php');

	$settings = array(
	'oauth_access_token' => "570680690-2pq2KkNEFg51SolM6Z5CJZLQCJhmOpL5DRwMRL8c",
	'oauth_access_token_secret' => "trOVKR2fREUsalyz91iLCm1HhlPi46amLuFmQFNLfICzR",
	'consumer_key' => "mvMVHujmoFat1Kyky2wsPLXhm",
	'consumer_secret' => "Zn1i0s6JRxueog0qFmORUXp82jEdWWiHcUV9S5TydNNrwJqTYl"
	);
	$twitterAAPI = new TwitterAPIExchange($settings);
	$tweetText = $_POST['tweetText'];	

		$variable = array();

		$variable['question'] = $tweetText;

		$query = $conn->prepare("INSERT INTO `yaro_tweet_question` (`".implode("`,`", array_keys($variable))."`) VALUES ('".implode("','", $variable)."')");

		$query->execute();
		//echo "New records created successfully";

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
}


?>