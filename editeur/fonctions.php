<?php
/*---------------------------------------
//--			Fonction de l'éditeur
---------------------------------------*/

//-- Affichfer les différentes images d'icones a choisir
function select_icone($repcou,$type){
// Liste des images du Répertoire
$img = "../images/".$repcou;
$dir = opendir($img);
	// Scan du répertoire
	$i = 1;
	while($scan=readdir($dir)) {
		// Fichier
		if(is_file($img."/".$scan)) {
		// Verification de l'extension d'image
		$ext = strtolower(substr($scan, strrpos($scan, '.') + 1));
			if (($ext=="jpg") || ($ext=="jpeg") || ($ext=="png") || ($ext=="gif")) {
				// Lien vers l'image
				if ($type == 'simple'){
					$lien[$i] = $scan;
					$i++;
				}elseif ($type == 'complexe'){
					$image = explode('_', $scan);
					$lien[$i] = $image[0]."_".$image[1];
					$i++;
				}
			}
		}
	}
closedir($dir);

if ($type == 'complexe'){
	$lien = array_unique($lien);
}

$z = $i/15;
$tailley = ceil($z);
$e = 1;
	echo "<table>";
	for ($a=1;$a<=$tailley;$a++){
		echo "<tr>"; 
		for($b=1;$b<=15;$b++){
			if (!empty($lien[$e])){
				if ($type == 'simple'){
					echo "<td><img src='../images/".$repcou.$lien[$e]."' /><input type='radio' name='image' value='".$repcou.$lien[$e]."' /></td>";
					$e++;
				}elseif ($type == 'complexe'){
					$images = explode('_',$lien[$e]);
					$taille2 = explode('x',$images[1]);
					$taillex =$taille2[0];
					$tailley = $taille2[1];
					//echo "<p>".$images[0]."</p>";
						$i = 1;
						echo "<td><table>";
						for($y=1;$y<=$tailley;$y++){
							echo "<tr  height='33'>";
							for($x=1;$x<=$taillex;$x++){
								echo "<td>";
									echo "<img src='../images/".$repcou.$lien[$e]."_".$i.".png' />";
									$i++;
								echo "</td>";
							}
							echo "</tr>";	
						}
						echo "</table>";
						echo "<input type='radio' name='image' value='".$repcou.$lien[$e]."' /></td>";
					$e++;
				}
			}
		}		
		echo "</tr>";
	}
	echo "</table>";
}

//-- Affichage des differents plan
function liste_plan(){
	echo "<select name='carte_id'>";
	$plans = "SELECT id, nom, description FROM cartes";
	$resultat = mysql_query ($plans) or die (mysql_error());
	while ($plan = mysql_fetch_array ($resultat)){
		echo "<option value='".$plan['id']."'>".$plan['nom']."</option>";
	}
	echo '</select>';
}

//-- Affichage des differents spawn
function liste_spawn(){
	echo "<select name='spawn_id'>";
	$spawns = "SELECT * FROM damier_spawn";																									
	$resultat = mysql_query ($spawns) or die (mysql_error());
	while ($spawn = mysql_fetch_array ($resultat)){
		echo "<option value='".$spawn['id']."'> x: ".$spawn['pos_x']." y: ".$spawn['pos_y']." | ".$spawn['nom']."</option>";
	}
	echo '</select>';
}

//-- Affichage des catégories
function liste_categorie($categorie){
	echo "<select name='categorie_id'>";
	$cats = "SELECT id, nom, description FROM categorie_".$categorie."";																									
	$resultat = mysql_query ($cats) or die (mysql_error());
	while ($cat = mysql_fetch_array ($resultat)){
		echo "<option value='".$cat['id']."'>".$cat['nom']."</option>";
	}
	echo '</select>';
}

//-- Affichage nom catégories en fonction de l'id
function name_categorie($categorie,$id){
	$namecats = "SELECT nom FROM categorie_".$categorie." WHERE id=".$id."";																									
	$resultat = mysql_query ($namecats) or die (mysql_error());
	$namecat = mysql_fetch_array ($resultat);
	echo $namecat['nom'];
}

//-- Récupération du nom du spawn
function get_spawn ($id){
	$spawns = "SELECT nom, pos_x, pos_y FROM damier_spawn WHERE id=".$id."";																									
	$resultat = mysql_query ($spawns) or die (mysql_error());
	$spawn = mysql_fetch_array ($resultat);
	return "x: ".$spawn['pos_x']." y: ".$spawn['pos_y']." | ".$spawn['nom'];
}

//-- Récupération du nom d'un objet complexe
function name_objet_complexe ($id){
	$objcs = "SELECT nom FROM case_objet_complexe WHERE id=".$id."";
	$resultat = mysql_query ($objcs) or die (mysql_error());
	$objc = mysql_fetch_array ($resultat);
	return $objc['nom'];
}

//-- Affichage des objets complexe
function liste_objet_complexe(){
	echo "<select name='objet_id'>";
	$listobjs = "SELECT id, nom, description FROM case_objet_complexe";																									
	$resultat = mysql_query ($listobjs) or die (mysql_error());
	while ($listobj = mysql_fetch_array ($resultat)){
		echo "<option value='".$listobj['id']."'>".$listobj['nom']." - ".$listobj['description']."</option>";
	}
	echo '</select>';
}

//-- Déplacement et changement de plan aux coordonées données
if(isset($_POST['Vision']) AND isset($_POST['coordX']) AND isset($_POST['coordY']) AND isset($_POST['plan'])){
	$vision_post 			= $_POST['Vision'];
	$pos_x_perso_post = $_POST['coordX'];
	$pos_y_perso_post = $_POST['coordY'];
	$carte_pos_post		= $_POST['plan'];
	
	$_SESSION['Vision']	= $_POST['Vision'];
	$_SESSION['coordX'] = $_POST['coordX'];
	$_SESSION['coordY'] = $_POST['coordY'];
	$_SESSION['plan']		= $_POST['plan'];
}else{
	$vision_post 			= 5;
	$pos_x_perso_post = 0;
	$pos_y_perso_post = 0;
	$carte_pos_post 	= 1;
}

//-- Deplacement rapide +5
if(isset($_POST['ModcoordY']) AND isset($_POST['ModcoordX'])){
	$modcoordx = $_POST['ModcoordX'];
	$modcoordy = $_POST['ModcoordY'];
	if(isset($_SESSION['coordX']) AND isset($_SESSION['coordY'])){
	$_SESSION['coordX'] = $_SESSION['coordX'] + $modcoordx;
	$_SESSION['coordY'] = $_SESSION['coordY'] + $modcoordy;
	}else {
		$_SESSION['coordX'] = $modcoordx;
		$_SESSION['coordY'] = $modcoordy;
		}
}
?>
