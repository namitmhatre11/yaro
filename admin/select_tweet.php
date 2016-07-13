<?php
	require_once('../config.php');
	require_once('class.paging.php');
	$paginate = new paginate($conn);
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
			$('.banner_show').change(function() {
				var tweet_id = $(this).val();
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