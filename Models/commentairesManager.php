<?php 

class CommentairesManager {

    private $_db; // Instance de PDO - objet de connexion au SGBD
	
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db) {
		$this->_db=$db;
	}

    /**
	* retourne l'ensemble des contributeurs présents dans la BD 
	* @return Commentaire[]
	*/

    public function commentaire($id_Projet){
		$categories = array();
		$req = "SELECT Nom_Categorie FROM `SAE301_Categorie` INNER JOIN SAE301_Appartient ON SAE301_Appartient.Id_Categorie = SAE301_Categorie.Id_Categorie INNER JOIN SAE301_Projet ON SAE301_Projet.Id_Projet = SAE301_Appartient.Id_Projet WHERE SAE301_Projet.Id_Projet = ?;";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($id_Projet));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// recup des données
		while ($donnees = $stmt->fetch())
		{
			$categories[] = new Commentaire($donnees);
		}
		return $categories;
	}

}

?>