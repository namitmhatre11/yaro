<?php
	require_once('../config.php');
	require_once('class.paging.php');
	$paginate = new paginate($conn);

	//$stmt = $GLOBALS['conn']->prepare("SELECT user_screen_name, question, ans, reply_img FROM yaro_tweets_data ORDER BY reply_id DESC"); 
	//$stmt->execute();
	//$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	//echo '<pre>';
	//var_dump($result);

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<form method="POST" action="">
		<!-- <table>
			<tr>
				<th>Screen Name</th>
				<th>Question</th>
				<th>Answer</th>
				<th>Image</th>
				<th>Use on Banner</th>
			</tr>
			<?php
				foreach ($result as $key => $value) {
			?>
					<tr>
						<td><?php echo $value['user_screen_name']; ?></td>
						<td><?php echo $value['question']; ?></td>
						<td><?php echo $value['ans']; ?></td>
						<td><?php echo empty($value['reply_img']) ? 'No' : 'Yes'; ?></td>
						<td><input type="checkbox" name="is_banner">Select to show on banner.</td>
					</tr>
			<?php
				}
			?>
		</table> -->



		
		<link rel="stylesheet" href="../style.css" type="text/css" />
		<table align="center" width="75%"  border="1">
		<tr>
		<td>User question and answers. #SabakaYaro</td>
		</tr>
		<tr>
		<td>

		        <table align="center" border="1" width="100%" height="100%" id="data">
			        <tr>
						<th>Screen Name</th>
						<th>Question</th>
						<th>Answer</th>
						<th>Image</th>
						<th>Use on Banner</th>
					</tr>
			        <?php 
			        $query = "SELECT user_screen_name, question, ans, reply_img, show_on_banner FROM yaro_tweets_data ORDER BY reply_id DESC";       
			        $records_per_page=10;
			        $newquery = $paginate->paging($query,$records_per_page);
			        $paginate->dataview($newquery);
			        $paginate->paginglink($query,$records_per_page);  
			        ?>
		        </table>
		</td>
		</tr>
		</table>
		<div id="footer">
		
		</div>


	</form>
</body>
</html>