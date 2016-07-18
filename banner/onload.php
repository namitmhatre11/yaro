<?php
require_once('../config.php');
$arrayFirst = array();

		try{
			$stmt = $conn->prepare("SELECT concat('@',user_screen_name) user_screen_name, profile_photo, question, ans, show_on_banner FROM yaro_tweets_data where show_on_banner=1 order by id desc LIMIT 1"); 
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			
			$arrayFirst = $result;

		}
		catch(PDOException $e){}

?>