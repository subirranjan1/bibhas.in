<?php
if(!isset($_COOKIE['oauth_token']) || !isset($_COOKIE['oauth_token_secret']) || !isset($_COOKIE['logged_in']) || $_COOKIE['logged_in'] != 1){
    header('location: index.php');
}else{
    require_once 'twasync/EpiSecret.php';
    $consumer = new EpiSecret();
    $twitter = new EpiTwitter($consumer->key, $consumer->secret, $_COOKIE['oauth_token'], $_COOKIE['oauth_token_secret']);
}
?>
<?php
include 'template/head.tpl.php';
?>

<body id="boddy">
    <div id="wrapper">
        <div id="navigation">
            <?php
            include 'template/nav.tpl.php';
            ?>
            <div class="clearfix"></div>
        </div>
        <?php
        include 'template/unfollow/body.tpl.php';
        ?>
        <div id="ratelimit">
            <?php
            $status = $twitter->get('/account/rate_limit_status.json');
            $reset_time = date('h:i:s a', strtotime($status->reset_time));
            ?>
            API request left:&nbsp;<?=$status->remaining_hits ?>, Will reset at <?=$reset_time ?>.
            <div id="submit_button" onclick="$('#form_tbuf').submit()">
                Unfollow
            </div>
        </div>
    </div>
</body>

</html>