<?php
require_once 'twasync/EpiSecret.php';
$consumer = new EpiSecret();
if(!isset($_COOKIE['oauth_token']) || !isset($_COOKIE['oauth_token_secret']) || !isset($_COOKIE['logged_in']) || $_COOKIE['logged_in'] != 1)
{
    $twitter = new EpiTwitter($consumer->key, $consumer->secret);
}elseif(isset($_COOKIE['oauth_token']) && isset($_COOKIE['oauth_token_secret']) && isset($_COOKIE['logged_in']) && $_COOKIE['logged_in'] == 1){
    $twitter = new EpiTwitter($consumer->key, $consumer->secret, $_COOKIE['oauth_token'], $_COOKIE['oauth_token_secret']);
    header('location: dashboard.php');
}else{
    header('location: index.php');
}