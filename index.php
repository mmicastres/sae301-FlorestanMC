<?php

// utilisation des sessions
session_start();

include "moteurtemplate.php";
include "connect.php";

include "Controllers/projetController.php";
include "Controllers/utilisateurController.php";
include "Controllers/ressourceController.php";
include "Controllers/categorieController.php";
include "Controllers/commentaireController.php";

$projController        = new ProjetController($bdd,$twig);
$utilController        = new UtilisateurController($bdd,$twig);
$ressourceController   = new RessourceController($bdd,$twig);
$categorieController   = new CategorieController($bdd,$twig);
$commentaireController = new CommentaireController($bdd,$twig);

// texte du message
$message = "";

// ============================== connexion / deconnexion - sessions ==================

// si la variable de session n'existe pas, on la crée
if (!isset($_SESSION['acces'])) {
   $_SESSION['acces']="non";
}

if (!isset($_SESSION['admin'])) {
   $_SESSION['admin']="non";
}

// click sur le bouton connexion
if (isset($_POST["connexion"]))  {  
  $message = $utilController->utilisateurConnexion($_POST);  
}

// click sur le bouton inscription
if (isset($_POST["inscription"]))  {  
  $message = $utilController->utilisateurInscription($_POST);  
}

// click sur le bouton ajout pour une catégorie
if (isset($_POST["categorieajout"]))  {  
  $message = $categorieController->categorieAjout($_POST);  
}

// click sur le bouton ajout pour une ressource
if (isset($_POST["ressourceajout"]))  {  
  $message = $ressourceController->ressourceAjout($_POST);  
}



// deconnexion : click sur le bouton deconnexion
if (isset($_GET["action"]) && $_GET['action']=="logout") { 
    $message = $utilController->utilisateurDeconnexion(); 
 } 

// formulaire de connexion
if (isset($_GET["action"])  && $_GET["action"]=="login") {
  $utilController->utilisateurFormulaire(); 
}

// formulaire d'inscription
if (isset($_GET["action"]) && $_GET["action"]=="inscription") {
  $utilController->inscriptionFormulaire();
}



// ============================== page d'accueil ==================

// cas par défaut = page d'accueil
if (!isset($_GET["action"]) && empty($_POST)) {
  echo $twig->render('index.html.twig',array('acces'=> $_SESSION['acces'])); 
}


// ============================== gestion des projets ==================

// liste des projets sous forme de cards
if (isset($_GET["action"]) && $_GET["action"]=="projets") {
  $projController->listeProjets();
}

// liste de mes projets sous forme de cards
if (isset($_GET["action"]) && $_GET["action"]=="mesprojets") { 
  $projController->listeMesProjets($_SESSION['idutilisateur']);
}

// Page Projet d'un projet spécifique
if (isset($_POST["voirprojet"])) {
  $projController->pageProjet();
}

// Espace Utilisateur  
if (isset($_GET["action"]) && $_GET["action"]=="profil") {
  $utilController->pageProfil($_SESSION['idutilisateur']);
}


// Ouvre un form avec les informations du profil pour les modifier
if (isset($_GET["action"]) && $_GET["action"]=="modifprofil") {
  $utilController->formModifProfil($_SESSION['idutilisateur']);
}

// click sur le bouton modifier le projet
if (isset($_POST["valider_modifprofil"]))  {  
  $message = $utilController->modifProfil($_POST);  
}

// ============================== ADMINISTRATION ==================

// Ouverture de la page Administration après un click sur le bouton correspondant
if (isset($_GET["action"]) && $_GET["action"]=="admin") {
   $utilController->pageAdmin();
}

// Ouvre le pannel de gestion des Ressources/SAE
if (isset($_GET["action"]) && $_GET["action"]=="modifressources") {
  $ressourceController->pageRessources();
}

// Ouvre le pannel de gestion des Catégories
if (isset($_GET["action"]) && $_GET["action"]=="modifcategories") {
  $categorieController->pageCategories();
}

// Ouvre le pannel de gestion des Utilisateurs
if (isset($_GET["action"]) && $_GET["action"]=="modifutilisateurs") {
  $utilController->pageUtilisateurs();
}

// Ouvre un form avec les informations du projet pour les modifier
if (isset($_POST["modifprojet"])) {
  $projController->formModifProjet();
}

// click sur le bouton ajouter le projet
if (isset($_POST["valider_modifprojet"]))  {  
  $message = $projController->modifProjet($_POST);  
}

// supression d'un utilisateur dans la bdd
// --> au clic sur le bouton "valider_supp" du form précédent
if (isset($_POST["valider_supp"])) { 
  $utilController->suppUtilisateur();
}

// supression d'une ressource dans la bdd
// --> au clic sur le bouton "valider_suppressource" du form précédent
if (isset($_POST["valider_suppressource"])) { 
  $ressourceController->suppRessource();
}

// supression d'une ressource dans la bdd
// --> au clic sur le bouton "valider_suppcategorie" du form précédent
if (isset($_POST["valider_suppcategorie"])) { 
  $categorieController->suppCategorie();
}

// supression d'un projet dans la bdd
// --> au clic sur le bouton "supprojet" du form précédent
if (isset($_POST["supprojet"])) { 
  $projController->suppProjet();
}

// formulaire ajout d'un utilisateur : saisie des caractéristiques à ajouter dans la BD
if (isset($_GET["action"]) && $_GET["action"]=="ajout") {
  $utilController->formAjoutUtilisateur();
 }

// formulaire ajout d'un projet : saisie des caractéristiques à ajouter dans la BD
if (isset($_GET["action"]) && $_GET["action"]=="ajoutprojet") {
  $projController->formAjoutProjet();
 }
 
// click sur le bouton ajouter le projet
if (isset($_POST["valider_addprojet"]))  {  
  $message = $projController->ajoutProjet($_POST);  
}

// click sur le bouton ajouter le commentaire
if (isset($_POST["valider_commentaire"]))  {  
  $message = $commentaireController->ajoutCommentaire($_POST);  
}

// formulaire ajout d'une ressource : saisie des caractéristiques à ajouter dans la BD
if (isset($_GET["action"]) && $_GET["action"]=="ajoutressource") {
  $ressourceController->formAjoutRessource();
 }

 // formulaire ajout d'une catégorie : saisie des caractéristiques à ajouter dans la BD
if (isset($_GET["action"]) && $_GET["action"]=="ajoutcategorie") {
  $categorieController->formAjoutCategorie();
 }

// ajout de l'itineraire dans la base
// --> au clic sur le bouton "valider_ajout" du form précédent
if (isset($_POST["valider_ajout"])) {
  $itiController->ajoutItineraire();
}


// suppression d'un itineraire : choix de l'itineraire
//  https://.../index/php?action=suppr
if (isset($_GET["action"]) && $_GET["action"]=="suppr") { 
  $itiController->choixSuppItineraire($_SESSION['idmembre']);
}

// // supression d'un itineraire dans la base
// // --> au clic sur le bouton "valider_supp" du form précédent
// if (isset($_POST["valider_supp"])) { 
//   $itiController->suppItineraire();
// }

// modification d'un itineraire : choix de l'itineraire
//  https://.../index/php?action=modif
if (isset($_GET["action"]) && $_GET["action"]=="modif") { 
  $itiController->choixModItineraire($_SESSION['idmembre']);
}

// modification d'un utilisateur : saisie des nouvelles valeurs
// --> au clic sur le bouton "saisie modif" du form précédent
if (isset($_POST["saisie_modif"])) {   
  $utilController->saisieModUtilisateur();
}

//modification d'un itineraire : enregistrement dans la bd
// --> au clic sur le bouton "valider_modif" du form précédent
if (isset($_POST["valider_modif"])) {
  $itiController->modItineraire();
}


// recherche d'itineraire : saisie des critres de recherche dans un formulaire
//  https://.../index/php?action=recherc
if (isset($_GET["action"]) && $_GET["action"]=="recher") {
  $projController->formRechercheProjet();
}

// recherche des itineraires : construction de la requete SQL en fonction des critères 
// de recherche et affichage du résultat dans un tableau HTML 
// --> au clic sur le bouton "valider_recher" du form précédent
if (isset($_POST["valider_recher"])) { 
  $projController->rechercheProjet();
}

?>
