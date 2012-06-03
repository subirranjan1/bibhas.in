<?php
$head_msg = '';
$me = $twitter->get_accountVerify_credentials();
$friends = $twitter->get('/friends/ids.json', array('user_id' => $me->id_str, 'stringify_ids' => true));
if(!isset($_GET['filter']) || $_GET['filter'] == ''){
    /*
     Default case, returns unfiltered 100 users in the friend list
    */
    $head_msg = 'Here are randomly picked 100 people you can unfollow';
    $friends_ids = $friends->ids;
    shuffle($friends_ids);
    $frnd_details = array();
    $temp_frnd_details = get_all_frnds_details($friends_ids);
    $count = 0;
    foreach($temp_frnd_details as $temp_frnd){
        if($count>=100)
            break;
        array_push($frnd_details, $temp_frnd);
        $count++;
    }
}elseif($_GET['filter']=='nfb'){
    $head_msg = 'These "friends of yours" are not following you back!';
    $followers = $twitter->get('/followers/ids.json', array('user_id' => $me->id_str, 'stringify_ids' => true));
    $frnd_details = array();
    $not_following = array_diff($friends->ids, $followers->ids);
    $temp_frnd_details = get_all_frnds_details($friends->ids);
    foreach($temp_frnd_details as $temp_frnd){
        if(in_array($temp_frnd->id_str, $not_following))
            array_push($frnd_details, $temp_frnd);
    }
}elseif($_GET['filter']=='np'){
    $head_msg = 'These friends of yours have less than 100 followers!';
    $friends_ids = $friends->ids;
    $frnd_details = array();
    $temp_frnd_details = get_all_frnds_details($friends_ids);
    foreach($temp_frnd_details as $temp_frnd){
        if($temp_frnd->followers_count < 100)
            array_push($frnd_details, $temp_frnd);
    }
}elseif($_GET['filter']=='fa'){
    $head_msg = 'These friends of yours does Not maintain a good following/follower ratio!';
    $friends_ids = $friends->ids;
    $frnd_details = array();
    $temp_frnd_details = get_all_frnds_details($friends_ids);
    foreach($temp_frnd_details as $temp_frnd){
        if(($temp_frnd->friends_count/$temp_frnd->followers_count) > 2)
            array_push($frnd_details, $temp_frnd);
    }
}else{
    $filter = trim($_GET['filter']);
    $head_msg = "Searched '{$filter}' in Name, Description and Location.";
    $pattern = "/{$filter}/i";
    $friends_ids = $friends->ids;
    $frnd_details = array();
    $temp_frnd_details = get_all_frnds_details($friends_ids);
    foreach($temp_frnd_details as $temp_frnd){
        if(preg_match($pattern, $temp_frnd->name)>0 || preg_match($pattern, $temp_frnd->description)>0 || preg_match($pattern, $temp_frnd->location)>0)
            array_push($frnd_details, $temp_frnd);
    }
}

/*
 * function [array] get_all_frnds_details(friends_ids)
 * takes array of friends ids and returns an array full of
 * details about their profile.
 *
 * @param $friends_ids (array) - Array of ids of twitter users
 * 
*/
function get_all_frnds_details($friends_ids){
    global $twitter;
    $temp_frnd_details = array();
    if(isset($_SESSION['frnd_details'])){
        $temp_frnd_details = $_SESSION['frnd_details'];
    }
    //shuffle($friends_ids);
    if(count($temp_frnd_details)==0){
        $loop_count = ceil(count($friends_ids)/100);
        for($j=0; $j<$loop_count; $j++){
            $ids = "";
            $count = 0;
            foreach($friends_ids as $key => $frnd){
                if($count == 100)
                    break;
                $ids .= $frnd . ',';
                unset($friends_ids[$key]);
                $count++;
            }
            $temp = $twitter->get('/users/lookup.json', array('user_id' => $ids, 'include_entities' => false));
            foreach($temp as $u){
                array_push($temp_frnd_details, $u);
            }
            $_SESSION['frnd_details'] = $temp_frnd_details;
        }
    }
    return $temp_frnd_details;
}
?>
<div id="content">
    <div id="filters">
        <ul id="filter_list">
            <li>Filters:</li>
            <li class="<?php echo (!isset($_GET['filter'])) ? "opted" : " " ?>"><a href="unfollow.php">Un-filtered</a></li>
            <li class="<?php echo (isset($_GET['filter']) && $_GET['filter'] == 'nfb') ? "opted" : " " ?>"><a href="unfollow.php?filter=nfb">Not-Following-Back</a></li>
            <li class="<?php echo (isset($_GET['filter']) && $_GET['filter'] == 'np') ? "opted" : " " ?>"><a href="unfollow.php?filter=np">Not-Popular</a></li>
            <li class="<?php echo (isset($_GET['filter']) && $_GET['filter'] == 'fa') ? "opted" : " " ?>"><a href="unfollow.php?filter=fa">Fanatic</a></li>
            <li><form method="get" action="unfollow.php">Search-By-Keyword:<input type="text" name="filter" style="width: 100px"><input type="submit" value="Go"></form></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div id="head_msg">
        <?php echo $head_msg; ?>
    </div>
    <div id="following_list">
        <!--
        <div class="follower">
            <img src="" alt="" width="50px" height="50px" />
            <input type="checkbox" name="tbuf" class="chkbx_tbuf" />
            <div class="tbuf_name">@iAmBibhas</div>
            <div class="tbuf_loc">Kolkata, India</div>
            <div class="tbuf_name">-> 123 | <- 324</div>
        </div>
        -->
        <form method="post" action="act.php" id="form_tbuf">
            <input type="hidden" name="action" value="unfollow" />
            <?php
            foreach($frnd_details as $this_frnd){
                ?>
            <div class="follower">
                <img src="<?=$this_frnd->profile_image_url ?>" alt="" width="50px" height="50px" />
                <input onchange="$(this).parent().toggleClass('checked')" type="checkbox" name="tbuf[]" class="chkbx_tbuf" value="<?=$this_frnd->id_str ?>"/>
                <div class="tbuf_name"><a target="_blank" title="<?=$this_frnd->name ?>" href="http://twitter.com/<?=$this_frnd->screen_name ?>"><?=$this_frnd->screen_name ?></a></div>
                <div class="tbuf_loc"><?=$this_frnd->location ?></div>
                <div class="tbuf_count">-> <?=$this_frnd->friends_count ?> | <- <?=$this_frnd->followers_count ?></div>
            </div>
                <?php
            }
            ?>  
        </form>
    </div>
</div>