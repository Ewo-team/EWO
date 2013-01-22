<?php
$sql = "SELECT races.camp_id, races.type FROM persos, races WHERE (persos.race_id = races.race_id AND races.grade_id = -2) AND persos.utilisateur_id = $utilisateur_id";
$resultat = mysql_query ($sql) or die (mysql_error());

$camp = null;
$t3 = $t4 = 0;

while ($perso = mysql_fetch_array($resultat)) {
	// On recommence par rechercher le camp du joueur en regardant le camp de son premier perso
	// Si 2 (=paria) on continu, paria n'est pas un camp.
	// Idem 5 (Autre) et 6 (légende)
	if (!$camp && $perso['camp_id'] != 2 && $perso['camp_id'] != 5 && $perso['camp_id'] != 6) {
		$camp = $perso['camp_id'];
	}

	if ($perso['type'] == 4)
		$t4++;
	else
		$t3++;
}

/* Maintenant on agit suivant si on est devant un joueur d'humains ou d'ailés.
Camp humain : 1 gros, 1 à 2 groupes de 4 petits.
Camp ailés : 1 à 2 gros, 1 groupe de 4 petits OU 3 gros.
1 Humain
3 Ange
4 Demon
*/
if ($camp == 1) {
	$restantT3 = 1 - $t3;
	$restantT4 = 8 - $t4;
	$groupeT4 = true;
} else {
	// On regarde le type de gameplay choisi (= si l'utilisateur a fait un t4)
	if ($t4 >= 1) {
		$restantT3 = 2 - $t3;
		$restantT4 = 4 - $t4;
		$groupeT4 = true;
	} else {
		$restantT3 = 3 - $t3;
		if ($restantT3 >= 1)
			$restantT4 = 4;
		else
			$restantT4 = 0;
		$groupeT4 = false;
	}
}

// Texte à afficher sur la page de création de personnages
// et savoir si on peut créer des perso encore.
if ($restantT3 == 0 && $restantT4 == 0) {
	$texte = "Vous ne pouvez plus créer de personnage.";
	$peutCreer = false;
} else {
	if ($groupeT4) {
		$texte = 'Vous pouvez encore créer '.$restantT3.' T3 et '.$restantT4.' T4.';
	} else {
		$alternative = $restantT3 - 1;
		$texte = 'Vous pouvez encore créer '.$alternative.' T3 et '.$restantT4.' T4 ou '.$restantT3.' T3.';
	}
	$peutCreer = true;
}

// Savoir si on laisse le choix à l'utilisateur du camp, et si non, lequel on lui affecte.
if ($camp == 3) {
	$ange = 'checked="checked"';
	$demon = 'disabled';
	$humain = 'disabled';
} elseif ($camp == 4) {
	$ange = 'disabled';
	$demon = 'checked="checked"';
	$humain = 'disabled';
} elseif ($camp == 1) {
	$ange = 'disabled';
	$demon = 'disabled';
	$humain = 'checked="checked"';
} else {
	$humain = '';
	$demon = '';
	$ange = '';
}
?>
