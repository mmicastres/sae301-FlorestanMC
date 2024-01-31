<?php
include "Modules/utilisateur.php";
include "Models/utilisateurManager.php";


/**
* Définition d'une classe permettant de gérer les membres 
*   en relation avec la base de données	
*/
class UtilisateurController {
    private $utilisateurManager; // instance du manager
	private $categorieManager; // instance du manager
	private $ressourceManager; // instance du manager
	private $projManager; // instance du manager
    private $twig;

	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db, $twig) {
		$this->utilisateurManager = new UtilisateurManager($db);
		$this->categorieManager   = new CategorieManager($db);
		$this->ressourceManager   = new RessourceManager($db);
		$this->projManager = new ProjetManager($db);
		$this->twig = $twig;
	}
    
     
	/**
	* connexion
	* @param aucun
	* @return rien
	*/


function utilisateurConnexion($data) {

        $utilisateur = $this->utilisateurManager->verif_identification($_POST['login'], $_POST['passwd']);

        if ($utilisateur != false)  {

            $_SESSION['acces'] = "oui";

            $_SESSION['admin'] = $utilisateur->admin();
// COPYRIGHT MONSIEUR PECATTE // COPYRIGHT MONSIEUR PECATTE // COPYRIGHT MONSIEUR PECATTE
            $_SESSION['idutilisateur'] = $utilisateur->idUtilisateur();

            if ($_SESSION['admin']==1)

                $message = "Bonjour Admin ".$utilisateur->prenom()." ".$utilisateur->nom()."!";

            else

                $message = "Bonjour ".$utilisateur->prenom()." ".$utilisateur->nom()."!";

            echo $this->twig->render('index.html.twig',array('acces'=> $_SESSION['acces'], 'admin'=> $_SESSION['admin'], 'message'=>$message));

        }

        else { // acces non autorisé : variable de session acces = non

                    $message = "identification incorrecte";

                    $_SESSION['acces'] = "non";

                    echo $this->twig->render('index.html.twig',array('acces'=> $_SESSION['acces'],'message'=>$message));

        }

    }

	/**
	 * ouverture de la page admin 
	 * @param aucun
	 * @return rien
	 */
	function pageAdmin() {
		// $w8ingprojects = $this->utilisateurManager->w8ingprojects();
		echo $this->twig->render('pageadmin.html.twig',array('acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin'])); 
	}

	/**
	* Espace Utilisateur
	* @param aucun
	* @return rien
	*/

	function pageProfil($idutilisateur) {
		$projets = $this->projManager->getProjetsMembre($idutilisateur);
		$infos = $this->utilisateurManager->getInfosMembre($idutilisateur);
		echo $this->twig->render('profil.html.twig',array('projs'=>$projets,'infos'=>$infos,'acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin'])); 
	}

	/**
	 * form modif profil
	 * @param aucun
	 * @return rien
	 */

	function formModifProfil($idutilisateur) {
		$infos = $this->utilisateurManager->getInfosMembre($idutilisateur);
		echo $this->twig->render('modifprofil.html.twig',array('infos'=>$infos,'idutil'=>$idutilisateur,'acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin'])); 
	}
	
	function modifProfil() {

		$util = new Utilisateur($_POST);
		$idutilisateur = $_POST['Id_Utilisateur'];
		$mdp = $_POST['Mot_de_passe'];
		$uploadfile="";

		if ($_FILES["Imageprofil"]["name"]!="") {
			
		
			if ($_FILES["Imageprofil"]["error"]==UPLOAD_ERR_OK) {
		
				$uploaddir = "assets/";
				$uploadfile = $uploaddir . basename($_FILES["Imageprofil"]["name"]);
				if (!move_uploaded_file($_FILES["Imageprofil"]["tmp_name"], $uploadfile)) {
				echo "pb lors du telechargement";
				} 
				else {
					$util->setImageProfil($uploadfile);
				}
			}
			else {
				// traitement des erreurs
				echo "pas de fichier";
			}
			
		}
		if ($mdp != ""){
			$ok = $this->utilisateurManager->modifProfilWpass($util, $idutilisateur);
		}
		else {
		$ok = $this->utilisateurManager->modifProfil($util, $idutilisateur);
	}
		$message = $ok ?  "Profil mis à jour" : "probleme lors de la modification du profil";
		echo $this->twig->render('index.html.twig',array('message'=>$message,'acces'=> $_SESSION['acces'])); 
	}
	/**
	 * page gestion utilisateurs
	 * @param aucun
	 * @return rien
	 */
	function pageUtilisateurs() {
		
		$utilisateurs = $this->utilisateurManager->getListUtilisateurs();
		echo $this->twig->render('adminutilisateurs.html.twig',array('utilisateurs'=>$utilisateurs,'acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin'])); 	
	}


     /**
     * suppression dans la BD d'un utilisateur à partir de l'id choisi dans le form précédent
     * @param aucun
     * @return rien
    */
    public function suppUtilisateur() {
        $idutilisateur = new Utilisateur($_POST);
		$this->utilisateurManager->deletecommutilisateur($idutilisateur);
		$this->utilisateurManager->deletecontrutilisateur($idutilisateur);
        $ok = $this->utilisateurManager->delete($idutilisateur);
         $message = $ok ?  "Utilisateur supprimé" : "probleme lors de la supression de l'utilisateur";
        echo $this->twig->render('pageadmin.html.twig',array('message'=>$message,'acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin']));
    }


	/**
	* formulaire ajout
	* @param aucun
	* @return rien
	*/
	public function formAjoutUtilisateur() {
		echo $this->twig->render('utilisateur_ajout.html.twig',array('acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin'])); 
	}

	/**
	 * inscription à partir du form
	 * @param aucun
	 * @return rien
	 */
	function utilisateurInscription() {
		// vérification que le login utilisé n'a pas déjà été utilisé
		// $utilisateur = $this->utilisateurManager->verif_doublon($_POST['login'],$_POST['passwd']);
		$util = new Utilisateur($_POST);
		
		$uploadfile="";
		if ($_FILES["Imageprofil"]["name"]!="") {
			
		
			if ($_FILES["Imageprofil"]["error"]==UPLOAD_ERR_OK) {
		
				$uploaddir = "assets/";
				$uploadfile = $uploaddir . basename($_FILES["Imageprofil"]["name"]);
				if (!move_uploaded_file($_FILES["Imageprofil"]["tmp_name"], $uploadfile)) {
				echo "pb lors du telechargement";
				} 
				else {
					$util->setImageProfil($uploadfile);
				}
			}
			else {
				// traitement des erreurs
				echo "pas de fichier";
			}

		$ok = $this->utilisateurManager->addutilisateur($util);
		$message = $ok ? "Inscription confirmée" : "probleme lors de l'inscription";
		if ($_SESSION['admin']==1) {
		echo $this->twig->render('pageadmin.html.twig',array('message'=>$message,'acces'=> $_SESSION['acces'])); 
		}
		else {
		echo $this->twig->render('index.html.twig',array('message'=>$message,'acces'=> $_SESSION['acces']));
		}
		 
	}
}
	/**
	* deconnexion
	* @param aucun
	* @return rien
	*/
	function utilisateurDeconnexion() {
		$_SESSION['acces'] = "non"; // acces non autorisé
		$_SESSION['admin'] = "";
		$_SESSION['Id_Utilisateur'] = "";
		$message = "vous êtes déconnecté";
		echo $this->twig->render('index.html.twig',array('acces'=> $_SESSION['acces'],'message'=>$message)); 
	 
	}
	

	/**
	* formulaire de connexion
	* @param aucun
	* @return rien
	*/
	function utilisateurFormulaire() {
		echo $this->twig->render('membre_connexion.html.twig',array('acces'=> $_SESSION['acces'])); 
	}
	
	/**
     * formulaire d'inscription
     * @param aucun
	 * @return rien
     */

     function inscriptionFormulaire() {
        echo $this->twig->render('utilisateur_inscription.html.twig',array('acces'=> $_SESSION['acces'])); 
	}
}