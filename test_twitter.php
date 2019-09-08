<?php


require_once ( 'twitteroauth-1.0.1/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;

/*
$settings = array (
    'oauth_access_token' => "redacted",
    'oauth_access_token_secret' => "redacted",
    'consumer_key' => "redacted",
    'consumer_secret' => "redacted"
);
*/

$connection = new TwitterOAuth("redacted", "redacted", "redacted", "redacted");

/*$content = $connection->get("account/verify_credentials");*/

$content = $connection->get("search/tweets", ["q" => "#downloadinnovation"]);

$array = json_decode(json_encode($content), True);

for($i=0;$i<sizeof($array['statuses']);$i++){
    echo $array['statuses'][$i]['text'] . "<br>";
    echo $array['statuses'][$i]['user']['name'] . "<br><br>"; 
}   
