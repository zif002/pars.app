<?php
define ("AUTH_KEY","AIzaSyCA0oQqK_dfWFUwKfz3yRDQwaaUeLqXn_s");
define ("API_URL","https://www.googleapis.com/urlshortener/v1/url");

function shortUrl($url = false,$shortUrl = false){
	$ku = curl_init();
	curl_setopt($ku,CURLOPT_SSL_VERIFYPEER,FALSE);
	curl_setopt($ku,CURLOPT_SSL_VERIFYHOST,FALSE);
	curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE);
	curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE);
	if($url) {
		curl_setopt($ku,CURLOPT_POST,TRUE);
		curl_setopt($ku,CURLOPT_POSTFIELDS,json_encode(array("longUrl"=>$url)));
		curl_setopt($ku,CURLOPT_HTTPHEADER,array("Content-Type:application/json"));
		curl_setopt($ku,CURLOPT_URL,API_URL."?key=".AUTH_KEY);
	}
	elseif($short_url) {
		echo $short_url;

	}
	$result = curl_exec($ku);
	curl_close($ku);
	return json_decode($result);
}
?>