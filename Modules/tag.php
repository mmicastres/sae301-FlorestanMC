<?php

/**
* définition de la classe Tag
*/

class Tag {

	private int $_idtag;
	private int $_idprojet;
	private string $_nomtag;

	
	public function __construct(array $donnees) {

		if (isset($donnees['Id_Tags']))   { $this->_idtag =      $donnees['Id_Tags']; }
		if (isset($donnees['Id_Projet'])) { $this->_idprojet =   $donnees['Id_Projet']; }
		if (isset($donnees['Nom_Tag']))   { $this->_nomtag =     $donnees['Nom_Tag']; }
	}

	// GETTERS

    public function idTag()  { return $this->_idtag;}
	public function idProjet()  { return $this->_idprojet;}
	public function nomTag() { return $this->_nomtag;}


	// SETTERS

    public function setIdTag(int $idtag)   { $this->_idtag =  $idtag;}
	public function setIdProjet(int $idprojet)   { $this->_idprojet =  $idprojet;}
	public function setNomTag(string $nomtag) { $this->_nomtag = $nomtag; }

}