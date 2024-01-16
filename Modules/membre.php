<?php
/** 
* définition de la classe Utilisateur
*/

class Utilisateur {

	private int $_idutilisateur;
	private string $_prenom;
	private string $_nom;
	private string $_motdepasse;
	private string $_mail;
	private string $_idiut;
	private int $_admin;
	
	public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['Id_Utilisateur']))  { $this->_idutilisateur = $donnees['Id_Utilisateur']; }
			if (isset($donnees['Nom']))             { $this->_nom =           $donnees['Nom']; }
			if (isset($donnees['Prenom']))          { $this->_prenom =        $donnees['Prenom']; }
			if (isset($donnees['Mot_de_passe']))    { $this->_motdepasse =    $donnees['Mot_de_passe']; }
			if (isset($donnees['Mail']))            { $this->_mail =          $donnees['Mail']; }
			if (isset($donnees['Identifiant_IUT'])) { $this->_idiut =         $donnees['Identifiant_IUT']; }
			if (isset($donnees['Admin']))           { $this->_admin =         $donnees['Admin']; }
        } 
		
		 // GETTERS //
		 public function idUtilisateur() {   return $this->_idutilisateur;}
		 public function nom() {             return $this->_nom;}
		 public function prenom() {          return $this->_prenom;}
		 public function motDePasse() {      return $this->_motdepasse;}
		 public function mail() {            return $this->_mail;}
		 public function idIUT() {           return $this->_idiut;}
		 public function admin() {           return $this->_admin;}
		 
		 // SETTERS //
		 public function setIdUtilisateur(int $idutilisateur) { $this->_idutilisateur = $idutilisateur; }
		 public function setNom(string $nom) {                  $this->_nom =           $nom; }
		 public function setPrenom(string $prenom) {            $this->_prenom =        $prenom; }
		 public function setMotDePasse(string $motdepasse) {    $this->_motdepasse =    $motdepasse; }
		 public function setMail(string $mail) {                $this->_email =         $mail; }	
		 public function setIdentifiantIUT(string $idiut) {     $this->_idiut =         $idiut;}
		 public function setAdmin(int $admin) {                 $this->_admin =         $admin; }		

}

class Membre {
        private int $_idmembre;
        private string $_nom;
        private string $_prenom;
		private string $_email;
		private string $_password;
		private int $_anneenaissance;
		private string $_sexe;
		private string $_voiture;
		private string $_telportable;
		private int $_admin;
		
        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['Id_Utilisateur'])) { $this->_idmembre = $donnees['Id_Utilisateur']; }
			if (isset($donnees['Nom'])) { $this->_nom = $donnees['Nom']; }
			if (isset($donnees['Prenom'])) { $this->_prenom = $donnees['Prenom']; }
			if (isset($donnees['Mail'])) { $this->_email = $donnees['Mail']; }
			if (isset($donnees['Mot_de_passe'])) { $this->_password = $donnees['Mot_de_passe']; }
			if (isset($donnees['anneenaissance'])) { $this->_anneenaissance = $donnees['anneenaissance']; }
			if (isset($donnees['sexe'])) { $this->_sexe = $donnees['sexe']; }
			if (isset($donnees['voiture'])) { $this->_voiture = $donnees['voiture']; }
			if (isset($donnees['telportable'])) { $this->_telportable = $donnees['telportable']; }
			if (isset($donnees['admin'])) { $this->_admin = $donnees['admin']; }
        }           
        // GETTERS //
		public function idMembre() { return $this->_idmembre;}
		public function nom() { return $this->_nom;}
		public function prenom() { return $this->_prenom;}
		public function email() { return $this->_email;}
		public function password() { return $this->_password;}
		public function anneeNaissance() { return $this->_anneenaissance;}
		public function sexe() { return $this->_sexe;}
		public function voiture() { return $this->_voiture;}
		public function telPortable() { return $this->_telportable;}
		public function admin() { return $this->_admin;}
		public function getAge() { return (date('Y')- $this->_anneenaissance) ; }
		
		// SETTERS //
		public function setIdMembre(int $idmembre) { $this->_idmembre = $idmembre; }
        public function setNom(string $nom) { $this->_nom= $nom; }
		public function setPrenom(string $prenom) { $this->_prenom = $prenom; }
		public function setEmail(string $email) { $this->_email = $email; }
		public function setPassword(string $password) { $this->_password = $password; }
		public function setAnneeNaissance(int $anneenaissance) { $this->_anneenaissance = $anneenaissance; }
		public function setSexe(string $sexe) { $this->_sexe = $sexe; }
		public function setVoiture(string $voiture) { $this->_voiture = $voiture; }
		public function setTelPortable(string $telportable) { $this->_telportable = $telportable; }		
		public function setAdmin(int $admin) { $this->_admin = $admin; }		

    }

?>