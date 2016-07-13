<?php 

require_once('TwitterAPIExchange.php');

	$settings = array(
	'oauth_access_token' => "570680690-2pq2KkNEFg51SolM6Z5CJZLQCJhmOpL5DRwMRL8c",
	'oauth_access_token_secret' => "trOVKR2fREUsalyz91iLCm1HhlPi46amLuFmQFNLfICzR",
	'consumer_key' => "mvMVHujmoFat1Kyky2wsPLXhm",
	'consumer_secret' => "Zn1i0s6JRxueog0qFmORUXp82jEdWWiHcUV9S5TydNNrwJqTYl"
	);
	$twitterAPI = new TwitterAPIExchange($settings);
	

?>