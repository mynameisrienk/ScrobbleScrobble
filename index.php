<?php
require_once('_config.php');
function autoload_class($class) { include_once('_classes/' . $class . '.class.php'); }
spl_autoload_register('autoload_class');


$db  = new Database();
$db->connect();
$FROM = $db->updated();

$curl = new Curl();
$scrobbles = $curl->recent($USER_NAME, $API_KEY, $FROM);



include('_header.php');

if($scrobbles->recenttracks->track) {

	$when = 0;
	$i = 0;
	
	echo "<ul>\n";
	foreach($scrobbles->recenttracks->track as $track) {
		$duration = $curl->duration($USER_NAME, $API_KEY, $track->artist->{'#text'}, $track->name, $track->mbid);

		// Update the last Scrobble timestamp
		if($when < $track->date->uts) {
			$when = $track->date->uts;	
			$db->modified( $when );
		}

		// Create an array to use with mysqli
		$item = Array(
			'track'       => $track->name,
			'artist'      => $track->artist->{'#text'},
			'album'       => $track->album->{'#text'},
			'duration'    => $duration,
			'URL'         => $track->url,
			'UTS'         => $track->date->uts
		);
		
		if($duration && $track->date->uts) {
			
			// Add to MySQL DB
			$db->insert( $item );
			
			echo "\t<li>\n";
			echo "\t\t<a href=\"{$track->url}\"><strong>{$track->name}</strong>\n";
			echo "\t\t<span>" . $curl->track_time($duration) . "</span><br/>\n";
			echo "\t\t{$track->artist->{'#text'}}<br/>\n";
			echo "\t\t({$track->album->{'#text'}})\n";
			echo "\t\t</a>\n";
			echo "\t</li>";
			$i++;
		}
	}
	echo "</ul>\n";
}

if($i == 0) {
	echo 	"<p>No scrobbles to save</p>\n";
} else {
	echo 	"<p>Saved $i scrobbles</p>\n";	
}

include('_footer.php');

?>