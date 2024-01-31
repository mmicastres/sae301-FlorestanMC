<?php

/**
* définition de la classe Categorie
*/

class Categorie {

	private int $_idcategorie;
	private int $_idprojet;
	private string $_nomcategorie;
	
	public function __construct(array $donnees) {

		if (isset($donnees['Id_Categorie']))   { $this->_idcategorie  =   $donnees['Id_Categorie']; }
		if (isset($donnees['Id_Projet'])) { $this->_idprojet =   $donnees['Id_Projet']; }
		if (isset($donnees['Nom_Categorie']))  { $this->_nomcategorie =   $donnees['Nom_Categorie']; }
	}

	// GETTERS

	public function idCategorie()    { return $this->_idcategorie;}
	public function idProjet()       { return $this->_idprojet;}
	public function nomCategorie()   { return $this->_nomcategorie;}

	// SETTERS

	public function setIdCategorie(int $idcategorie)       { $this->_idcategorie  = $idcategorie; }
	public function setIdProjet(int $idprojet)             { $this->_idprojet =  $idprojet;}
	public function setNomCategorie(string $nomcategorie)  { $this->_nomcategorie = $nomcategorie; }
}