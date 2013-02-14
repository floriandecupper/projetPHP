<?php

class User {

	private
		$id,
		$nom,
		$prenom,
		$pseudo,
		$points,
		$mail,
		$description,
		$date,
		$ip,
		$tags, 
		$id_parrain,
		$timestamp;
	/**
		Getters
		@return string
	**/
	function id() {
		return $this->id;
	}
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
	function id_parrain() {
		return $this->id_parrain;
	}
	function ip() {
		return $this->ip;
	}
	function tags() {
		return $this->tags;
	}
	function timestamp() {
		return $this->timestamp;
	}
	function date() {
		return $this->date;
	}
	function description() {
		return $this->description;
	}
	function fillByID() {
		//Connexion BDD
		//Recherche de l'utilisateur par l'id
		//Remplissage des données
	}
	function __construct($id, $nom='', $prenom='', $points='', $mail='', $description='', $tags='', $timestamp=0, $ip='', $id_parrain='') 
	{
		$this->id=$id;
		$this->nom=$nom;
		$this->prenom=$prenom;
		$this->pseudo=$prenom.' '.substr($nom, 0, 1).'.';
		$this->points=$points;
		$this->mail=$mail;
		$this->description=$description;
		$this->tags=$tags;

		$this->timestamp=$timestamp;
		$this->date=date('d/m/Y', $timestamp).' à '.date('H:i:s', $timestamp);
		$this->ip=$ip;
		$this->id_parrain=$id_parrain;
		return $this;
	}
}