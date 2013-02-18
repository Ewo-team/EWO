<?php
//-- Header --
require_once __DIR__ . '/../../conf/master.php';

include_once SERVER_ROOT . '/template/header_new.php';
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/

//----------------------------
//  DEFINITION DES VARIABLES 
//----------------------------

$target     = SERVER_ROOT.'/images/avatar/';  // Repertoire cible
$max_size   = 10000000000;     // Taille max en octets du fichier
$width_max  = 140;        // Largeur max de l'image en pixels
$height_max = 140;        // Hauteur max de l'image en pixels

//---------------------------------------------
//  DEFINITION DES VARIABLES LIEES AU FICHIER
//---------------------------------------------

// $nom_file     = $_FILES['fichier']['name'];
// $taille       = $_FILES['fichier']['size'];
// $tmp_name     = $_FILES['fichier']['tmp_name'];
// $infosfichier = pathinfo($_FILES['fichier']['name']);

// if (isset ($infosfichier['extension'])){
// $extension_upload = $infosfichier['extension'];
// }

// $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
	
//----------------------
//  SCRIPT D'UPLOAD
//----------------------

// On vérifie si le champ est rempli
// if(!empty($_FILES['fichier']['name']) AND (isset($_POST['id_perso']))) {
    // On vérifie l'extension du fichier
    // if (in_array($extension_upload, $extensions_autorisees)){
        // On récupère les dimensions du fichier
        // $infos_img = getimagesize($tmp_name);
        
        // On vérifie les dimensions et taille de l'image
        // if(($infos_img[0] <= $width_max) && ($infos_img[1] <= $height_max) && ($taille <= $max_size)) {
            // Si c'est OK, on teste l'upload
            // $date = date('hisjmy');
						// $utilisateur_id = $_SESSION['utilisateur']['id'];
           	// $id_perso = $_POST['id_perso'];
           	// $prefix = $date.$utilisateur_id.$id_perso;
            // $cible = $prefix.$_FILES['fichier']['name'];
						// $reussite = move_uploaded_file($tmp_name,$target.$cible);
            // if($reussite != FALSE) {
              // Si upload OK

           		//-- Suppression de l'ancien avatar s'il existe
           		// $avatarsql = "SELECT avatar_url FROM persos WHERE id = $id_perso";																							
							// $resultatavat = mysql_query ($avatarsql) or die (mysql_error());
							// $oldavatar = mysql_fetch_array ($resultatavat);
							
							// print_r($oldavatar);
							
							// if ($oldavatar['avatar_url'] != ''){
           		// $delavatar = $oldavatar['avatar_url'];
           		// unlink($root_url."/images/avatar/".$delavatar);
           		// }
           		
           		// $sql = mysql_query("UPDATE persos SET avatar_url = '$cible' WHERE utilisateur_id = '$utilisateur_id' AND id = '$id_perso'");       
           		
              // echo "<h2>Upload Réussi !</h2></b><br /><br /></p><p align='center'>[<a href='".$_SESSION['temps']['page']."'>Retour</a>]</p>";
               echo "<h2>Upload Désactivé !</h2></b><br /><br /></p><p align='center'>[<a href='./liste_persos.php'>Retour</a>]</p>";
//include($root_url."/template/footer_new.php");
              //echo "<script language='javascript' type='text/javascript' >document.location='".$_SESSION['temps']['page']."'</script>";exit;	
              //header("location:".$_SESSION['temps']['page']."");exit;
               
            // } else {
                // Sinon on affiche une erreur système
                // echo "<h2>Probleme</h2><p align='center'>Problème lors de l'upload !</b><br /><br />', ".$_FILES['fichier']['error'].", '</p><p align='center'>[<a href='".$_SESSION['temps']['page']."'>Retour</a>]</p>";
								// include($root_url."/template/footer_new.php");
            // }
        // } else {
            // Sinon on affiche une erreur pour les dimensions et taille de l'image
            // echo "<h2>Probleme</h2><p align='center'>Problème dans les dimensions ou la taille de l'image !</p><p align='center'>[<a href='".$_SESSION['temps']['page']."'>Retour</a>]</p>";
						// include($root_url."/template/footer_new.php");
        // }
    // } else {
        // Sinon on affiche une erreur pour l'extension
        // echo "<h2>Probleme</h2><p align='center'>Votre image n'en est pas une !</p><p align='center'>[<a href='".$_SESSION['temps']['page']."'>Retour</a>]</p>";
				// include($root_url."/template/footer_new.php");
    // }
// } else {
    // Sinon on affiche une erreur pour le champ vide
    // echo "<h2>Probleme</h2><p align='center'>Le champ du formulaire est vide !</p><p align='center'>[<a href='".$_SESSION['temps']['page']."'>Retour</a>]</p>";    
//-- Footer --
include(SERVER_ROOT."/template/footer_new.php");
//}
//------------
?>
