<?php

/**
* Définition d'une classe permettant de gérer les utilisateurs 
* en relation avec la base de données
*
*/

class UtilisateurManager
    {
        private $_db; // Instance de PDO - objet de connexion au SGBD
        
		/** 
		* Constructeur = initialisation de la connexion vers le SGBD
		*/
        public function __construct($db) {
            $this->_db=$db;
        }
		
		/**
		* verification de l'identité d'un utilisateur (Login/password)
		* @param string $login
		* @param string $password
		* @return utilisateur si authentification ok, false sinon
		*/
		public function verif_identification($login, $password) {
		//echo $login." : ".$password;
			$req = "SELECT Id_Utilisateur, Nom, Prenom, Mot_de_passe, Admin FROM SAE301_Utilisateur WHERE Mail=:login";
			$stmt = $this->_db->prepare($req); 
			$stmt->execute(array(":login" => $login));
                if ($data=$stmt->fetch()) { 
                   if (password_verify($password, $data["Mot_de_passe"])){
                    $utilisateur = new Utilisateur($data);
                    return $utilisateur;
                    }}
			else return false;
		}


        	/**
            * retourne l'ensemble des Utilisateurs présentes dans la BD 
            * @return Utilisateur[]
            */
		public function getListUtilisateurs() {
			$utilisateurs = array();
			$req = "SELECT Id_Utilisateur, Prenom, Nom, Mail, Identifiant_IUT FROM `SAE301_Utilisateur`";
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
                
				$utilisateurs[] = new Utilisateur($donnees);
			}
			return $utilisateurs;
		}

        /**
	* modification d'un profil dans la BD si le mdp est changé
	* @param Utilisateur
	* @return boolean 
	*/
	public function modifProfilWpass(Utilisateur $util, $idutilisateur) : bool {
		$req = "UPDATE SAE301_Utilisateur SET Prenom = :Prenom, Nom = :Nom, Mot_de_passe = :Mot_de_passe, Mail  = :Mail, Identifiant_IUT = :Identifiant_IUT, Imageprofil = :Imageprofil WHERE Id_Utilisateur= :Id_Utilisateur";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array(":Prenom" => $util->prenom(),":Nom" => $util->nom(),":Mot_de_passe" => $util->motDePasse(),":Mail" => $util->mail(),":Identifiant_IUT" => $util->idIUT(), ":Imageprofil" => $util->imageProfil(), ":Id_Utilisateur" => $idutilisateur));
		return $stmt->rowCount();
		
	}

    /**
	* modification d'un profil dans la BD 
	* @param Utilisateur
	* @return boolean 
	*/
	public function modifProfil(Utilisateur $util, $idutilisateur) : bool {
		$req = "UPDATE SAE301_Utilisateur SET Prenom = :Prenom, Nom = :Nom, Mail  = :Mail, Identifiant_IUT = :Identifiant_IUT, Imageprofil = :Imageprofil WHERE Id_Utilisateur= :Id_Utilisateur";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array(":Prenom" => $util->prenom(),":Nom" => $util->nom(),":Mail" => $util->mail(),":Identifiant_IUT" => $util->idIUT(), ":Imageprofil" => $util->imageProfil(), ":Id_Utilisateur" => $idutilisateur));
		return $stmt->rowCount();
		
	}
    
        /**
         * vérification que l'utilisateur n'existe pas déjà
         * @param string $login
		 * @param string $password
		 * return rien si il existe déjà, @return utilisateur si il n'existe pas
         */
        // public function verif_doublon(int $login,int $password) {
        //     $req = "SELECT Id_Utilisateur, Nom, Prenom FROM SAE301_Utilisateur WHERE Mail=:login and Mot_de_passe=:password ";
		// 	$stmt = $this->_db->prepare($req);
		// 	$stmt->execute(array(":login" => $login, ":password" => $password));
		// 	if ($data=$stmt->fetch()) { 
		// 		return "Cet email a déjà été utilisé";   
		// 		}
		// 	else {
        //     
        //     } 
        // }

        public function addutilisateur(Utilisateur $util) {
            // calcul d'un nouveau   utilisateur non déja utilisé = Maximum + 1
            $stmt = $this->_db->prepare("SELECT max(Id_Utilisateur) AS maximum FROM SAE301_Utilisateur");
            $stmt->execute();

            $util->setIdUtilisateur($stmt->fetchColumn()+1);
            
            // requete d'ajout dans la BD
            $req = "INSERT INTO `SAE301_Utilisateur` (`Id_Utilisateur`, `Prenom`, `Nom`, `Mot_de_passe`, `Mail`, `Identifiant_IUT`, `Imageprofil`) VALUES (?,?,?,?,?,?,?)";
            $stmt = $this->_db->prepare($req);

            $hashedpassword = password_hash($util->motDePasse(),PASSWORD_DEFAULT);
            $res  = $stmt->execute(array($util->idUtilisateur(), $util->prenom(), $util->nom(), $hashedpassword, $util->mail(), $util->idIUT(), $util->imageProfil()));		
            // pour debuguer les requêtes SQL
            $errorInfo = $stmt->errorInfo();
            if ($errorInfo[0] != 0) {
                print_r($errorInfo);
            }
            return $res;
        }

        // public function w8ingProjects() {
        //     // Affichage des projets en attente de validation
            
        // }


        // Ajout des contributeurs dans un nouveau projet

		public function addcontributeurs($idcontributeur, $idprojet) 
        {
            // requete d'ajout dans la BD
             
                $req = "INSERT INTO SAE301_Contribue (Id_Projet, Id_Utilisateur) VALUES (?,?)"; 
                $stmt = $this->_db->prepare($req);
                $res  = $stmt->execute(array($idprojet, $idcontributeur));
              
            // pour debuguer les requêtes SQL
            $errorInfo = $stmt->errorInfo();
            if ($errorInfo[0] != 0) {
                print_r($errorInfo);
            }
            return $res;
    
        }

        /**
	* suppression des contributions auquel appartient un utilisateur dans la base de données
	* @param Utilisateur 
	* @return boolean true si suppression, false sinon
	*/
	public function deletecontrutilisateur(Utilisateur $idutilisateur) : bool {
		$req = "DELETE FROM SAE301_Contribue WHERE Id_Utilisateur = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($idutilisateur->idUtilisateur()));
	}

       /**
	* suppression des commentaires d'un utilisateur dans la base de données
	* @param Utilisateur 
	* @return boolean true si suppression, false sinon
	*/
	public function deletecommutilisateur(Utilisateur $idutilisateur) : bool {
		$req = "DELETE FROM SAE301_Commente WHERE Id_Utilisateur = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($idutilisateur->idUtilisateur()));
	}
        

        /**
        * suppression d'un utilisateur dans la base de données
        * @param Utilisateur 
        * @return boolean true si suppression, false sinon
        */
        
        public function delete(Utilisateur $utilisateur) : bool {
            $req = "DELETE FROM SAE301_Utilisateur WHERE Id_Utilisateur = ?";
            $stmt = $this->_db->prepare($req);
            return $stmt->execute(array($utilisateur->idutilisateur()));
        }

        
        public function getInfosMembre($idutilisateur) {
            // récupération de toutes les informations d'un utilisateur donné
            $infos = array();
            $req = "SELECT Id_Utilisateur, Prenom, Nom, Mail, Mot_De_Passe, Identifiant_IUT, Imageprofil, Admin FROM `SAE301_Utilisateur` WHERE Id_Utilisateur =?";
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
                $infos[] = new Utilisateur($donnees);
            }
            return $infos;
        }

        public function contrProjet($id_Projet){
            // récupération de tous les contributeurs d'un projet donné
            $contr = array();
            $req = "SELECT SAE301_Projet.Id_Projet, SAE301_Utilisateur.Id_Utilisateur, Nom, Prenom FROM `SAE301_Utilisateur` INNER JOIN SAE301_Contribue ON SAE301_Contribue.Id_Utilisateur = SAE301_Utilisateur.Id_Utilisateur INNER JOIN SAE301_Projet ON SAE301_Projet.Id_Projet = SAE301_Contribue.Id_Projet WHERE SAE301_Projet.Id_Projet = ?;";
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
                $contr[] = new Utilisateur($donnees);
            }
            return $contr;
        }

   

    }

	
        

	
?>