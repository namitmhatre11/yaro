<?php
require_once('config.php');
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

}
catch(PDOException $e){
	echo "Error: " . $e->getMessage();
}


?>