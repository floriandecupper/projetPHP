<?php
	function transformToPseudo($prenom,$nom) {
		return $prenom.' '.substr($nom, 0, 1).'.';
	}
	function transformToDate($date) {
		$retour= date('d/m/Y',$date).' à '.date('H',$date).'h'.date('i',$date); 
		return $retour;
	}
	function transformToCountDown($date) {
		$time=time()-$date;
		if($time>86400) {
			$result=round($time/86400);
			return "Il y a $result jours";
			//jour
		}elseif($time>3600) {
			//heure
			$result=round($time/3600);
			return "Il y a $result heures";
		}elseif($time>60) {
			//min
			$result=round($time/60);
			return "Il y a $result minutes";
		}
		return "Il y a $time secondes";
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
	function transformToExtension($str) {
		$extension = explode('.', $str);
        $extension = end($extension);
        return '.'.$extension;
    }
?>