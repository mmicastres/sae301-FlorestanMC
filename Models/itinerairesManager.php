<?php

/**
* Définition d'une classe permettant de gérer les itinéraires 
*   en relation avec la base de données	
*/
class ProjetManager {

	private $_db; // Instance de PDO - objet de connexion au SGBD
	
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db) {
		$this->_db=$db;
	}
	
	/**
	* retourne l'ensemble des projets présents dans la BD 
	* @return Projet[]
	*/
	public function getProjets() {
		$projs = array();
		$req = "SELECT Id_Projet, Titre, Description, Image, Id_Contexte FROM SAE301_Projet";
		$stmt = $this->_db->prepare($req);
		$stmt->execute();
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		while ($donnees = $stmt->fetch())
		{
			$projs[] = new Projet($donnees);
		}
		return $projs;
	}

	public function voirProjet($id_Projet) {
		$proj = array();
		$req = "SELECT SAE301_Projet.Id_Projet, Titre, Description, Image, Demo, Sources, Id_Contexte FROM `SAE301_Projet` WHERE SAE301_Projet.Id_Projet = ?; ";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($id_Projet));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		while ($donnees = $stmt->fetch())
		{
			$proj[] = new Projet($donnees);
		}
		return $proj;
	}

	/**
	* méthode de recherche d'un projet dans la BD à partir des critères passés en paramètre
	* @param string $titre
	* @param string $description
	* @return Projet[]
	*/
	public function search(string $titre, string $description) {
		$req = "SELECT Id_Projet, Titre, Description, Image, Id_Contexte FROM SAE301_Projet";
		$cond = '';

		if ($titre<>"") 
		{ 	$cond = $cond . " Titre like '%". $titre ."%'";
		}
		if ($description<>"") 
		{ 	if ($cond<>"") $cond .= " AND ";
			$cond = $cond . " Description like '%" . $description ."%'";
		}
		if ($cond <>"")
		{ 	$req .= " WHERE " . $cond;
		}
		// execution de la requete				
		$stmt = $this->_db->prepare($req);
		$stmt->execute();
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		$projs = array();
		while ($donnees = $stmt->fetch())
		{
			$projs[] = new Projet($donnees);
		}
		return $projs;
	}

	/**
	* retourne l'ensemble des itinéraires présents dans la BD pour un membre
	* @param int idmembre
	* @return Projet[]
	*/
	public function getProjetsMembre(int $idutilisateur) {
		$projs = array();
		$req = "SELECT SAE301_Projet.Id_Projet, Titre, Description, Image, Id_Contexte FROM `SAE301_Projet` INNER JOIN SAE301_Contribue ON SAE301_Contribue.Id_Projet = SAE301_Projet.Id_Projet INNER JOIN SAE301_Utilisateur ON SAE301_Utilisateur.Id_Utilisateur = SAE301_Contribue.Id_Utilisateur WHERE SAE301_Utilisateur.Id_Utilisateur = ?;";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idutilisateur));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// recup des données
		while ($donnees = $stmt->fetch())
		{
			$projs[] = new Projet($donnees);
		}
		return $projs;
	}
}
class ItineraireManager {
    
	private $_db; // Instance de PDO - objet de connexion au SGBD
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db) {
		$this->_db=$db;
	}
        
	/**
	* ajout d'un itineraire dans la BD
	* @param itineraire à ajouter
	* @return int true si l'ajout a bien eu lieu, false sinon
	*/
	public function add(Itineraire $iti) {
		// calcul d'un nouveau code d'itineraire non déja utilisé = Maximum + 1
		$stmt = $this->_db->prepare("SELECT max(iditi) AS maximum FROM itineraire");
		$stmt->execute();
		$iti->setIdIti($stmt->fetchColumn()+1);
		
		// requete d'ajout dans la BD
		$req = "INSERT INTO itineraire (iditi,idmembre,lieudepart,lieuarrivee,heuredepart,datedepart,tarif,nbplaces,bagagesautorises,details) VALUES (?,?,?,?,?,?,?,?,?,?)";
		$stmt = $this->_db->prepare($req);
		$res  = $stmt->execute(array($iti->idIti(), $iti->idMembre(), $iti->lieuDepart(), $iti->lieuArrivee(), $iti->heureDepart(),dateChgmtFormat($iti->dateDepart()), $iti->tarif(), $iti->nbPlaces(), $iti->bagagesAutorises(), $iti->details()));		
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}
        
	/**
	* nombre d'itinéraires dans la base de données
	* @return int le nb d'itinéraires
	*/
	public function count():int {
		$stmt = $this->_db->prepare('SELECT COUNT(*) FROM itineraire');
		$stmt->execute();
		return $stmt->fetchColumn();
	}
        
	/**
	* suppression d'un itineraire dans la base de données
	* @param Itineraire 
	* @return boolean true si suppression, false sinon
	*/
	public function delete(Itineraire $iti) : bool {
		$req = "DELETE FROM itineraire WHERE iditi = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($iti->iditi()));
	}
		
	/**
	* echerche dans la BD d'un itineraire à partir de son id
	* @param int $iditi 
	* @return Itineraire 
	*/
	public function get(int $iditi) : Itineraire {	
		$req = 'SELECT iditi,idmembre,lieudepart,lieuarrivee,heuredepart,date_format(datedepart,"%d/%c/%Y")as datedepart,tarif,nbplaces,bagagesautorises,details FROM itineraire WHERE iditi=?';
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($iditi));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		$iti = new Itineraire($stmt->fetch());
		return $iti;
	}		
		
	/**
	* retourne l'ensemble des itinéraires présents dans la BD 
	* @return Itineraire[]
	*/
	public function getProjets() {
		$itis = array();
		$req = "SELECT Id_Utilisateur, Prenom, Mot_de_passe, Mail, Identifiant_IUT FROM SAE301_Utilisateur";
		$stmt = $this->_db->prepare($req);
		$stmt->execute();
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		while ($donnees = $stmt->fetch())
		{
			$itis[] = new Itineraire($donnees);
		}
		return $itis;
	}

	/**
	* retourne l'ensemble des itinéraires présents dans la BD pour un membre
	* @param int idmembre
	* @return Itineraire[]
	*/
	public function getProjetsMembre(int $idmembre) {
		$itis = array();
		$req = "SELECT Id_Projet, Titre, Description, Image, Id_Contexte,"
		. "tarif, nbplaces, bagagesautorises, details FROM itineraire WHERE idmembre=?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idmembre));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// recup des données
		while ($donnees = $stmt->fetch())
		{
			$itis[] = new Itineraire($donnees);
		}
		return $itis;
	}
	/**
	* méthode de recherche d'itinéraires dans la BD à partir des critères passés en paramètre
	* @param string $lieudepart
	* @param string $lieudepart
	* @param string $datedepart
	* @return Itineraire[]
	*/
	public function search(string $lieudepart, string $lieuarrivee, string $datedepart) {
		$req = "SELECT iditi,lieudepart,lieuarrivee,heuredepart,date_format(datedepart,'%d/%c/%Y')as datedepart,tarif,nbplaces,bagagesautorises,details FROM itineraire";
		$cond = '';

		if ($lieudepart<>"") 
		{ 	$cond = $cond . " lieudepart like '%". $lieudepart ."%'";
		}
		if ($lieuarrivee<>"") 
		{ 	if ($cond<>"") $cond .= " AND ";
			$cond = $cond . " lieuarrivee like '%" . $lieuarrivee ."%'";
		}
		if ($datedepart<>"") 
		{ 	if ($cond<>"") $cond .= " AND ";
			$cond = $cond . " datedepart = '" . dateChgmtFormat($datedepart) . "'";
		}
		if ($cond <>"")
		{ 	$req .= " WHERE " . $cond;
		}
		// execution de la requete				
		$stmt = $this->_db->prepare($req);
		$stmt->execute();
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		$itineraires = array();
		while ($donnees = $stmt->fetch())
		{
			$itineraires[] = new Itineraire($donnees);
		}
		return $itineraires;
	}
	
	/**
	* modification d'un itineraire dans la BD
	* @param Itineraire
	* @return boolean 
	*/
	public function update(Itineraire $iti) : bool {
		$req = "UPDATE itineraire SET lieudepart = :lieudepart, "
					. "lieuarrivee = :lieuarrivee, "
					. "heuredepart = :heuredepart, "
					. "datedepart  = :datedepart, "
					. "tarif = :tarif, "
					. "nbplaces = :nbplaces, "
					. "bagagesautorises= :bagages, "
					. "details = :details" 
					. " WHERE iditi = :iditi";
		//var_dump($iti);

		$stmt = $this->_db->prepare($req);
		$stmt->execute(array(":lieudepart" => $iti->lieuDepart(),
								":lieuarrivee" => $iti->lieuArrivee(),
								":heuredepart" => $iti->heureDepart(),
								":datedepart" => dateChgmtFormat($iti->dateDepart()),
								":tarif" => $iti->tarif(), 
								":nbplaces" => $iti->nbPlaces(),
								":bagages" => $iti->bagagesAutorises(),
								":details" => $iti->details(),
								":iditi" => $iti->idIti() ));
		return $stmt->rowCount();
		
	}
}

// fontion de changement de format d'une date
// tranformation de la date au format j/m/a au format a/m/j
function dateChgmtFormat($date) {
//echo "date:".$date;
		list($j,$m,$a) = explode("/",$date);
		return "$a/$m/$j";
}
?>