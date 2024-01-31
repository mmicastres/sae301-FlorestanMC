<?php

include "Modules/projet.php";
include "Modules/tag.php";
include "Models/projetManager.php";
include "Models/tagManager.php";

/**
* Définition d'une classe permettant de gérer les projets 
*   en relation avec la base de données	
*/

class ProjetController {

	private $projManager; // instance du manager
	private $utilisateurManager; // instance du manager
	private $tagManager; // instance du manager
	private $categorieManager; // instance du manager
	private $commentaireManager; // instance du manager
	private $ressourceManager; // instance du manager
	private $twig;
	

	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db, $twig) {

		$this->projManager        = new ProjetManager($db);
		$this->utilisateurManager = new UtilisateurManager($db);
		$this->tagManager        = new TagManager($db);
		$this->categorieManager   = new CategorieManager($db);
		$this->commentaireManager = new CommentaireManager($db);
		$this->ressourceManager   = new RessourceManager($db) ;
		$this->twig = $twig;
		
	}

	/**
	* liste de tous les projets
	* @param aucun
	* @return rien
	*/
	public function listeProjets() {
		$projets = $this->projManager->getProjets();
		echo $this->twig->render('projet_liste.html.twig',array('projs'=>$projets,'acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin'])); 
	}

    // Affichage de la totalité des informations du projet via différentes fonctions
	public function pageProjet() {
		
		$projets       = $this->projManager->voirProjet($_POST["Id_Projet"]);
		$contributeurs = $this->utilisateurManager->contrProjet($_POST["Id_Projet"]);
		$tags          = $this->tagManager->tagsProjet($_POST["Id_Projet"]);
		$categories    = $this->categorieManager->categoriesProjet($_POST["Id_Projet"]);
		$commentaires  = $this->commentaireManager->commentairesProjet($_POST["Id_Projet"]);

		// render la view twig avec les différentes valeurs provenant des requetes contenues dans des variables
		echo $this->twig->render('projet_page.html.twig',array('projs'=>$projets,'contrs'=>$contributeurs,'tags'=>$tags,'categories'=>$categories, 'commentaires'=>$commentaires,'acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin']));

		// var_dump($categories) Si besoin de vérifier ce qu'il y a dans la variable; 
		
	}

	/**
	* formulaire ajout projet
	* @param aucun
	* @return rien
	*/
	public function formAjoutProjet() {
		$contributeurs = $this->utilisateurManager->getListUtilisateurs();
		$tags          = $this->tagManager->getListTags();
		$categories    = $this->categorieManager->getListCategories();
		$ressources    = $this->ressourceManager->getListRessources();
		echo $this->twig->render('projet_ajout.html.twig',array('utilisateurs'=>$contributeurs,'tags'=>$tags,'ressources'=>$ressources,'categories'=>$categories,'acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin'])); 
	}
 
	/**
	* ajout dans la BD d'un projet à partir du form
	* @param aucun
	* @return rien
	*/
	public function ajoutProjet() {
		$proj          = new Projet($_POST);
		$ok = $idprojet = $this->projManager->add($proj);
		
		$idcontributeurs = $_POST['Contributeurs'];
		foreach ($idcontributeurs as $idcontributeur) {
			$this->utilisateurManager->addcontributeurs($idcontributeur, $idprojet);
		}
		$idtags = $_POST['Tags'];
		foreach ($idtags as $idtag) {
			$this->tagManager->addcaracterise($idtag, $idprojet);
		}
		$idcategories = $_POST['Categories'];
		foreach ($idcategories as $idcategorie) {	
			$this->categorieManager->addappartient($idcategorie, $idprojet);
		}
		$message = $ok ? "Projet ajouté" : "probleme lors de l'ajout du projet";
		echo $this->twig->render('index.html.twig',array('message'=>$message,'acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin'])); 
		
 
	}

	/**
	* suppression dans la BD d'un projet à partir de l'id choisi dans le form précédent
	* @param aucun
	* @return rien
	*/
	public function suppProjet() {
		$idprojet = new Projet($_POST);
		$this->projManager->deletecommprojet($idprojet);
		$this->projManager->deletetagsprojet($idprojet);
		$this->projManager->deletecateprojet($idprojet);
		$this->projManager->deletecontrprojet($idprojet);
		$ok = $this->projManager->delete($idprojet);
		$message = $ok ?  "Projet supprimé" : "probleme lors de la supression du projet";
		echo $this->twig->render('index.html.twig',array('message'=>$message,'acces'=> $_SESSION['acces'])); 
	}
	
	/**
	* formulaire modification projet
	* @param aucun
	* @return rien
	*/
	public function formModifProjet() {

		$contributeurs = $this->utilisateurManager->getListUtilisateurs();
		$tags          = $this->tagManager->getListTags();
		$categories    = $this->categorieManager->getListCategories();
		$ressources    = $this->ressourceManager->getListRessources();
		
		
		$oldinfosprojet   = $this->projManager->voirProjet($_POST["Id_Projet"]);
		$oldcontributeurs = $this->utilisateurManager->contrProjet($_POST["Id_Projet"]);
		$oldtags          = $this->tagManager->tagsProjet($_POST["Id_Projet"]);
		$oldcategories    = $this->categorieManager->categoriesProjet($_POST["Id_Projet"]);
		$oldressource    = $this->ressourceManager->ressourceProjet($_POST["Id_Projet"]);
		echo $this->twig->render('projet_modif.html.twig',array('contributeurs'=>$contributeurs,'tags'=>$tags,'categories'=>$categories,'ressources'=>$ressources,'oldinfos'=>$oldinfosprojet,'oldcontributeurs'=>$oldcontributeurs,'oldtags'=>$oldtags,'oldcategories'=>$oldcategories,'oldressource'=>$oldressource,'acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin'])); 
	}

	/**
	* modification du projet
	* @param aucun
	* @return rien
	*/

	public function modifProjet() {
		
		$projet = new Projet($_POST);
		$idprojet = $_POST['Id_Projet'];

		$uploadfile="";
		
		if ($_FILES["Image"]["name"]!="") {
			
		
			if ($_FILES["Image"]["error"]==UPLOAD_ERR_OK) {
		
				$uploaddir = "assets/";
				$uploadfile = $uploaddir . basename($_FILES["Image"]["name"]);
				if (!move_uploaded_file($_FILES["Image"]["tmp_name"], $uploadfile)) {
				echo "pb lors du telechargement";
				} 
				else {
					$projet->setImage($uploadfile);
				}
			}
			else {
				// traitement des erreurs
				echo "pas de fichier";
			}
			
		}

		$this->projManager->modifprojet($projet);
		$this->projManager->deletecontrprojet($projet);
		$idcontributeurs = $_POST['Contributeurs'];
		foreach ($idcontributeurs as $idcontributeur) {
			$this->utilisateurManager->addcontributeurs($idcontributeur, $idprojet);
		}
		$this->projManager->deletetagsprojet($projet);
		$idtags = $_POST['Tags'];
		foreach ($idtags as $idtag) {
			$this->tagManager->addcaracterise($idtag, $idprojet);
		}
		$this->projManager->deletecateprojet($projet);
		$idcategories = $_POST['Categories'];
		foreach ($idcategories as $idcategorie) {	
		$ok = $this->categorieManager->addappartient($idcategorie, $idprojet);
		}
		

		$message = $ok ?  "Projet modifié" : "probleme lors de la modification du projet";
		echo $this->twig->render('index.html.twig',array('message'=>$message,'acces'=> $_SESSION['acces'])); 
	}

	/**	
	* form de saisie des criteres
	* @param aucun
	* @return rien
	*/
	public function formRechercheProjet() {
		echo $this->twig->render('projet_recherche.html.twig',array('acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin'])); 
	}

	/**
	* recherche dans la BD des projets à partir des données du form précédent
	* @param aucun
	* @return rien
	*/
	public function rechercheProjet() {
		$projets = $this->projManager->search($_POST["titre"], $_POST["description"]);
		echo $this->twig->render('projet_liste.html.twig',array('projs'=>$projets,'acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin'])); 
	}

	/**
	* liste de mes projets
	* @param aucun
	* @return rien
	*/	
	public function listeMesProjets($idutilisateur) {
		$projets = $this->projManager->getProjetsMembre($idutilisateur);
		echo $this->twig->render('projet_liste.html.twig',array('projs'=>$projets,'acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin'])); 
	  }

}

class ItineraireController {
    
	private $itiManager; // instance du manager
	private $twig;
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db, $twig) {
		$this->itiManager = new ItineraireManager($db);
		$this->twig = $twig;
	}
        
	/**
	* liste de tous les itinéraires
	* @param aucun
	* @return rien
	*/
	public function listeProjets() {
		$projets = $this->projManager->getProjets();
		echo $this->twig->render('projets_liste.html.twig',array('projs'=>$projets,'acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin'])); 
	}

	/**
	* liste de mes itinéraires
	* @param aucun
	* @return rien
	*/
	public function listeMesProjets($utilisateur) {
		$itineraires = $this->itiManager->getProjetsMembre($idutilisateur);
		echo $this->twig->render('projets_liste.html.twig',array('itis'=>$itineraires,'acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin'])); 
	  }
	/**
	* formulaire ajout
	* @param aucun
	* @return rien
	*/
	public function formAjoutItineraire() {
		echo $this->twig->render('itineraire_ajout.html.twig',array('acces'=> $_SESSION['acces'],'idmembre'=>$_SESSION['idmembre'])); 
	}

	/**
	* ajout dans la BD d'un iti à partir du form
	* @param aucun
	* @return rien
	*/
	public function ajoutItineraire() {
		$iti = new Itineraire($_POST);
		$ok = $this->itiManager->add($iti);
		$message = $ok ? "Itinéraire ajouté" : "probleme lors de l'ajout";
		echo $this->twig->render('index.html.twig',array('message'=>$message,'acces'=> $_SESSION['acces'])); 

	}
	/**
	* form de choix de l'iti à supprimer
	* @param aucun
	* @return rien
	*/
	public function choixSuppItineraire($idMembre) {
		$itineraires = $this->itiManager->getListMembre($idMembre);
		echo $this->twig->render('itineraire_choix_suppression.html.twig',array('itis'=>$itineraires,'acces'=> $_SESSION['acces'])); 
	}
	/**
	* suppression dans la BD d'un iti à partir de l'id choisi dans le form précédent
	* @param aucun
	* @return rien
	*/
	public function suppItineraire() {
		$iti = new Itineraire($_POST);
		$ok = $this->itiManager->delete($iti);
		$message = $ok ?  "itineraire supprimé" : "probleme lors de la supression";
		echo $this->twig->render('index.html.twig',array('message'=>$message,'acces'=> $_SESSION['acces'])); 
	}
	/**
	* form de choix de l'iti à modifier
	* @param aucun
	* @return rien
	*/
	public function choixModItineraire($idMembre) {
		$itineraires = $this->itiManager->getListMembre($idMembre);
		echo $this->twig->render('itineraire_choix_modification.html.twig',array('itis'=>$itineraires,'acces'=> $_SESSION['acces'])); 
	}
	/**
	* form de saisi des nouvelles valeurs de l'iti à modifier
	* @param aucun
	* @return rien
	*/
	public function saisieModItineraire() {
		$iti =  $this->itiManager->get($_POST["iditi"]);
		echo $this->twig->render('itineraire_modification.html.twig',array('iti'=>$iti,'acces'=> $_SESSION['acces'])); 
	}

	/**
	* modification dans la BD d'un iti à partir des données du form précédent
	* @param aucun
	* @return rien
	*/
	public function modItineraire() {
		$iti =  new Itineraire($_POST);
		$ok = $this->itiManager->update($iti);
		$message = $ok ? "itineraire modifié" : $message = "probleme lors de la modification";
		echo $this->twig->render('index.html.twig',array('message'=>$message,'acces'=> $_SESSION['acces'])); 
	}

	/**
	* form de saisie des criteres
	* @param aucun
	* @return rien
	*/
	public function formRechercheProjet() {
		echo $this->twig->render('projet_recherche.html.twig',array('acces'=> $_SESSION['acces'])); 
	}

	/**
	* recherche dans la BD d'iti à partir des données du form précédent
	* @param aucun
	* @return rien
	*/
	public function rechercheItineraire() {
		$itineraires = $this->itiManager->search($_POST["lieudepart"], $_POST["lieuarrivee"], $_POST["datedepart"]);
		echo $this->twig->render('itineraire_liste.html.twig',array('itis'=>$itineraires,'acces'=> $_SESSION['acces'])); 
	}
}