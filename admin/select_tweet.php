<?php
	require_once('../config.php');
	require_once('class.paging.php');
	$paginate = new paginate($conn);

	//$stmt = $GLOBALS['conn']->prepare("SELECT user_screen_name, question, ans, reply_img FROM yaro_tweets_data ORDER BY reply_id DESC"); 
	//$stmt->execute();
	//$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	//echo '<pre>';
	//var_dump($result);
	if (!empty($_POST)){
		//sd
		echo '<pre>';
		print_r($_POST);
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="../style.css" type="text/css" />
</head>
<body>
	<form method="POST" id="tweet_entries" action="">
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
							<th>Show on Banner</th>
						</tr>
				        <?php 
				        $query = "SELECT user_screen_name, question, ans, reply_img, show_on_banner, id FROM yaro_tweets_data ORDER BY reply_id DESC";       
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
	<script src="../js/jquery.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			//alert('hi');
			$('.banner_show').change(function() {
				//alert('hi');
				//alert($(this).is(":checked"));
				//alert($(this).val());
				var tweet_id = $(this).val();
				console.log(tweet_id);
				var is_checked = $(this).is(":checked");
				
				$.ajax({
                    url: "banner_tweet_update.php",
                    type: 'POST',
                    data: { id : tweet_id,checked:is_checked},
                    success: function(data, textStatus, xhr) {
                        console.log('Banner tweet list updated!');
                        alert('Banner tweet list updated!');
                          return false;
                    },
                  error:function(){
                    console.log("Something went wrong.");
                    
                    return false;
                  }
                });


			});
		});
	</script>
</body>
</html>