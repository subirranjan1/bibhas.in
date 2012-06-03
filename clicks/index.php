<?php
$base_url = "http://api.flickr.com/services/rest/?method=flickr.people.getPublicPhotos&api_key=6acf2546bac4dc1724aed646c5792f04&user_id=78813669@N00&format=json&nojsoncallback=1";
$response = json_decode(file_get_contents($base_url));
?>
<html>
<head>
	<title>Clicks</title>
	<script src="js/jquery-1.7.2.min.js"></script>
	<script src="js/lightbox.js"></script>
	<link href="css/lightbox.css" rel="stylesheet" />	
	<link href="css/screen.css" rel="stylesheet" />	
    <script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-3724476-13']);
    _gaq.push(['_trackPageview']);

    (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
    </script>
</head>
<body>
<div class="imageRow">
	<div class="set">
		<?php
		foreach ($response->photos->photo as $key => $value) {
			// http://farm{farm-id}.staticflickr.com/{server-id}/{id}_{secret}_[mstzb].jpg
			$count = $key+1;
			$url = "http://farm{$value->farm}.staticflickr.com/{$value->server}/{$value->id}_{$value->secret}_q.jpg";
			$orig_url = "http://farm{$value->farm}.staticflickr.com/{$value->server}/{$value->id}_{$value->secret}.jpg";
			//print_r($value);
			?>
			<div class="single">
				<a href='<?=$orig_url ?>' title='<?=$value->title ?>' class='image' rel='lightbox[set]'>
					<img src='<?=$url ?>' alt='image <?=$count ?> 0f <?=$response->photos->total ?>' />
				</a>
			</div>
			<?php
		}
		?>
	</div>
</div>
</body>
</html>
