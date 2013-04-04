<?php

function controleCreationPerso($utilisateur_id) {



	if(ControleAcces('anim', 0)) {
		return array(
			'camp' => null,
			'peutCreer' => true,
			'creationT3' => true,
			'creationT4' => true,
			'texte' => 'Mode Admin',
			'admin' => true);
	}

	$sql = "SELECT camps.nom as nom, races.camp_id, races.type FROM persos, races, camps WHERE (persos.race_id = races.race_id AND races.grade_id = -2) AND (races.camp_id = camps.id) AND persos.utilisateur_id = $utilisateur_id";
	$resultat = mysql_query ($sql) or die (mysql_error());

	$camp = null;
	$t3 = $t4 = 0;

	while ($perso = mysql_fetch_array($resultat)) {
		// On recommence par rechercher le camp du joueur en regardant le camp de son premier perso
		// Si 2 (=paria) on continu, paria n'est pas un camp.
		// Idem 5 (Autre) et 6 (légende)
		if (!$camp && $perso['camp_id'] != 2 && $perso['camp_id'] != 5 && $perso['camp_id'] != 6) {
			$camp = strtolower($perso['nom']);
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

	if($t4 > 0) {
		$creationT4 = false;
	}

	if(($t4 > 0 && $t3 < 2) || ($t4 == 0 && $t3 < 3)) {
		$creationT3 = true;
		
		if($t4 == 0) {
			$creationT4 = true;
		}
	} else {
		$creationT3 = false;
		$creationT4 = false;
	}


	// Texte à afficher sur la page de création de personnages
	// et savoir si on peut créer des perso encore.
	if (!$creationT3 && !$creationT4) {
		$texte = "Vous ne pouvez plus créer de personnage.";
		$peutCreer = false;
	} else {
		$restantT3 = 3 - $t3;
		$alternative = 2 - $t3;
		if ($creationT3 && !$creationT4) {
					
			$texte = 'Vous pouvez encore créer '.$restantT3.' case(s) indépendante(s)';
		} elseif(!$creationT3 && $creationT4) {
			$texte = 'Vous pouvez encore créer 4 T4';            
			} else {
			$alternative = $restantT3 - 1;
			$texte = 'Vous pouvez encore créer '.$alternative.' case(s) indépendante(s) et un groupe de 4 cases solidaires,<br> ou '.$restantT3.' case(s) indépendante(s).';
		}
		$peutCreer = true;
	}

	// Savoir si on laisse le choix à l'utilisateur du camp, et si non, lequel on lui affecte.
	/*if ($camp == 3) {
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
	}*/

	return array(
		//'ange' => $ange,
		//'demon' => $demon,
		//'humain' => $humain,
		'camp' => $camp,
		'peutCreer' => $peutCreer,
		'creationT3' => $creationT3,
		'creationT4' => $creationT4,
		'texte' => $texte,
		'admin' => false);
}
?>
