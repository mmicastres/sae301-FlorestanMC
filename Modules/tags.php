<?php

/**
* définition de la classe Tag
*/

class Tag {

	private int $_idtag;
	private string $_nomtag;

	
	public function __construct(array $donnees) {

		if (isset($donnees['Id_Tag']))   { $this->_idtag =   $donnees['Id_Tag']; }
		if (isset($donnees['Nom_Tag']))  { $this->_nomtag =  $donnees['Nom_Tag']; }
	}

	// GETTERS

    public function idTag()  { return $this->_idtag;}
	public function nomTag() { return $this->_nomtag;}


	// SETTERS

    public function setIdTag(int $idtag)   { $this->_idtag =  $idtag;}
	public function setNomTag(string $nomtag) { $this->_nomtag = $nomtag; }

}