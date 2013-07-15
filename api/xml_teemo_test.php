<?php
	//Setting up URL
	$base  = "http://lol.michaelku.com/api/champion/";
	$name = "?name=teemo&skills=true";
	$format = "&format=xml";
		
	//Full URL Path
	$url = $base.$name.$format;
	
	//CURL Copy/Paste
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	$curlResponse = curl_exec($ch);
	curl_close($ch);
	$xmlObject = simplexml_load_string($curlResponse);
	
	print "URL: <br />$url";
	print "<hr /> XML: <br />";
	print_r($xmlObject);	
	print "<hr /> Print name, title, and attributes: <br />";
		
	$name = $xmlObject->name;
	$title = $xmlObject->title;
	$attr = $xmlObject->attributes;
	print "$name, $title <br /> $attr";
?>