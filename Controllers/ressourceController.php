<?php

include "Modules/ressource.php";
include "Models/ressourceManager.php";

/**
* Définition d'une classe permettant de gérer les ressources 
*   en relation avec la base de données	
*/
class RessourceController {
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
		$this->projManager        = new ProjetManager($db);
		$this->twig = $twig;
	}
    
    /**
	 * page gestion ressources/SAE 
	 * @param aucun
	 * @return rien
	 */
	function pageRessources() {
		$ressources = $this->ressourceManager->getListRessources();
		echo $this->twig->render('adminressources.html.twig',array('ressources'=>$ressources,'acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin'])); 
	}

	/**
	* formulaire ajout ressource
	* @param aucun
	* @return rien
	*/
	public function formAjoutRessource() {
		echo $this->twig->render('ressource_ajout.html.twig',array('acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin'])); 
	}

	/**
	 * ajout ressource à partir du form
	 * @param aucun
	 * @return rien
	 */
	function ressourceAjout() {
		$ressource = new Ressource($_POST);
		$ok = $this->ressourceManager->addressource($ressource);
		$message = $ok ? "Ressource ajoutée" : "probleme lors de l'ajout de la ressource";
		echo $this->twig->render('index.html.twig',array('message'=>$message,'acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin'])); 
	}

      /**
      * suppression dans la BD d'un utilisateur à partir de l'id choisi dans le form précédent
      * @param aucun
      * @return rien
     */
     public function suppRessource() {
         $ressource = new Ressource($_POST);
         $ok = $this->ressourceManager->delete($ressource);
         $message = $ok ?  "Ressource supprimée" : "probleme lors de la supression de la ressource";
         echo $this->twig->render('pageadmin.html.twig',array('message'=>$message,'acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin']));
     }
 
	
}