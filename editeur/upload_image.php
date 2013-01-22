<?php
session_start(); 
$root_url = "./../..";
//-- Header --
include($root_url."/conf/master.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/

$ewo = bdd_connect('ewo');
if(isset($_POST['dest'])){
	$dest=$_POST['dest']."/";
	} else $dest ="";
//----------------------------
//  DEFINITION DES VARIABLES
//----------------------------

$target     = "./../../images/".$dest;  // Répertoire cible
$max_size   = 10000000000;     // Taille max en octets du fichier
$width_max  = 50;        // Largeur max de l'image en pixels
$height_max = 50;        // Hauteur max de l'image en pixels
$prefix     = $dest;  // Chemin du dépôt en fonction de la race.

//-- Test de l'existence du dossier devant contenir les icones en fonction de la race, le cas échéant, on le créé
@mkDir($target);

//---------------------------------------------
//  DEFINITION DES VARIABLES LIEES AU FICHIER
//---------------------------------------------

$nom_file   = $_FILES['fichier']['name'];
$taille     = $_FILES['fichier']['size'];
$tmp        = $_FILES['fichier']['tmp_name'];

//----------------------
//  SCRIPT D'UPLOAD
//----------------------


// On vérifie si le champ est rempli
if(!empty($_FILES['fichier']['name'])) {
    // On vérifie l'extension du fichier
    if((substr($nom_file, -3) == 'jpg') OR (substr($nom_file, -3) == 'png') OR (substr($nom_file, -3) == 'gif')) {
        // On récupère les dimensions du fichier
        $infos_img = getimagesize($_FILES['fichier']['tmp_name']);
        
        // On vérifie les dimensions et taille de l'image
        if(($infos_img[0] <= $width_max) && ($infos_img[1] <= $height_max) && ($_FILES['fichier']['size'] <= $max_size)) {
            // Si c'est OK, on teste l'upload
		
            $cible = $prefix.$_FILES['fichier']['name'];
            if(move_uploaded_file($_FILES['fichier']['tmp_name'],$target.$_FILES['fichier']['name'])) {
              // Si upload OK
           		
  						//-- Mise à jour de la base avec le lien de l'avatar
							echo "<script language='javascript' type='text/javascript' >document.location='./'</script>";exit;
               
            } else {
                // Sinon on affiche une erreur système
                echo "<h2>Probleme</h2><p align='center'>Problème lors de l\'upload !</b><br /><br />', ".$_FILES['fichier']['error'].", '</p><p align='center'>[<a href='".$_SERVER['HTTP_REFERER']."'>Retour</a>]</p>";
                //include("../template/footer.php");
            }
        } else {
            // Sinon on affiche une erreur pour les dimensions et taille de l'image
            echo "<h2>Probleme</h2><p align='center'>Problème dans les dimensions ou taille de l\'image !</p><p align='center'>[<a href='".$_SERVER['HTTP_REFERER']."'>Retour</a>]</p>";
            //include("../template/footer.php");
        }
    } else {
        // Sinon on affiche une erreur pour l'extension
        echo "<h2>Probleme</h2><p align='center'>Votre image n\'en est pas une !</p><p align='center'>[<a href='".$_SERVER['HTTP_REFERER']."'>Retour</a>]</p>";
        //include("../template/footer.php");
    }
} else {
    // Sinon on affiche une erreur pour le champ vide
    echo "<h2>Probleme</h2><p align='center'>Le champ du formulaire est vide !</p><p align='center'>[<a href='".$_SERVER['HTTP_REFERER']."'>Retour</a>]</p>";    
    //include("../template/footer.php");
}

?>
