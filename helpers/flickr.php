<?php
/* 
	Handmade Flickr helper. 
	
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
$flickrBase = 'http://api.flickr.com/services/rest/?method=';
require('conf/config.php');
function getID ( $username ) { // Converts username to ID if not already done
	global $flickrBase, $accounts;
	// Return username if already an ID
	if(preg_match('/^[0-9]{8}\@N[0-9]{2}$/', $username)){
		return $username;
	}
	else // Else get ID from username
	{
		$user = simplexml_load_file($flickrBase.'flickr.people.findByUsername&api_key='.$accounts['flickr']['apikey'].'&username='.$username);
		return $user->user['id'];
	}
}
/* Function from Flickr to encode photo IDs in base58 to use with flic.kr/p shorturl */
function base58_encode( $num ) {
	$alphabet = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
	$base_count = strlen($alphabet);
	$encoded = '';
	while ($num >= $base_count) {
		$div = $num/$base_count;
		$mod = ($num-($base_count*intval($div)));
		$encoded = $alphabet[$mod] . $encoded;
		$num = intval($div);
	}
	if ($num) $encoded = $alphabet[$num] . $encoded;
	return $encoded;
}

/* This function uses the RSS feed. Lots of information is gathered, but it's limited to 20 results */
function getPhotoFeed ( $id ) {
	global $flickrBase, $accounts;
	$photos = simplexml_load_file('http://api.flickr.com/services/feeds/photos_public.gne?lang=en-us&format=xml&id='.$id);
	return $photos;
}

/* This only grabs photo id and constructs uri. Can get all public photos */
function getPhotos ( $id, $per_page, $safe = NULL ) {
	global $flickrBase, $accounts;
	$xml = simplexml_load_file($flickrBase.'flickr.people.getPublicPhotos&api_key='.$accounts['flickr']['apikey'].'&user_id='.$id.'&per_page='.$per_page.'&safe_search='.$safe);
	$array = array(); // array to hold pictures
	foreach ($xml->photos->photo as $photo) { // We create an array containing each url, photo title, link (in that order)
		//http://farm{farm-id}.static.flickr.com/{server-id}/{id}_{secret}.jpg <- format to get the image from flickr
		$url = 'http://farm'.$photo['farm'].'.static.flickr.com/'.$photo['server'].'/'.$photo['id'].'_'.$photo['secret'].'.jpg'; // url of photo
		$link = 'http://flic.kr/p/'.base58_encode($photo['id']); // clickable link that goes to the photo
		$item = array( "url" => $url, "title" => $photo['title'], "link" => $link ); // each photo item
		array_push($array, $item); // put each photo item into array
	}
	return $array;
}
?>