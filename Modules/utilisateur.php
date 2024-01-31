<?php
/** 
* définition de la classe Utilisateur
*/

class Utilisateur {

	private int $_idutilisateur;
	private int $_idprojet;
	private string $_prenom;
	private string $_nom;
	private string $_motdepasse;
	private string $_mail;
	private string $_idiut;
	private string $_imageprofil;
	private int $_admin;
	
	public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['Id_Utilisateur']))  { $this->_idutilisateur = $donnees['Id_Utilisateur']; }
			if (isset($donnees['Id_Projet']))       { $this->_idprojet      = $donnees['Id_Projet']; }
			if (isset($donnees['Prenom']))          { $this->_prenom        = $donnees['Prenom']; }
			if (isset($donnees['Nom']))             { $this->_nom           = $donnees['Nom']; }
			if (isset($donnees['Mot_de_passe']))    { $this->_motdepasse    = $donnees['Mot_de_passe']; }
			if (isset($donnees['Mail']))            { $this->_mail          = $donnees['Mail']; }
			if (isset($donnees['Identifiant_IUT'])) { $this->_idiut         = $donnees['Identifiant_IUT']; }
			if (isset($donnees['Imageprofil']))     { $this->_imageprofil   = $donnees['Imageprofil']; }
			if (isset($donnees['Admin']))           { $this->_admin         = $donnees['Admin']; }
        } 
		
		 // GETTERS //
		 public function idUtilisateur() {   return $this->_idutilisateur;}
		 public function idProjet()  {       return $this->_idprojet;}
		 public function prenom() {          return $this->_prenom;}
		 public function nom() {             return $this->_nom;}
		 public function motDePasse() {      return $this->_motdepasse;}
		 public function mail() {            return $this->_mail;}
		 public function idIUT() {           return $this->_idiut;}
		 public function imageProfil() {     return $this->_imageprofil;}
		 public function admin() {           return $this->_admin;}
		 
		 // SETTERS //
		 public function setIdUtilisateur(int $idutilisateur) { $this->_idutilisateur = $idutilisateur; }
		 public function setIdProjet(int $idprojet)           { $this->_idprojet      =  $idprojet;}
		 public function setPrenom(string $prenom) {            $this->_prenom        = $prenom; }
		 public function setNom(string $nom) {                  $this->_nom           = $nom; }
		 public function setMotDePasse(string $motdepasse) {    $this->_motdepasse    = $motdepasse; }
		 public function setMail(string $mail) {                $this->_email         = $mail; }	
		 public function setIdIUT(string $idiut) {              $this->_idiut         = $idiut;}
		 public function setImageProfil(string $imageprofil) {  $this->_imageprofil   = $imageprofil;}
		 public function setAdmin(int $admin) {                 $this->_admin         = $admin; }		

}

?>