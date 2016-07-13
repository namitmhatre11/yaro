<?php
if(isset($_POST) && isset($_POST['next'])) {

	require_once('../config.php');

		$next = $_POST['next'];
		try{
			$stmt = $conn->prepare("SELECT concat('@',user_screen_name) user_screen_name, profile_photo, question, ans, reply_img FROM yaro_tweets_data order by id desc LIMIT :incrementValue,1"); 
			$stmt->bindValue(':incrementValue', intval($next+1), PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if(!$result) {

			$next = 0;
			$stmt = $conn->prepare("SELECT concat('@',user_screen_name) user_screen_name, profile_photo, question, ans, reply_img FROM yaro_tweets_data order by id desc LIMIT :incrementValue,1"); 
			$stmt->bindValue(':incrementValue', intval($next+1), PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			}
			$result['nextValue'] = $next+1;
			echo json_encode($result);
			exit();

		}
		catch(PDOException $e){}

}
?>