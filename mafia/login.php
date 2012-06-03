<?php
require_once 'twasync/include.php';
$auth_url = $twitter->getAuthenticateUrl();
header("location: {$auth_url}");
?>