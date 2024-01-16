<?php

/**
* définition de la classe itineraire
*/

class Contributeur {

	private int $_idutilisateur;
	private int $_idprojet;

	
	public function __construct(array $donnees) {

		if (isset($donnees['Id_Utilisateur']))   { $this->_idutilisateur =    $donnees['Id_Utilisateur']; }
		if (isset($donnees['Id_Projet']))        { $this->_idprojet =       $donnees['Id_Projet']; }
	}

	// GETTERS

    public function idUtilisateur() { return $this->_idutilisateur;}
	public function idProjet()      { return $this->_idprojet;}


	// SETTERS

    public function setIdUtilisateur(int $idutilisateur) { $this->_idutilisateur = $idutilisateur;}
	public function setIdProjet(int $idprojet)           { $this->_idprojet = $idprojet; }

}