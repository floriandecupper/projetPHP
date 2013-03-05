<?php
	function transformToPseudo($prenom,$nom) {
		return $prenom.' '.substr($nom, 0, 1).'.';
	}
	function transformToDate($date) {
		$retour= date('d/m/Y',$date).' à '.date('H',$date).'h'.date('i',$date); 
		return $retour;
	}
	function transformToText($text,$array) {
		foreach($array as $value) {
			$text=preg_replace('/VALUE/', $value, $text, 1);
		}
		return $text;
	}
	function transformToTags($tags) {
    	$tags = preg_replace('/\s+/', '', $tags);
  		return $tags;
	}
?>