<?php

/*
	***********************
	activer_tour.php
	***********************
	
	***********************
	ajout_perso.php
	***********************
		"SELECT passwd_forum,email FROM utilisateurs WHERE id = '$utilisateur_id' "
		"SELECT nom FROM persos WHERE nom = '$perso_nom'"
		"SELECT race_id AS id FROM races WHERE race_id = '$perso_race'"
		"INSERT INTO persos(
					id, 
					background, 
					description_affil, 
					utilisateur_id, 
					nb_suicide,
					race_id,
					superieur_id,
					grade_id,
					faction_id,
					nom,
					creation_date,
					date_tour,
					avatar_url,
					icone_id,
					galon_id,
					options,
					mdj,
					signature,
					sexe)
				VALUES (
					'',
					'$perso_bg',
					'', 
					$utilisateur_id, 
					'', 
					$race_id,
					'', 
					$grade_id,
					'', 
					'$perso_nom', 
					CURRENT_TIMESTAMP(), 
					'', 
					'', 
					'', 
					'',
					'0',
					'', 
					'',
					'".$sexe."')"
		"INSERT INTO `caracs_alter` (`perso_id`, 
					`alter_pa`, 
					`alter_mouv`, 
					`alter_def`, 
					`alter_att`, 
					`alter_recup_pv`, 
					`alter_force`, 
					`alter_perception`, 
					`nb_desaffil`, 
					`alter_niv_mag`) 
				VALUES ('$id_perso', 
					'', 
					'', 
					'', 
					'', 
					'', 
					'', 
					'', 
					'', 
					'')"
		"INSERT INTO `caracs_alter_mag` (`perso_id`, 
					`alter_pa`, 
					`alter_mouv`, 
					`alter_def`, 
					`alter_att`, 
					`alter_recup_pv`, 
					`alter_force`, 
					`alter_perception`, 
					`alter_niv_mag`) 
				VALUES ('$id_perso', 
					'', 
					'', 
					'', 
					'', 
					'', 
					'', 
					'', 
					'')"
		"INSERT INTO `caracs` (	`perso_id`, 
					`px`, 
					`pi`, 
					`pv`, 
					`recup_pv`, 
					`malus_def`, 
					`niv`, 
					`cercle`, 
					`mouv`, 
					`pa`, 
					`pa_dec`, 
					`des_attaque`,
					`maj_des`,
					`force`, 
					`perception`) 
				VALUES ('$id_perso', 
					'$px', 
					'$pi', 
					'$pv',
					'$recup_pv', 
					'$malus_def', 
					'$niv', 
					'', 
					'$mouv',  
					'$pa', 
					'', 
					'$des_attaque',
					'',
					'$force', 
					'$perception')"
									
		
		"INSERT INTO `blocks` (`unique_id`, `perso_id`, `block_id`, `column_id`, `order_id`) VALUES
					('', '$id_perso', 'block-1', 'column-1', 0),
					('', '$id_perso', 'block-3', 'column-1', 1),
					('', '$id_perso', 'block-2', 'column-1', 2),
					('', '$id_perso', 'block-4', 'column-1', 3),
					('', '$id_perso', 'block-5', 'column-2', 2),
					('', '$id_perso', 'block-6', 'column-2', 1),
					('', '$id_perso', 'block-7', 'column-2', 0)"		


	***********************
	apercu_perso.php
	***********************
	
	***********************
	controle_persos.php
	***********************
		"SELECT race_id FROM persos WHERE persos.utilisateur_id = '$utilisateur_id'"					
		"SELECT COUNT(nom) AS nombre_persos FROM persos WHERE utilisateur_id = '$utilisateur_id'"

		
	***********************		
	creation_perso.php
	***********************
		
	***********************
	editer_perso.php
	***********************
		"SELECT * FROM persos WHERE utilisateur_id = '$id_utilisateur' AND id = '$id'"

		
	***********************
	edition_background.php
	***********************
		"UPDATE persos SET background = '$background' WHERE utilisateur_id = '$utilisateur_id' AND id = '$id_perso'"		

	***********************
	edition_mdj.php
	***********************
		"UPDATE persos SET mdj = '$mdj' WHERE utilisateur_id = '$utilisateur_id' AND id = '$id_perso'"

	***********************
	edition_signature.php
	***********************
		"UPDATE persos SET signature = '$signature' WHERE utilisateur_id = '$utilisateur_id' AND id = '$id_perso'"

	***********************
	edition_signature_defaut.php
	***********************
		"SELECT options FROM persos WHERE id = '".$id_perso."'"

	***********************
	fonctions.php
	***********************
		"SELECT $requete FROM caracs WHERE perso_id=$perso_id"
		"SELECT race_id, grade_id, galon_id FROM persos WHERE id = $perso_id"
		"SELECT $carac FROM caracs_alter_mag WHERE perso_id=$perso_id"
		"UPDATE caracs SET `$carac`=$new_value WHERE perso_id=$perso_id"
		"UPDATE caracs_alter_mag SET $carac=$new_value WHERE perso_id=$perso_id"
		"SELECT * FROM caracs_alter_plan WHERE perso_id=$perso_id"
		"INSERT INTO `caracs_alter_plan` (`perso_id`, 
				`alter_pa`, 
				`alter_pv`, 
				`alter_mouv`, 
				`alter_def`, 
				`alter_att`, 
				`alter_recup_pv`, 
				`alter_force`, 
				`alter_perception`, 
				`alter_niv_mag`, 
				`alter_res_mag`, 
				`alter_effet`) 
			VALUES ('$perso_id', 
				'', 
				'', 
				'', 
				'', 
				'', 
				'', 
				'', 
				'', 
				'',
				'', 
				'')"
		"UPDATE `caracs_alter_plan` SET 	`alter_pa`='', 
				`alter_pv`='', 
				`alter_mouv`='', 
				`alter_def`='', 
				`alter_att`='', 
				`alter_recup_pv`='', 
				`alter_force`='', 
				`alter_perception`='', 
				`alter_niv_mag`='', 
				`alter_res_mag`='', 
				`alter_effet`=''
			WHERE perso_id=$perso_id"		
		"UPDATE caracs_alter_plan SET $carac=$new_value WHERE perso_id=$perso_id"
		"SELECT `galon_id` AS galon	FROM `persos` WHERE `id`=$perso_id"
		"SELECT `pi`,`niv_pv`, `niv_recup_pv`, `niv_mouv`, `niv_pa`, `niv_des` AS `niv_des`,`niv_force`,`niv_perception`,`niv` AS `magie`
			FROM `caracs` WHERE `perso_id` = $id_perso"
		"SELECT race_id AS race_id, grade_id AS grade_id, `galon_id` AS galon FROM `persos` WHERE `id`=$id_perso"	
		"UPDATE caracs SET `pi`=$new_pi WHERE perso_id = $id_perso"	
		"UPDATE caracs SET `niv_des`=$new_niv WHERE perso_id = $id_perso"
		"SELECT race_id, grade_id FROM persos WHERE id = $id_perso"
		"SELECT `pv`, `recup_pv`, `mouv`, `pa`, `des_attaque` AS `des`,`force`,`perception`,`niv` AS `magie`
			FROM `caracs` WHERE `perso_id` = $id_perso"
		"UPDATE caracs SET  pv = ".$new_caracs['pv'].",
				pa = ".$new_caracs['pa'].",
				mouv = ".$new_caracs['mouv'].",
				recup_pv = ".$new_caracs['recup_pv'].",
				niv = ".$new_caracs['magie'].",  
				des_attaque = ".$new_caracs['des'].",
				`force` = ".$new_caracs['force'].",
				perception = ".$new_caracs['perception']."
			WHERE perso_id = $id_perso"	
		"UPDATE persos SET superieur_id = 0 WHERE superieur_id = $perso_id"	
		"SELECT race_id, grade_id FROM persos WHERE id = $id_perso"
		"UPDATE persos SET grade_id = $new_grade WHERE id = $id_perso"
		"UPDATE persos SET race_id = $new_race WHERE id = $id_perso"
		"UPDATE persos SET galon_id = $galon WHERE id = $perso_id"
		"SELECT * FROM caracs_alter_mag WHERE perso_id='$perso_id'"
		"SELECT * FROM caracs_alter_plan WHERE perso_id='$perso_id'"
		"SELECT * FROM caracs_alter WHERE perso_id='$perso_id'"
		"SELECT * FROM caracs_alter_artefact INNER JOIN inventaire ON perso_id='$perso_id'
			WHERE caracs_alter_artefact.case_artefact_id=inventaire.case_artefact_id"
		"SELECT race_id, grade_id FROM persos WHERE id = '".$id_perso."'"
		"SELECT * FROM caracs	WHERE perso_id = '".$id_perso."'"
		"SELECT sum(A.poid) as poid FROM inventaire I 
			JOIN case_artefact A ON I.case_artefact_id = A.id 
			WHERE I.perso_id = '".$perso_id."'"
	
	***********************	
	index.php
	***********************

	***********************	
	liste_perso_edition.php
	***********************

	***********************	
	liste_persos.php
	***********************
		"SELECT nom, id FROM persos WHERE utilisateur_id = '$utilisateur_id' ORDER BY persos.id ASC"
		"SELECT persos.id AS id_perso, persos.race_id, persos.grade_id, persos.nom AS nom_perso, persos.date_tour AS tour, races.color AS couleur, caracs.pv, caracs.niv_pv, caracs.mouv, caracs.niv_mouv, caracs.pa, caracs.niv_pa, caracs.px   
			FROM persos 
			INNER JOIN races ON persos.race_id = races.race_id AND persos.grade_id = races.grade_id
			INNER JOIN caracs ON caracs.perso_id = persos.id
			WHERE utilisateur_id = '$utilisateur_id' ORDER BY persos.id ASC"
		"SELECT damier_persos.pos_x AS pos_x, damier_persos.pos_y AS pos_y, cartes.nom AS cartes 
			FROM damier_persos
			INNER JOIN cartes ON damier_persos.carte_id = cartes.id
			WHERE perso_id = '$id'"	
	
	***********************
	upload_image.php
	***********************
		(le code est en commentaire, les requêtes seront extraites en temps voulu)
		
*/

?>