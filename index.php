<?php require_once('conf/config.php') ?>
<?
/*** EVERYBODY FUNCTIONS ***/

// Curl helper function
function curl_get($url)
{
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	$return = curl_exec($curl);
	curl_close($curl);
	return $return;
}

/*** VIMEO ***/
if (isset($accounts['vimeo']['username']) && $accounts['vimeo']['username'] != '')
{
	$video_bubble = true;
	$vimeo_on = true;
	$api_endpoint = 'http://www.vimeo.com/api/v2/'.$accounts['vimeo']['username'];
	$vimeo_user = simplexml_load_string(curl_get($api_endpoint.'/info.xml'));
	$vimeo_videos = simplexml_load_string(curl_get($api_endpoint.'/videos.xml'));
}

/*** YOUTUBE ***/
if (isset($accounts['youtube']['username']) && $accounts['youtube']['username'] != '')
{
	$video_bubble = true;
	$youtube_on = true;
	$youtube_rss_feed = 'http://gdata.youtube.com/feeds/api/users/'.$accounts['youtube']['username'].'/uploads?v=2';
	$youtube_simple_xml = simplexml_load_file($youtube_rss_feed);
}

/*** TWITTER ***/
if (isset($accounts['twitter']['username']) && $accounts['twitter']['username'] != '')
{
	$twitter_on = true;
	$twitter_xml_feed = 'http://api.twitter.com/1/statuses/user_timeline.xml?screen_name='.$accounts['twitter']['username'];
	$twitter_simple_xml = simplexml_load_file($twitter_xml_feed);
	$twitter_status_feed = $twitter_simple_xml->status;
}

/*** FACEBOOK ***/

$i = 0;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><? if (isset($general['first_name']) && $general['first_name'] != '') {echo strtolower($general['first_name']);} ?> <? if (isset($general['last_name']) && $general['last_name'] != '') {echo strtolower($general['last_name']);} ?></title>
	<meta http-equiv="Content-category" content="text/html; charset=utf-8" />
	<link href="css/splash.css" rel="stylesheet" category="text/css" />
    <link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico" /> 
	<link rel="SHORTCUT ICON" href="favicon.ico" />
	<script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>
	
	<? if (isset($accounts['vimeo']['username']) || isset($accounts['youtube']['username']))
	{
	?>
	<script type="text/javascript" charset="utf-8">
		var elementsArray = new Array();
		var nav_items;
		var i = 0;
		$(document).ready(function(){
			$("a[rel^='prettyPhoto']").prettyPhoto();
			elementsArray = $('.content_bubble');
			nav_items = $('#elements ol li').length;
			// You can now specify a page in the uri to go to it first - pceasies.me/?photos
			page = location.href.split('?')[1];
			if(page && page.length > 3) {
				switchto(page, 0)
			};
			document.getElementById("pictures").style.display = 'none';
			$("a[rel^='prettyPhoto']").prettyPhoto({theme: 'light_rounded',slideshow:5000, autoplay_slideshow:true});
			$('#nav li').each(function() {
				$(this).attr('title', i);
				i++;
			});
			$('#nav li a').click(function() {
				switchto( $(this).text() );	
				return false;
			});
		});
		$(window).load(function() {
			$('#loading-pics').fadeOut('fast', function() {
				$('#pictures').fadeIn(2000);
			}).html('');
		});
		function switchto( elem ){
			$(elementsArray).hide(1);
			$('#'+elem).fadeIn('slow');
			amount = (42 + (parseInt($('#nav_'+elem).attr('title'))*114));
			$('#triangle').stop().animate({marginRight: amount}, 1000);
		}
	</script>
	<script src="js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="css/prettyPhoto.css" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
	<? } ?>
	<script type="text/javascript">
		//document.getElementById('nav').style = 'display: none;';
	</script>
	<style>
		body
		{
			
			<? if(isset($visual_style['background_image']) && $visual_style['background_image'] != '') {echo 'background-image: url('.$visual_style['background_image'].');';} ?>
			
		}
		div#nav, div#nav a
		{
			
			<? if(isset($visual_style['navigation_color']) && $visual_style['navigation_color'] != '') {
				echo('color: '.$visual_style['navigation_color'].';');
				} ?>
		
			<? if(isset($visual_style['navigation_shadows']) && $visual_style['navigation_shadows'] != '') {
				echo 'text-shadow:0px 0px 6px #666';
				} ?>
			
		}
	</style>
</head>
<body>
	<div id="nav">
		<h1>
		<? if (isset($general['first_name']) && $general['first_name'] != '') {echo strtolower($general['first_name']);} ?> <? if (isset($general['last_name']) && $general['last_name'] != '') {echo strtolower($general['last_name']);} ?>
		</h1>
		<div id="elements">
			<ol>
				<!-- I added a simple PHP number increment which is used to determine the correct offset for the triangle arrow
					The arrow is always lined up (few px off) no matter which modules are active  -->
				<li id="nav_about"><a href="?p=about">about</a></li>
				<? if ($images) { ?><li id="nav_photos"><a href="?p=photos">photos</a></li><? } ?>
				<? if ($videos) { ?><li id="nav_videos"><a href="?p=videos">videos</a></li><? } ?>
				<? if ($twitter) { ?><li id="nav_twitter"><a href="?p=twitter">twitter</a></li><? } ?>
			</ol>
			<div id="triangle">
				<img src="images/bubble_triangle_100.png" width="30" height="15" />
			</div>
		</div>	
	</div>
	
	
	
	<div id="about" class="content_bubble">
		<h3>about</h3>
		<p><?=$general['about_me']; ?></p>
	</div>
	
	<? if ($images) { ?> <!-- Images true/false check added outside of div some the 'photos' line and an empty div won't show up when disabled -->
	<div id="photos" class="content_bubble">
		<h3><? if (isset($general['first_name']) && $general['first_name'] != '') {echo strtolower($general['first_name'])."'s ";} ?>photos</h3>
		<div id="loading-pics"><img src="images/loading.gif"></div>
		<div id="pictures">
		<?php
		if($images) {
			if($accounts['flickr']['username'] !== '') {
				require_once('helpers/flickr.php');
				$accounts['flickr']['username'] = getID($accounts['flickr']['username']); // This turns the username into an id if it isn't one
				$flickr_images = getPhotos($accounts['flickr']['username'], 25);
				foreach($flickr_images as $item) {
					echo '<a href="'.$item['url'].'" rel="prettyPhoto[flickr]"><img src="'.$item['url'].'" class="image-thumb" name="'.$item['title'].'" alt="<a href=\''.$item['link'].'\'>'.$item['title'].'</a>"></a>';
				}
			}
			if($accounts['flickr']['username'] !== '' && $accounts['picasa']['username'] !== '') {
				echo '<hr>';
			}
			if($accounts['picasa']['username'] !== '') {
				require_once('helpers/picasa.php');
				$picasa_images = getPicasaPhotos($accounts['picasa']['username'], 25);
				foreach($picasa_images as $item) {
					echo '<a href="'.$item['url'].'" rel="prettyPhoto[picasa]"><img src="'.$item['url'].'" class="image-thumb" name="'.$item['title'].'" alt="<a href=\''.$item['link'].'\'>'.$item['title'].'</a>"></a>';
				}
			}
		}
		?>
		</div>	
		<p id="more">
		<?php
			if($accounts['flickr']['username'] !== '') echo '<a href="http://flickr.com/photos/'.$accounts["flickr"]["username"].'">Flickr...</a><br>';
			if($accounts['picasa']['username'] !== '') echo '<a href="http://picasaweb.google.com/'.$accounts["picasa"]["username"].'">Picasa...</a>';
		?>
		</p>
		
	</div>
	<? } ?>
	
	<div id="videos" class="content_bubble">
		<? if ($videos) { ?>
		<h3><? if (isset($general['first_name']) && $general['first_name'] != '') {echo strtolower($general['first_name'])."'s ";} ?>videos</h3>
		<?
		if (isset($general['about_videos']) && $general['about_videos'] != '')
		{
			echo '<p>'.$general['about_videos'].'</p>';
		}
		?>
		<p>
			<? if ($accounts['vimeo']['username'] !== '') { ?>
			<!-- Vimeo -->
			<div id="vimeo_videos">
				<?php foreach ($vimeo_videos->video as $video): ?>
	            <a href="<?=$video->url ?>&width=640" rel="prettyPhoto" title="<?=$video->title ?>"><img src="<?=$video->thumbnail_small ?>" width="120" height="90" /></a>
				<?php endforeach; ?>
			</div>
			<? } ?>
			
			<? if ($accounts['youtube']['username'] !== '') { ?>
			<!-- YouTube -->
			<div id="youtube_videos">
				<?
				// iterate over entries in feed
				foreach ($youtube_simple_xml->entry as $entry)
				{
					// Namespace info...
					$media = $entry->children('http://search.yahoo.com/mrss/');

					// Get the video URL...
					$attrs = $media->group->player->attributes();
					$video_url = $attrs['url'];
					$video_title = $media->group->title; 

					// Get the video thumbnail...
					$attrs = $media->group->thumbnail[0]->attributes();
					$thumbnail = $attrs['url'];
					
					echo '<a href="'.$video_url.'&width=640" rel="prettyPhoto" title="'.$video_title.'"><img src="'.$thumbnail.'" width="120" height="90" /></a>';
				}
				?>
			</div>
			<? } ?>
		</p>
		<? } ?>
	</div>
	
	<div id="twitter" class="content_bubble">
		<h3><? if (isset($general['first_name']) && $general['first_name'] != '') {echo strtolower($general['first_name'])."'s ";} ?>tweets</h3>

		<p>
			<div id ="twitter_feed">
				<? if ($twitter) { ?>
				<?
				foreach ($twitter_simple_xml->status as $tweet)
				{
					echo '<p class="tweet"><img src="'.$tweet->user->profile_image_url.'" style="float: left; margin: 0 8px 8px 0;" height="60" width="60" />'.$tweet->text.'<br /><span style="font-size: 10px; font-style: italic;">'.$tweet->created_at.'</span></p><hr />';
				}
				?>
				<p id="more">
					<a href="http://twitter.com/<?=$accounts['twitter']['username'] ?>">More...</a>
				</p>
				<? } ?>
			</div>
		</p>
	</div>
	
	<div id="footer">
		Lifehacker.me by <a href="http://lifehacker.com">Lifehacker</a>.
	</div>
	
	<div id="spacer" style="padding-bottom: 12px; float: right; clear: both;">&nbsp;</div>
	
</body>
</html>