<?php
/* 
	Helper to grab Picasa images
	By:
	- Nick - pceasies.com
	- admin@newbieworks.org
	
	License Restrictions:
	- You cannot sell this
	- You cannot claim as your own
	- You must leave this message intact
	License Allows:
	- Modifications
	- Distributions
	- Commercial use
*/
if(!$config) require_once('conf/config.php'); // Require config.php if it's not already loaded

function getPicasaPhotos( $username, $max = 20 ) { // Username, maximum pictures
	$xml = simplexml_load_file('http://picasaweb.google.com/data/feed/base/user/'.$username.'/?alt=rss&kind=photo&imgmax=640u&max-results='.$max); // Load XML
	$array = array(); // Empty array for pictures
	foreach( $xml->channel->item as $photo ) { // We create an array containing each url, photo title, link (in that order)
		$url = $photo->enclosure['url']; // Set url
		$link = $photo->link; // Set link
		$item = array( "url" => $url, "title" => $photo->title, "link" => $link ); // Make item
		array_push( $array, $item ); // Add item to array
	}
	return $array;
}
?>