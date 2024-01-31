<?php

include "Modules/commentaire.php";
include "Models/commentaireManager.php";

/**
* Définition d'une classe permettant de gérer les projets 
*   en relation avec la base de données	
*/

class commentaireController {

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

    public function ajoutCommentaire() {

		$commentaire    = new Commentaire($_POST);
		$idutilisateur = $_SESSION['idutilisateur'];
		$ok = $commentaire = $this->commentaireManager->add($commentaire, $idutilisateur);
		$projets       = $this->projManager->voirProjet($_POST["Id_Projet"]);
		$contributeurs = $this->utilisateurManager->contrProjet($_POST["Id_Projet"]);
		$tags          = $this->tagManager->tagsProjet($_POST["Id_Projet"]);
		$categories    = $this->categorieManager->categoriesProjet($_POST["Id_Projet"]);
		$commentaires  = $this->commentaireManager->commentairesProjet($_POST["Id_Projet"]);
        $message = $ok ? "Projet ajouté" : "probleme lors de l'ajout du projet";
		// render la view twig avec les différentes valeurs provenant des requetes contenues dans des variables
		echo $this->twig->render('projet_page.html.twig',array('message'=>$message, 'projs'=>$projets,'contrs'=>$contributeurs,'tags'=>$tags,'categories'=>$categories, 'commentaires'=>$commentaires,'acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin']));

		// var_dump($categories) Si besoin de vérifier ce qu'il y a dans la variable; 
		
	}

	


}
