<?php
/**
 * Carte de la terre
 *
 * @author Anarion <anarion@ewo.fr>
 * @version 1.0
 * @package carte
 */
session_start();


$root_url = "..";

include("functions.php");
include("../conf/master.php");
include("../persos/fonctions.php");
include("../jeu/fonctions.php");
// Paramètres de connexion à la base de données
$ewo_bdd = bdd_connect('ewo');

$taille = 4;
if(isset($_GET['taille'])) {
	$taille = $_GET['taille'];
}

ControleAcces('utilisateur',1);

headersvg("Carte d'Althian", 150*$taille, 150*$taille);
// Recupération des données de la carte

$sql = "SELECT * FROM damier_persos, persos, races 
		WHERE damier_persos.carte_id = 1 
			AND damier_persos.perso_id = persos.id 
			AND races.race_id = persos.race_id AND races.grade_id = persos.grade_id 
			AND(pos_x BETWEEN -75 AND 75) 
			AND (pos_y BETWEEN -75 AND 75)";
$result = mysql_query($sql);

$humain = $paria = $ange = $demon = array();
$persos = array();

while($ligne = mysql_fetch_array($result)) {
	switch($ligne['camp_id']) {
		case 1: 
			$couleur = 'humain'; 
			break;
		case 2: 
			$couleur = 'paria'; 
			break;
		case 3: 
			$couleur = 'ange'; 
			break;
		case 4: 
			$couleur = 'demon'; 
			break;
		default: 
			$couleur = 'noir'; 
			break;
	}
	
	//$ligne['couleur'] = $couleur;
	$persos[$couleur][] = $ligne;
}

$sql = "SELECT * FROM damier_bouclier
		WHERE (pos_x BETWEEN -75 AND 75) 
			AND (pos_y BETWEEN -75 AND 75)";
$result = mysql_query($sql);

// Boucliers
echo '<g class="bouclier">';
while($ligne = mysql_fetch_array($result)) {
	$x = ($ligne['pos_x'] + 75)*$taille;
	$y = (150-($ligne['pos_y'] + 75))*$taille;
	bouclier($x,$y, $taille*$ligne['type_id'], $ligne['nom']);
}
echo '</g>';

// Persos
foreach($persos as $couleur => $race) {
	echo '<g class="'.$couleur.'">';
	foreach($race as $ligne) {
		
		$x = (($ligne['pos_x'] + 75))*$taille;
		$y = (150-($ligne['pos_y'] + 75))*$taille;
		
		carre($x, $y, $taille, 'g'.$ligne['grade_id']);	
	}
	echo '</g>';
}

// Grille
AxeHorizontale((150-(45 + 75))*$taille, 150*$taille, 'Y=45');
AxeHorizontale((150-(15 + 75))*$taille, 150*$taille, 'Y=15');
AxeHorizontale((150-(-15 + 75))*$taille, 150*$taille, 'Y=-15');
AxeHorizontale((150-(-45 + 75))*$taille, 150*$taille, 'Y=-45');


AxeVerticale((45 + 75)*$taille, 150*$taille, 'X=45');
AxeVerticale((15 + 75)*$taille, 150*$taille, 'X=15');
AxeVerticale((-15 + 75)*$taille, 150*$taille, 'X=-15');
AxeVerticale((-45 + 75)*$taille, 150*$taille, 'X=-45');



footersvg();
mysql_close($ewo_bdd);
