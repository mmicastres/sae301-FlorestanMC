<?php

/**
* définition de la classe itineraire
*/

class Projet {

	private int $_idprojet;
	private string $_titre;
	private string $_description;
	private string $_image = ""; // Tips monsieur Barreau
	private string $_demo;
	private string $_sources;
	private int $_idressource;
	private string $_libelle;
	private string $_nom;
	
	public function __construct(array $donnees) {

		if (isset($donnees['Id_Projet']))   { $this->_idprojet =    $donnees['Id_Projet']; }
		if (isset($donnees['Titre']))       { $this->_titre =       $donnees['Titre']; }
		if (isset($donnees['Description'])) { $this->_description = $donnees['Description']; }
		if (isset($donnees['Image']))       { $this->_image =       $donnees['Image']; }
		if (isset($donnees['Demo']))        { $this->_demo =        $donnees['Demo']; }
		if (isset($donnees['Sources']))     { $this->_sources =     $donnees['Sources']; }
		if (isset($donnees['Id_Contexte'])) { $this->_idressource =  $donnees['Id_Contexte']; }
		if (isset($donnees['Identifiant'])) { $this->_libelle =  $donnees['Identifiant']; }
		if (isset($donnees['Intitule'])) { $this->_nom =  $donnees['Intitule']; }

	}

	// GETTERS

	public function idProjet()    { return $this->_idprojet;}
	public function titre()       { return $this->_titre;}
	public function description() { return $this->_description;}
	public function image()       { return $this->_image;}
	public function demo ()       { return $this->_demo;}
	public function sources ()    { return $this->_sources;}
	public function idRessource()  { return $this->_idressource;}
	public function libelle()  { return $this->_libelle;}
	public function nom()  { return $this->_nom;}


	// SETTERS

	public function setIdProjet(int $idprojet)          { $this->_idprojet = $idprojet; }
	public function setTitre(string $titre)             { $this->_titre = $titre; }
	public function setDescription(string $description) { $this->_description = $description;}
	public function setImage(string $image)             { $this->_image = $image;}
	public function setDemo(string $demo)               { $this->_demo = $demo;}
	public function setSources(string $sources)         { $this->_sources = $sources;}
	public function setIdRessource(int $idressource)    { $this->_idressource = $idressource;}
	public function setLibelle(string $libelle)    { $this->_libelle = $libelle;}
	public function setNom(string $nom)    { $this->_nom = $nom;}
}
