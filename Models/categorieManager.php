<?php 

class CategorieManager {

    private $_db; // Instance de PDO - objet de connexion au SGBD
	
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db) {
		$this->_db=$db;
	}

	/**
	* retourne l'ensemble des Catégories présentes dans la BD 
	* @return Categorie[]
	*/
		public function getListCategories() {
			$categories = array();
			$req = "SELECT * FROM `SAE301_Categorie`;";
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
				$categories[] = new Categorie($donnees);
			}
			return $categories;
		}

		// Ajout des catégories dans un nouveau projet

		public function addappartient($idcategorie, $idprojet) 
	{
		// requete d'ajout dans la BD	

			$req = "INSERT INTO SAE301_Appartient (Id_Projet, Id_Categorie) VALUES (?,?)"; 
			$stmt = $this->_db->prepare($req);
			$res  = $stmt->execute(array($idprojet, $idcategorie));
		  
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;

	}


		public function addcategorie(Categorie $categorie) {
            // calcul d'une nouvelle catégorie non déja utilisé = Maximum + 1
            $stmt = $this->_db->prepare("SELECT max(Id_Categorie) AS maximum FROM SAE301_Categorie");
            $stmt->execute();
            $categorie->setIdCategorie($stmt->fetchColumn()+1);
            
            // requete d'ajout dans la BD
            $req = "INSERT INTO `SAE301_Categorie` (`Id_Categorie`, `Nom_Categorie`) VALUES (?,?)";
            $stmt = $this->_db->prepare($req);
            $res  = $stmt->execute(array($categorie->idCategorie(), $categorie->nomCategorie()));		
            // pour debuguer les requêtes SQL
            $errorInfo = $stmt->errorInfo();
            if ($errorInfo[0] != 0) {
                print_r($errorInfo);
            }
            return $res;
        }

    public function categoriesProjet($id_Projet){
		$categories = array();
		$req = "SELECT SAE301_Appartient.Id_Categorie, Nom_Categorie FROM `SAE301_Categorie` INNER JOIN SAE301_Appartient ON SAE301_Appartient.Id_Categorie = SAE301_Categorie.Id_Categorie INNER JOIN SAE301_Projet ON SAE301_Projet.Id_Projet = SAE301_Appartient.Id_Projet WHERE SAE301_Projet.Id_Projet = ?;";
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
			$categories[] = new Categorie($donnees);
		}
		return $categories;
	}


	/**
        * suppression d'une catéogire dans la base de données
        * @param Categorie 
        * @return boolean true si suppression, false sinon
        */
        
        public function deleteappartient(Categorie $categorie) : bool {
            $req = "DELETE FROM SAE301_Appartient WHERE Id_Categorie = ?";
            $stmt = $this->_db->prepare($req);
            return $stmt->execute(array($categorie->idcategorie()));
        }

	 /**
        * suppression d'une catéogire dans la base de données
        * @param Categorie 
        * @return boolean true si suppression, false sinon
        */
        
        public function delete(Categorie $categorie) : bool {
            $req = "DELETE FROM SAE301_Categorie WHERE Id_Categorie = ?";
            $stmt = $this->_db->prepare($req);
            return $stmt->execute(array($categorie->idcategorie()));
        }

		

}

?>