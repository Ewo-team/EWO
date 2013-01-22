<?php

/*
	***********************
	index.php
	***********************

	
	***********************
	menu_liste.php
	***********************
		"SELECT COUNT(wait_faction.id) AS nombre FROM wait_faction
			WHERE utilisateur_id = '$utilisateur_id' AND demandeur = '0'"
		"SELECT COUNT(wait_faction.id) AS nombre FROM wait_faction INNER JOIN persos ON persos.id=$perso_id
			WHERE wait_faction.faction_id = persos.faction_id AND demandeur = '1'"
		"SELECT COUNT(bals.id) AS nombre FROM bals INNER JOIN persos
			ON bals.perso_src_id = persos.id  WHERE perso_dest_id = '$id_perso' AND flag_lu = '0'"

*/

?>	