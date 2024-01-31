<?php 

class CommentaireManager {

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

    public function commentairesProjet($id_Projet){
		$commentaires = array();
		$req = "SELECT Texte_Commentaire, DateCommentaire, Nom, Prenom, Imageprofil FROM `SAE301_Commente` INNER JOIN SAE301_Projet ON SAE301_Projet.Id_Projet = SAE301_Commente.Id_Projet INNER JOIN SAE301_Utilisateur ON SAE301_Utilisateur.Id_Utilisateur = SAE301_Commente.Id_Utilisateur WHERE SAE301_Projet.Id_Projet = ?;";
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
			$commentaires[] = new Commentaire($donnees);
		}
		return $commentaires;
	}

	/**
	* ajout d'un projet dans la BD
	* @param projet à ajouter
	* @return int true si l'ajout a bien eu lieu, false sinon
	*/
	public function add(Commentaire $commentaire, $idutilisateur) 
	{

		// calcul d'un nouveau code de commentaire non déja utilisé = Maximum + 1
		$stmt = $this->_db->prepare("SELECT max(Id_Commentaire) AS maximum FROM SAE301_Commente");
		$stmt->execute();
		$commentaire->setIdCommentaire($stmt->fetchColumn()+1);
		// requete d'ajout dans la BD
		$req = "INSERT INTO SAE301_Commente ( Id_Utilisateur, Id_Projet, Texte_Commentaire) VALUES ( ?, ?, ?)";
		$stmt = $this->_db->prepare($req);
		$res  = $stmt->execute(array( $idutilisateur, $commentaire->idProjet(), $commentaire->texteCommentaire()));
				
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;


	}
	
}

?>