<?php
require_once('../config.php');

if($_POST && isset($_POST['id'])){
	echo $_POST['id'].'--'.$_POST['checked'];
	try{
		echo $checked = $_POST['checked'] == "true" ? 1 : 0;
		echo "UPDATE yaro_tweets_data SET show_on_banner=".$checked." WHERE id=".$_POST['id'];
		$query = $conn->prepare("UPDATE yaro_tweets_data SET show_on_banner= :checked WHERE id=:id");
		$query->execute(array(':checked'=> $checked, ':id'=> $_POST['id']));
		return false;
	}catch(PDOException $e){
		echo $query . "<br>" . $e->getMessage();
	}
		
}
?>