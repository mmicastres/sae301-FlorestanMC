<?php

include "Modules/categorie.php";
include "Models/categorieManager.php";

/**
* Définition d'une classe permettant de gérer les ressources 
*   en relation avec la base de données	
*/
class CategorieController {
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
	* formulaire ajout d'une catégorie
	* @param aucun
	* @return rien
	*/
	public function formAjoutCategorie() {
		echo $this->twig->render('categorie_ajout.html.twig',array('acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin'])); 
	}
 
	/**
	 * ajout catégorie à partir du form
	 * @param aucun
	 * @return rien
	 */
	function categorieAjout() {
		$categorie = new Categorie($_POST);
		$ok = $this->categorieManager->addcategorie($categorie);
		$message = $ok ? "Catégorie ajoutée" : "probleme lors de l'ajout de la catégorie";
		echo $this->twig->render('pageadmin.html.twig',array('message'=>$message,'acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin'])); 
	}

    /**
	 * page gestion catégories 
	 * @param aucun
	 * @return rien
	 */
	function pageCategories() {
		$categories = $this->categorieManager->getListCategories();
		echo $this->twig->render('admincategories.html.twig',array('categories'=>$categories,'acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin'])); 
	}
      /**
      * suppression dans la BD d'une catégorie à partir de l'id choisi dans le form précédent
      * @param aucun
      * @return rien
     */
     public function suppCategorie() {
         $categorie = new Categorie($_POST);
		 $this->categorieManager->deleteappartient($categorie);
         $ok = $this->categorieManager->delete($categorie);
         $message = $ok ?  "Catégorie supprimée" : "probleme lors de la supression de la catégorie";
         echo $this->twig->render('pageadmin.html.twig',array('message'=>$message,'acces'=> $_SESSION['acces'],'admin'=> $_SESSION['admin']));
     }
 
     
	
}