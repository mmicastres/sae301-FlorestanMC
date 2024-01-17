<?php

include "Modules/itineraires.php";
include "Modules/contributeur.php";
include "Modules/tags.php";
include "Modules/categories.php";
include "Modules/commentaires.php";
include "Models/itinerairesManager.php";
include "Models/contributeurManager.php";
include "Models/tagsManager.php";
include "Models/categoriesManager.php";
include "Models/commentairesManager.php";
/**
* Définition d'une classe permettant de gérer les projets 
*   en relation avec la base de données	
*/

class ProjetController {

	private $projManager; // instance du manager
	private $utilisateursManager; // instance du manager
	private $tagsManager; // instance du manager
	private $categoriesManager; // instance du manager
	private $commentairesManager; // instance du manager
	private $twig;
	

	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db, $twig) {

		$this->projManager         = new ProjetManager($db);
		$this->utilisateursManager = new UtilisateursManager($db);
		$this->tagsManager         = new TagsManager($db);
		$this->categoriesManager   = new CategoriesManager($db);
		$this->commentairesManager = new CommentairesManager($db);
		$this->twig = $twig;
		
	}

	/**
	* liste de tous les projets
	* @param aucun
	* @return rien
	*/
	public function listeProjets() {
		$projets = $this->projManager->getProjets();
		echo $this->twig->render('projet_liste.html.twig',array('projs'=>$projets,'acces'=> $_SESSION['acces'])); 
	}


	public function pageProjet() {
		
		$projets       = $this->projManager->voirProjet($_POST["Id_Projet"]);
		$contributeurs = $this->utilisateursManager->contrProjet($_POST["Id_Projet"]);
		$tags          = $this->tagsManager->tagsProjet($_POST["Id_Projet"]);
		$categories    = $this->categoriesManager->categoriesProjet($_POST["Id_Projet"]);
		$commentaires  = $this->commentairesManager->commentairesProjet($_POST["Id_Projet"]);

		// render la view twig avec les différentes valeurs provenant des requetes contenues dans des variables
		echo $this->twig->render('projet_page.html.twig',array('projs'=>$projets,'contrs'=>$contributeurs,'tags'=>$tags,'categories'=>$categories,'acces'=> $_SESSION['acces']));

		// var_dump($categories) Si besoin de vérifier ce qu'il y a dans la variable; 
		
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
	public function rechercheProjet() {
		$projets = $this->projManager->search($_POST["titre"], $_POST["description"]);
		echo $this->twig->render('projet_liste.html.twig',array('projs'=>$projets,'acces'=> $_SESSION['acces'])); 
	}

	/**
	* liste de mes itinéraires
	* @param aucun
	* @return rien
	*/
	public function listeMesProjets($idutilisateur) {
		$projets = $this->projManager->getProjetsMembre($idutilisateur);
		echo $this->twig->render('projet_liste.html.twig',array('projs'=>$projets,'acces'=> $_SESSION['acces'])); 
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
		$projets = $this->itiManager->getProjets();
		echo $this->twig->render('projets_liste.html.twig',array('projs'=>$projets,'acces'=> $_SESSION['acces'])); 
	}

	/**
	* liste de mes itinéraires
	* @param aucun
	* @return rien
	*/
	public function listeMesProjets($idutilisateur) {
		$itineraires = $this->itiManager->getProjetsMembre($idutilisateur);
		echo $this->twig->render('projets_liste.html.twig',array('itis'=>$itineraires,'acces'=> $_SESSION['acces'])); 
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
	public function formRechercheItineraire() {
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