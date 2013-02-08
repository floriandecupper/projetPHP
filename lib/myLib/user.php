<?php

class User {

	private
		$nom,
		$prenom,
		$pseudo,
		$points,
		$mail;
	/**
		Getters
		@return string
	**/
	function nom() {
		return $this->nom;
	}
	function prenom() {
		return $this->prenom;
	}
	function pseudo() {
		return $this->pseudo;
	}
	function points() {
		return $this->points;
	}
	function mail() {
		return $this->mail;
	}

	function __construct($nom, $prenom, $points, $mail) 
	{
		$this->nom=$nom;
		$this->prenom=$prenom;
		$this->pseudo=$prenom.' '.substr($nom, 0, 1).'.';
		$this->points=$points;
		$this->mail=$mail;
		return $this;
	}
}