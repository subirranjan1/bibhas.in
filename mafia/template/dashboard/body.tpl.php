<?php
$me = $twitter->get_accountVerify_credentials();
$logs = $db->get_data('log', "user_hash = '{$me->id_str}' order by datetime DESC");
?>
<div id="content">
    <div id="activity">
        <ul id="activity_list">
            <?php
            if(count($logs) == 0):
            ?>
            <li>Did nothing yet. Go <a href="unfollow.php">Unfollow</a> some people.</li>
            <?php
            else:
                foreach($logs as $log):
                    $datetime = date('jS M', strtotime($log['datetime']));
                ?>
                <li><?=$datetime ?> - Unfollowed <?=$log['count'] ?> <?php echo ($log['count']>1) ? 'people' : 'person'; ?>.</li>
                <?php
                endforeach;
            endif;
            ?>
        </ul>
    </div>
</div>