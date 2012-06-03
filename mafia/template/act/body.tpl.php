<?php
$me = $twitter->get_accountVerify_credentials();
$uf_count = 0;
$uf_frnds_list = array();
$time = time();
$log_id = $db->insert_data('log', array('user_hash' => $me->id_str, 'type' => 'unfollow', 'count' => 0, 'datetime' => $time));
foreach($tbuf_ids as $user_id){
    $temp_user = $twitter->post('/friendships/destroy.json', array('user_id' => $user_id));
    //$temp_user = $twitter->get('/users/lookup.json', array('user_id' => $user_id . ',', 'include_entities' => false));
    array_push($uf_frnds_list, $temp_user);
    $temp_id = $db->insert_data('unfollow_record', array('log_id' => $log_id, 'target_id' => $user_id));
    $uf_count++;
}
$db->update_data('log', array('count' => $uf_count), "`id` = {$log_id}");
?>

<div id="content">
    <div id="head_msg">
        Successfully unfollowed these people.
    </div>
    <div id="following_list">
        <?php
        //print_r($uf_frnds_list);
        foreach($uf_frnds_list as $this_frnd){
            ?>
        <div class="follower">
            <img src="<?=$this_frnd->profile_image_url ?>" alt="" width="50px" height="50px" />
            <div class="tbuf_name"><a target="_blank" title="<?=$this_frnd->name ?>" href="http://twitter.com/<?=$this_frnd->screen_name ?>"><?=$this_frnd->screen_name ?></a></div>
            <div class="tbuf_loc"><?=$this_frnd->location ?></div>
            <div class="tbuf_count">-> <?=$this_frnd->friends_count ?> | <- <?=$this_frnd->followers_count ?></div>
        </div>
            <?php
        }
        ?>
    </div>
</div>