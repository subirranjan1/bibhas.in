<?php
session_start();
session_destroy();
session_start();
setcookie('oauth_token', "", time()-3600);
setcookie('oauth_token_secret', "", time()-3600);
setcookie('logged_in', "", time()-3600);
header('location: index.php');