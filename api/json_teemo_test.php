<?php
	//Setting up URL
	$base  = "http://lol.michaelku.com/api/champion/";
	$name = "?name=teemo&skills=true";
		
	//Full URL Path
	$url = $base.$name;
	
	//JSON Decode
	$json = file_get_contents($url);
	
	print "URL: <br />$url";
	print "<hr /> JSON: <br />";
	print_r($json);
	
	$json = json_decode($json);
	
	print "<hr /> Print name, title, and attributes: <br />";
		
	$name = $json->champion->name;
	$title = $json->champion->title;
	$attr = $json->champion->attributes;
	print "$name, $title <br /> $attr";
?>