<?php
class Curl {

	public function recent($USER_NAME, $API_KEY, $FROM) {

		$GET_RECENT = 'http://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&user=' . 
		urlencode($USER_NAME) . "&api_key=$API_KEY&limit=200&&from=$FROM&extended=0&format=json";
		
		$ch = curl_init($GET_RECENT);	
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		$data = curl_exec($ch);
		return json_decode($data);
	}

	public function duration($USER_NAME, $API_KEY, $ARTIST, $TRACK, $MBID) {

		if(!$MBID) {
			$GET_TRACK = "http://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=$API_KEY&artist=" .
			 urlencode($ARTIST) . '&track=' . urlencode($TRACK) . '&username=' .
			 urlencode($USER_NAME) . '&autocorrect=1&format=json';		
		} else {
			$GET_TRACK = "http://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=$API_KEY&mbid=$MBID&username=" . urlencode($USER_NAME) . '&autocorrect=1&format=json';
		}
	
		$ch = curl_init($GET_TRACK);	
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		$data = curl_exec($ch);
		$result = json_decode($data);
		return $result->track->duration;
	}
	
	public function track_time($DURATION) {
	
		$t = $DURATION/1000;
		$s = $t % 60;
		$t = ($t - $seconds) / 60;
		$m = $t % 60;
		$h = floor(($t - $minutes) / 60);
		
		return (($h) ? $h . ':' : ''). substr('0'.$m, -2) . ':' . substr('0'.$s, -2);
		
	}
} 