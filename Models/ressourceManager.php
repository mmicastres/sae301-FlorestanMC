<?php 

class RessourceManager {

    private $_db; // Instance de PDO - objet de connexion au SGBD
	
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db) {
		$this->_db=$db;
	}

	/**
	* retourne l'ensemble des Catégories présentes dans la BD 
	* @return Ressource[]
	*/
		public function getListRessources() {
			$ressources = array();
			$req = "SELECT * FROM `SAE301_Contexte`;";
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
				$ressources[] = new Ressource($donnees);
			}
			return $ressources;
		}

		public function ressourceProjet($id_Projet){
            // récupération de la ressource d'un projet donné
            $contr = array();
            $req = "SELECT SAE301_Contexte.Id_Contexte, Identifiant, Semestre, Intitule FROM SAE301_Contexte INNER JOIN SAE301_Projet ON SAE301_Contexte.Id_Contexte = SAE301_Projet.Id_Contexte WHERE SAE301_Projet.Id_Projet = ?;";
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
                $ressource[] = new Ressource($donnees);
            }
            return $ressource;
        }

		public function addressource(Ressource $ressource) {
            // calcul d'une nouvelle catégorie non déja utilisé = Maximum + 1
            $stmt = $this->_db->prepare("SELECT max(Id_Contexte) AS maximum FROM SAE301_Contexte");
            $stmt->execute();
            $ressource->setIdRessource($stmt->fetchColumn()+1);
            
            // requete d'ajout dans la BD
            $req = "INSERT INTO `SAE301_Contexte` (`Id_Contexte`, `Identifiant`,`Semestre`,`Intitule`) VALUES (?,?,?,?)";
            $stmt = $this->_db->prepare($req);
            $res  = $stmt->execute(array($ressource->idRessource(), $ressource->libelle(),$ressource->semestre(), $ressource->nom()));		
            // pour debuguer les requêtes SQL
            $errorInfo = $stmt->errorInfo();
            if ($errorInfo[0] != 0) {
                print_r($errorInfo);
            }
            return $res;
        }
        
        

		 /**
        * suppression d'une ressource dans la base de données
        * @param Ressource 
        * @return boolean true si suppression, false sinon
        */
        
        public function delete(Ressource $ressource) : bool {
            $req = "DELETE FROM SAE301_Contexte WHERE Id_Contexte = ?";
            $stmt = $this->_db->prepare($req);
            return $stmt->execute(array($ressource->idressource()));
        }


}

?>