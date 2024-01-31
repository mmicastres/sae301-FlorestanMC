<?php

/**
* définition de la classe Ressource
*/

class Ressource {

	private int $_idressource;
	private string $_libelle;
    private string $_semestre;
    private string $_nom;
    private int $_note;
	
	public function __construct(array $donnees) {

		if (isset($donnees['Id_Contexte']))   { $this->_idressource  =   $donnees['Id_Contexte']; }
		if (isset($donnees['Identifiant']))  { $this->_libelle =   $donnees['Identifiant']; }
        if (isset($donnees['Semestre']))  { $this->_semestre =   $donnees['Semestre']; }
        if (isset($donnees['Intitule']))  { $this->_nom =   $donnees['Intitule']; }
        if (isset($donnees['Notes']))  { $this->_note =   $donnees['Notes']; }
	}

	// GETTERS

	public function idRessource() { return $this->_idressource;}
	public function libelle()     { return $this->_libelle;}
    public function semestre()    { return $this->_semestre;}
    public function nom()         { return $this->_nom;}
    public function note()        { return $this->_note;}

	// SETTERS

	public function setIdRessource(int $idressource) { $this->_idressource  = $idressource; }
	public function setLibelle(string $libelle)      { $this->_libelle      = $libelle; }
    public function setSemestre(string $semestre)    { $this->_semestre     = $semestre; }
    public function setNom(string $nom)              { $this->_nom          = $nom; }
    public function setNote(string $note)            { $this->_note         = $note; }
}