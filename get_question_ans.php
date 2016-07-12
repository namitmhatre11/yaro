<?php
require_once('config.php');

try{
	$stmt = $conn->prepare("SELECT reply_id FROM yaro_tweets_data ORDER BY reply_id DESC LIMIT 1"); 
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_NUM);
	print_r($result);
	$since = isset($result) ? '&since_id='.$result[0] : '';
	//$since = $result[0];
}
catch(PDOException $e){
	echo "Error: " . $e->getMessage();
}
//exit;



require_once('TwitterAPIExchange.php');

$settings = array(
    'oauth_access_token' => "570680690-2pq2KkNEFg51SolM6Z5CJZLQCJhmOpL5DRwMRL8c",
    'oauth_access_token_secret' => "trOVKR2fREUsalyz91iLCm1HhlPi46amLuFmQFNLfICzR",
    'consumer_key' => "mvMVHujmoFat1Kyky2wsPLXhm",
    'consumer_secret' => "Zn1i0s6JRxueog0qFmORUXp82jEdWWiHcUV9S5TydNNrwJqTYl"
);
$twitterAAPI = new TwitterAPIExchange($settings);


$url = 'https://api.twitter.com/1.1/search/tweets.json';
$getfield = '?q=#SABKaYARO YARO Says&count=100'.$since;
//echo $getfield;exit;
$requestMethod = 'GET';

$que_url = 'https://api.twitter.com/1.1/statuses/show.json';


$response = $twitterAAPI->setGetfield($getfield)
    ->buildOauth($url, $requestMethod)
    ->performRequest();

/*echo "<pre>";
var_dump(json_decode($response));exit();*/

$result = json_decode($response);

$data=array();


foreach ($result as $key => $value) {
	//echo "<br/>---------------------------------------<br/>";
	if(is_array($value)){
		foreach ($value as $key1 => $value1) {
			//echo "<br/>";
			//echo "<pre>";
			//print_r($value1);
			$reply_img="";
			if(property_exists($value1->entities, 'media')){
				$reply_img = $value1->entities->media[0]->media_url;
			}

			/*echo "<br/>";
			echo $value1->text."-----".$value1->id."-----".$value1->in_reply_to_status_id;*/
			
			$que_getfield = '?id='.$value1->in_reply_to_status_id;
			if($que_getfield){
				$que_response = $twitterAAPI->setGetfield($que_getfield)
										->buildOauth($que_url, $requestMethod)
	    								->performRequest();

	    		$que_response = json_decode($que_response);
	    		//echo "<pre>";
	    		//print_r($que_response);
	    		//echo '<br>'.$que_response->text;
	    		//echo '<br>'.$value1->text;

				//echo '<br> id:'.$que_response->user->id.' - name: '.$que_response->user->name.' - screen-name: '.$que_response->user->screen_name.' - profile-image: '.$que_response->user->profile_image_url.'<br>';

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
	/*else
	{
		echo $value1->text;
	}*/
}
echo "<pre>";
print_r($data);
	
	try {
		$values = "";
		foreach ($data as $key => $value) {
		$newValue = "('".implode("','", $value)."')";
		$values .= !empty($values) ? ",".$newValue : $newValue;
		}

		$query = $conn->prepare("INSERT INTO `yaro_tweets_data` (`".implode("`,`", array_keys($data[0]))."`) VALUES $values");

		$query->execute();
		echo "New records created successfully";
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}

?>