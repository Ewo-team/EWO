<?php

/*

	***********************
	inventaire/drop.php
	***********************
		"SELECT * FROM inventaire WHERE id='".$_GET['id_inventaire']."' AND perso_id='".$cenPos['perso_id']."'"
		"DELETE FROM inventaire WHERE id = '".$_GET['id_inventaire']."'"
		"SELECT * FROM case_artefact WHERE id='".$inventaire['case_artefact_id']."'"
		"INSERT INTO damier_artefact (id, icone_artefact_id, pos_x, pos_y, pv, carte_id) VALUE ('', '".$inventaire['case_artefact_id']."', '".$position['pos_x']."', '".$position['pos_y']."','".$inventaire['pv']."' ,'".$cenPos['carte_id']."')"
		"SELECT * FROM cartes WHERE id='$carte_pos'"
	
	***********************
	inventaire/inventaire.php
	***********************	
		"SELECT I.statut AS stat, A.nom as nom, A.image as img, A.poid as poid, A.cout as cout, A.description AS description , I.statut as statut, A.id as id, I.pv as pv, I.id as id_inventaire 
			FROM inventaire I JOIN case_artefact A ON I.case_artefact_id = A.id WHERE I.perso_id = '".$perso_id."' ORDER BY A.nom ASC"

	***********************
	action_ajax.php
	***********************
		
		
	***********************
	actions.php
	***********************
		"SELECT * FROM damier_persos WHERE perso_id='$perso_id'"
		"SELECT * FROM action WHERE (cercle_id=$cercle OR cercle_id=0) AND id='$action_id'"
		"SELECT superieur_id FROM persos WHERE id=$perso_id"
		"SELECT * FROM damier_$type WHERE $type_id='$cible_id'"
		"SELECT * FROM damier_$type WHERE $type_id='$cible_id'"
		"SELECT * FROM damier_$type WHERE $type_id='$cible_id'"
		"SELECT * FROM damier_$type WHERE $type_id='$cible2_id'"
		"SELECT * FROM damier_porte WHERE id='$cible2_id'"
		"SELECT * FROM damier_$type WHERE $type_id='$cible_id'"
		"SELECT superieur_id AS sup_id FROM persos WHERE id=$perso_id"
		"SELECT nom AS sup_nom FROM persos WHERE id=$sup_id"
		"SELECT * FROM cartes WHERE id='$carte_pos'"
		"SELECT `damier_$type`.`$type_id` AS id $rchchrace FROM `damier_$type` $innerjoin WHERE carte_id='$carte_pos' AND $rchch_x AND $rchch_y"
		"SELECT `damier_objet_simple`.`id` AS id, case_objet_simple.`nom` AS nom FROM damier_objet_simple 
			INNER JOIN `case_objet_simple` ON `case_objet_simple`.`id`=`damier_objet_simple`.`case_objet_simple_id`
			WHERE carte_id='$carte_pos' AND $rchch_x AND $rchch_y"
		"SELECT `damier_objet_complexe`.`id` AS id, case_objet_complexe.`nom` AS nom FROM damier_objet_complexe
			INNER JOIN `case_objet_complexe` ON `case_objet_complexe`.`id`=`damier_objet_complexe`.`case_objet_complexe_id`
			WHERE carte_id='$carte_pos' AND $rchch_x AND $rchch_y"
		"SELECT `damier_porte`.`id` AS id, `nom_image` AS type, `damier_porte`.`nom` AS nom FROM damier_porte 
			WHERE carte_id='$carte_pos' AND $rchch_x AND $rchch_y"
		"SELECT `damier_bouclier`.`id` AS id, `damier_bouclier`.`nom_image` AS type , `damier_bouclier`.`nom` AS nom FROM damier_bouclier WHERE carte_id='$carte_pos' AND $rchch_x AND $rchch_y"
		"SELECT * FROM persos WHERE id='".$cible['id'][$inci]."'"
		"SELECT * FROM persos WHERE id='".$cible2_id."'"
		"SELECT * FROM effet WHERE id='$effet_id'"
		"SELECT * FROM effet WHERE id='$effet_id'"
	
	***********************
	bdd_mdj.php
	***********************
		"UPDATE persos SET mdj = '$mdj' WHERE utilisateur_id = '$utilisateur_id' AND id = '$perso_id'"
	
	***********************
	boite_outils.php
	***********************

	
	***********************
	carac.php
	***********************

	
	***********************
	damier.php
	***********************
		"SELECT * FROM `damier_spawn` WHERE nom REGEXP 'humain.*primaire.*'"
		
	***********************
	deplacement.php
	***********************
		"SELECT * FROM damier_persos WHERE perso_id='$perso_id'"
	
	***********************
	fonctions.php
	***********************
		"SELECT * FROM cartes WHERE id=$plan"
		"SELECT type FROM factions WHERE id='$perso_faction'"
		"SELECT camp_id FROM races WHERE race_id='$perso_race' AND grade_id='0'"
		"SELECT * FROM persos WHERE id='$cible_id'"
		"SELECT camp_id FROM races WHERE race_id='".$cible_info['race_id']."' AND grade_id='0'"
	
	***********************
	get_blocks.php
	***********************
		"SELECT * FROM blocks WHERE perso_id = '".$_SESSION['persos']['current_id']."' ORDER BY order_id ASC"
	
	***********************
	index.php
	***********************
		"SELECT * FROM damier_persos WHERE perso_id='$perso_id'"
		"SELECT * FROM damier_persos WHERE perso_id='".$_SESSION['persos']['id'][$i]."'"
		"SELECT * FROM `damier_spawn` WHERE nom REGEXP 'humain.*primaire.*'"
		"INSERT INTO damier_persos (carte_id, pos_x, pos_y, perso_id) VALUE ('".$new_pos['plan']."','".$new_pos['pos_x']."','".$new_pos['pos_y']."','".$perso_id."')"
		"SELECT * FROM `damier_spawn` WHERE nom REGEXP '.*primaire.*' AND carte_id=5"
		"SELECT * FROM cartes WHERE id='$carte_id'"
		"INSERT INTO `ewo`.`damier_persos` (`carte_id`, `pos_x`, `pos_y`, `perso_id`) VALUE ('$carte_pos','$pos_x_perso','$pos_y_perso','$perso_id')"
		"SELECT * FROM `damier_spawn` WHERE nom REGEXP 'angélique.*primaire.*'"
		"SELECT * FROM cartes WHERE id='$carte_id'"
		"INSERT INTO `ewo`.`damier_persos` (`carte_id`, `pos_x`, `pos_y`, `perso_id`) VALUE ('$carte_pos','$pos_x_perso','$pos_y_perso','$perso_id')"
		"SELECT * FROM `damier_spawn` WHERE nom REGEXP 'démoniaque.*primaire.*'"
		"SELECT * FROM cartes WHERE id='$carte_id'"
		"INSERT INTO `ewo`.`damier_persos` (`carte_id`, `pos_x`, `pos_y`, `perso_id`) VALUE ('$carte_pos','$pos_x_perso','$pos_y_perso','$perso_id')"
		"INSERT INTO damier_persos (carte_id, pos_x, pos_y, perso_id) VALUE (".$new_pos['plan'].",".$new_pos['pos_x'].",".$new_pos['pos_y'].",".$perso_id.")"
		"SELECT * FROM cartes WHERE id='$carte_pos'"
	
	***********************
	info_carac.php
	***********************

		
	***********************
	infos.php
	***********************
	
	
	***********************
	infos_action.php
	***********************

	
	***********************
	infos_damier.php
	***********************

	
	***********************
	minicarte.php
	***********************

	
	***********************
	panel_actions.php
	***********************

		
	***********************
	panel_mouvement.php
	***********************
	
	
	***********************
	save.php
	***********************

	
	***********************
	save_visible.php
	***********************

	
	***********************
	stat_time.php
	***********************


	
*/

?>	