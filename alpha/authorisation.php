<?php
// Keys and tokens
$consumer_key = '7hgh3UQklwxZ29Txl6IKyAmyg';
$consumer_secret = 	'g6Lm1RV4uQGWScahPZrsBqffmFCBxrQGgogYiBtaPO0MzuSH1V';
$access_token = '313203162-GJiKRkPQO06jrnZNLdLawzN9TlQqzBLt4WbcXdOL';
$access_token_secret = 'eii0AK604NDFTTTBjxpuo8nP9gsJn25JBrSQ3ZqyurgBB';

// Include library
require "twitteroauth/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

// Connect to API
$connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);
$content = $connection->get("account/verify_credentials");

?>