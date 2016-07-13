<?php require_once('onload.php'); ?>
<html lang="en" data-slide="1">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Yaro</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body> 
       <div class="medium-banner yaro-banner">
       <img src="img/medium-bg.jpg" class="full-width">
          <div class="qna-wrpr clearfix">
            <div class="yaro-quest-wrpr">
              <div class="user-img tweetProfile">
                <img src="<?php echo $arrayFirst['profile_photo']; ?>">
              </div>
              <div class="user-name tweetUserName">
                <?php echo $arrayFirst['user_screen_name']; ?>
              </div>
              <img class="tw-img" src="img/tw-icon-m.png">
              <p class="question  tweetQuestion"><?php echo $arrayFirst['question']; ?></p>
            </div>
            <div class="yaro-ans-wrpr">
              <div class="user-img-r">
                <img src="img/yaro-img.jpg">
              </div>
              <div class="user-name-r">
                Y.A.R.O
              </div>
              <img class="tw-img-r" src="img/tw-icon-m.png">
              <p class="question tweetAnswer"><?php echo $arrayFirst['ans']; ?></p>              
            </div>
          </div>  
       </div>
       <script src="js/jquery.min.js"></script>
<script src="js/custom.js"></script>
  </body>
</html>