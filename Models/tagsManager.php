<?php 

class TagsManager {

    private $_db; // Instance de PDO - objet de connexion au SGBD
	
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db) {
		$this->_db=$db;
	}

    /**
	* retourne l'ensemble des contributeurs présents dans la BD 
	* @return Tag[]
	*/

    public function tagsProjet($id_Projet){
		$tags = array();
		$req = "SELECT Nom_Tag FROM `SAE301_Tags` INNER JOIN SAE301_Caracterise ON SAE301_Caracterise.Id_Tags = SAE301_Tags.Id_Tags INNER JOIN SAE301_Projet ON SAE301_Projet.Id_Projet = SAE301_Caracterise.Id_Projet WHERE SAE301_Projet.Id_Projet = ?;";
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
			$tags[] = new Tag($donnees);
		}
		return $tags;
	}

}

?>