<?php 

require_once "functions.php";

$error = array();
$saved = false;
if(isset($_POST['ics']) && count($_POST['ics'])==2){
	$ics = $_POST['ics'];

	if(!preg_match("/[0-9]{4}\-[0-9]{4}/i", $ics['si'])){
		$error['si'] = 1;
		//echo 'error si';
	}else{
		if(isset($error['si']))
			unset($error['si']);
	}
	if(!preg_match("/[\w]+\@[^\s]+/i", $ics['email'])){
		$error['email'] = 1;
		//echo 'error email';
	}else{
		if(isset($error['email']))
			unset($error['email']);
	}
	if(count($error)==0){
		//print_r($ics);
		preg_match("/[0-9]{4}\-[0-9]{4}/i", $ics['si'], $sis);
		$si = $sis[0];
		
		preg_match("/[\w]+\@[^\s]+/i", $ics['email'], $emails);
		$email = $emails[0];
		
		if(isListed($email)){
			$error['msg'] = 'The email address is already listed';
		}else{
			addNotifRecord($si, $email);
		}
		if(!isset($error['msg']))
			$saved = true;
	}
}
?>
<!doctype html>
<html>

	<head>
		<meta charset="utf-8"/>
		<title>Get ICS availability notification for your Xperia Arc S, Neo V and Ray</title>
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link rel="stylesheet" media="all" href="less.css"/>
		<link href='http://fonts.googleapis.com/css?family=Lusitana' rel='stylesheet' type='text/css'>
		<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js?ver=3.3.1'></script>
		<script type='text/javascript' src="root.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<!-- Adding "maximum-scale=1" fixes the Mobile Safari auto-zoom bug: http://filamentgroup.com/examples/iosScaleBug/ -->
		<script type="text/javascript">

		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-3724476-12']);
		  _gaq.push(['_trackPageview']);

		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();

		</script>
	</head>
	
	<body lang="en">
		<div id="header" class="gigantic">
			<span id="logo-text">
				ICS-Notifier
			</span>
		</div>
		<div class="large">&nbsp;</div>
		<?php 
		if(!$saved):
		?>
		<form action="." method="post">
			<div class="large" id="input-place">
				<?php echo isset($error['msg']) ? $error['msg'] . '<br />' : ''; ?>
				<div>Enter SI number(<a href="http://talk.sonymobile.com/servlet/JiveServlet/downloadImage/2-178141-10539/450-252/icsphones.JPG" target="_blank">?</a>): <input type="text" name="ics[si]" value="e.g. 1254-8967" onfocus="this.value=''" placeholder="" class="large <?php echo isset($error['si']) ? "error" : "" ?>" /></div>
				<div>Enter Email address: <input type="text" name="ics[email]" value="" placeholder="" class="large <?php echo isset($error['email']) ? "error" : "" ?>" /></div>
				<div><input type="submit" name="submit" value="Submit" class="large" /></div>
			</div>
		</form>
		<?php 
		else:
		?>
			<div class="large">
				Done! I'll notify you once your device is listed. :)
			</div>
		<?php 
		endif;
		?>
		<div class="large">&nbsp;</div>
		<div class="bigger">
			Sony has just started rolling out ICS for it's 2011 Xperia line-up(Arc S, Neo V, Ray). They have put up <a href="http://talk.sonymobile.com/thread/35144" target="_blank">a list</a> of SI numbers that tells customers if the update is available for their country and device. It is too bothersome to keep checking their website to see if my device has shown up. So here is a notifier service that'll notify you as soon as your SI number gets listed on their website.
		</div>
		<div class="bigger">&nbsp;</div>
		<div class="big">
			<ul>
				<li>Send me feedback <a href="https://twitter.com/#!/iAmBibhas" class="contact">@iAmBibhas</a>. :)</li>
			</ul>
		</div>
	</body>
	
</html>