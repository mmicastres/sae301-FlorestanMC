<?php

/**
* définition de la classe Commentaire
*/

class Commentaire {

	private int $_idcommentaire;
    private int $_idutilisateur;
    private int $_idprojet;
	private string $_textecommentaire;
    private string $_datecommentaire;
    private string $_nom;
    private string $_prenom;
    private string $_imageprofil;
	
	public function __construct(array $donnees) {

		if (isset($donnees['Id_Commentaire']))    { $this->_idcategorie  =      $donnees['Id_Commentaire']; }
		if (isset($donnees['Id_Utilisateur']))    { $this->_idutilisateur =     $donnees['Id_Utilisateur']; }
        if (isset($donnees['Id_Projet']))         { $this->_idprojet =          $donnees['Id_Projet']; }
        if (isset($donnees['Texte_Commentaire'])) { $this->_textecommentaire =  $donnees['Texte_Commentaire']; }
        if (isset($donnees['DateCommentaire']))   { $this->_datecommentaire =   $donnees['DateCommentaire']; }
        if (isset($donnees['Nom']))               { $this->_nom =               $donnees['Nom'];}
        if (isset($donnees['Prenom']))            { $this->_prenom =            $donnees['Prenom'];}
        if (isset($donnees['Imageprofil']))       { $this->_imageprofil =       $donnees['Imageprofil'];}
	}

	// GETTERS

	public function idCommentaire()    { return $this->_idcommentaire;}
	public function idUtilisateur()    { return $this->_idutilisateur;}
    public function idProjet()         { return $this->_idprojet;}
    public function texteCommentaire() { return $this->_textecommentaire;}
    public function dateCommentaire()  { return $this->_datecommentaire;}
    public function nom()              { return $this->_nom;}
    public function prenom()           { return $this->_prenom;}
    public function imageProfil()      { return $this->_imageprofil;}
	// SETTERS

	public function setIdCommentaire(int $idcommentaire)          { $this->_idcommentaire    = $idcommentaire; }
	public function setIdUtilisateur(string $idutilisateur)       { $this->_idutilisateur    = $idutilisateur; }
    public function setIdProjet(string $idprojet)                 { $this->_idprojet         = $idprojet; }
    public function setTexteCommentaire(string $textecommentaire) { $this->_textecommentaire = $textecommentaire; }
    public function setDateCommentaire(string $datecommentaire)   { $this->_datecommentaire  = $datecommentaire; }
    public function setNom(string $nom)                           { $this->_nom              = $nom;}
    public function setPrenom(string $prenom)                     { $this->_prenom           = $prenom;}
    public function setImageProfil(string $imageprofil)           { $this->_imageprofil      = $imageprofil;}
}