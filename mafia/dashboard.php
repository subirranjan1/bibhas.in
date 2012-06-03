<?php
if(!isset($_COOKIE['oauth_token']) || !isset($_COOKIE['oauth_token_secret']) || !isset($_COOKIE['logged_in']) || $_COOKIE['logged_in'] != 1){
    header('location: index.php');
}else{
    include_once 'db/DbObj.php';
    require_once 'twasync/EpiSecret.php';
    $db = new DbObj();
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
        include 'template/dashboard/body.tpl.php';
        ?>
    </div>
</body>

</html>