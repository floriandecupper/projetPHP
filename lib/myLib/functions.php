<?php
	function transformToPseudo($prenom,$nom) {
		return $prenom.' '.substr($nom, 0, 1).'.';
	}
	function transformToDate($date) {
		$retour= date('d/m/Y',$date).' à '.date('H',$date).'h'.date('i',$date); 
		return $retour;
	}
?>