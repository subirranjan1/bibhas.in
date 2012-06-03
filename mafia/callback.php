<?php
require_once 'twasync/include.php';
if(isset($_GET['oauth_token']) && $_GET['oauth_token'] != ''){
    $twitter->setToken($_GET['oauth_token']);
    $token = $twitter->getAccessToken();
    $twitter->setToken($token->oauth_token, $token->oauth_token_secret);
    setcookie('oauth_token', $token->oauth_token);
    setcookie('oauth_token_secret', $token->oauth_token_secret);
    setcookie('logged_in', 1);
    header('location: dashboard.php');
}else{
    header('location: index.php');
}