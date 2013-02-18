<?php

    use legions\LegionDroits    as LegionDroits;
    use legions\Legion          as Legion;

$balling = array();

$id_courant = array_search($id_per, $_SESSION['persos']['id']);
$grade = $_SESSION['persos']['grade'][$id_courant];
$galon = $_SESSION['persos']['galon'][$id_courant];
$camp = $_SESSION['persos']['camp'][$id_courant];
$planperso = $_SESSION['persos']['carte_respawn'][$id_courant];

$balplan = 0;

// Ajout de la balling-list de faction
if($_SESSION['persos']['faction']['id'][$id_courant] != 0) {

    $legion_droits = LegionDroits::droitsFromPersoId($id_courant);


	if($legion_droits->canDo(LegionDroits::UTILISER_BAL_LIST)) {
            $legion = Legion::getLegionFromId($id_courant);
            $balling[] = array('faction',"Bal liste de ".$legion->getNom());
	}

	// r�cup�rer le type de faction
	$type = $_SESSION['persos']['faction']['type'][$id_courant];

	if($type == 2 && ($grade >= 3 || $galon >= 2)) {
		$balplan = 1;
	}
}

// Ajout de la balling-list du plan
if($grade == 5 || ($grade == 4 && $galon >= 2) || $balplan==1) {
	if(isset($_SESSION['persos']['carte'][$id_courant]) && $_SESSION['persos']['carte'][$id_courant] == $planperso && ($camp == 3 || $camp == 4)) {
		$balling[] = array('plan',"Tous les occupants du plan");
	}
}

$lignes = $conn->SelectInfoListeNumerique(null, $camp, $id_per, true);

// Ajout des balling-list de la BDD
foreach($lignes as $ligne) {
	$id = $ligne['id'];

	if($ligne['type'] == 'public' || $ligne['type'] == 'prive' || $ligne['enregistre'] == '1') {
		$balling[] = array($id,$ligne['libelle']);
	} else {
		if($ligne['grade'] == 5) {
			$distance = 50;
		} else {
			$distance = 25;
		}

		// On test si le perso n'est pas mort (=est sur une carte) pour eviter une erreur.
		if (isset($_SESSION['persos']['carte'][$id_courant])) {
			$distance_x = abs($_SESSION['persos']['pos_x'][$id_courant] - $ligne['pos_x']);
			$distance_y = abs($_SESSION['persos']['pos_y'][$id_courant] - $ligne['pos_y']);

			if(max($distance_x,$distance_y) <= $distance && $_SESSION['persos']['carte'][$id_courant] == $ligne['carte']) {
				// On est dans l'aire de d�tection
				$balling[] = array($id,$ligne['libelle']);
			}
		}
	}
}

// Ajout des derni�res balling-list
// Groupe de liste 3

$balling[] = "admin";

if($droits[1] == 1) {
	$balling[] = array('mass_joueur',"Tous les joueurs");
}

if($droits[2] == 1) {
	$balling[] = array('mass_camp',"Tous les personnages du camp");
}

$balling[] = array('admin',"Administrateurs");
$balling[] = array('anim',"Animateurs");
$balling[] = array('at',"Anti-triche");

?>
