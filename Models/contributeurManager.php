<?php 

class UtilisateursManager {

    private $_db; // Instance de PDO - objet de connexion au SGBD
	
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db) {
		$this->_db=$db;
	}

    /**
	* retourne l'ensemble des contributeurs présents dans la BD 
	* @return Utilisateur[]
	*/

    public function contrProjet($id_Projet){
		$contr = array();
		$req = "SELECT Nom, Prenom FROM `SAE301_Utilisateur` INNER JOIN SAE301_Contribue ON SAE301_Contribue.Id_Utilisateur = SAE301_Utilisateur.Id_Utilisateur INNER JOIN SAE301_Projet ON SAE301_Projet.Id_Projet = SAE301_Contribue.Id_Projet WHERE SAE301_Projet.Id_Projet = ?;";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($id_Projet));
		// pour debuguer les requêtes SQL'tz
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// recup des données
		while ($donnees = $stmt->fetch())
		{
			$contr[] = new Utilisateur($donnees);
		}
		return $contr;
	}

}

?>