<?php

/*
	***********************
	action_grade.php
	***********************
		"SELECT * FROM faction_grades WHERE `faction_grades`.`grade_id` = '$grade_id' AND `faction_grades`.`faction_id` = '$faction_id'"
		"SELECT * FROM faction_grades WHERE `faction_grades`.`grade_id` = '$grade_id' AND `faction_grades`.`faction_id` = '$faction_id'"
		"UPDATE `ewo`.`faction_grades` SET `droits` = '$droits_', `nom`='$nom' WHERE `faction_grades`.`grade_id` = '$grade_id' AND `faction_grades`.`faction_id` = '$faction_id' LIMIT 1 ;"
		"SELECT MAX(grade_id) FROM faction_grades WHERE faction_id='$faction_id'"
		"INSERT INTO faction_grades(id,grade_id,faction_id,nom,description,droits) 
			VALUES ('','$id_grade','$faction_id','$nom','','$droits')"
	
	***********************
	action_mem.php
	***********************	
		"SELECT droits FROM faction_grades WHERE grade_id='$grade_id' AND faction_id='$faction_id'"
		"SELECT utilisateur_id, id, faction_id FROM persos WHERE (id REGEXP '$perso_id' OR nom REGEXP '$perso_id')"
		"SELECT faction_id FROM wait_faction WHERE perso_id='$perso_id' AND faction_id='$faction_id'"
		"INSERT INTO `ewo`.`wait_faction` (`id` ,`utilisateur_id` ,`perso_id` ,`faction_id` ,`demandeur`)
			VALUES (NULL , '$user_id', '$perso_id', '$faction_id', '');"
	
	***********************
	controle_membre.php
	***********************
		"SELECT faction_membres.faction_grade_id AS faction_grade, faction_grades.droits AS droits
			FROM faction_membres
			INNER JOIN faction_grades ON faction_grades.faction_id = ".$faction_id." AND faction_grades.grade_id = faction_membres.faction_grade_id
			WHERE faction_membres.perso_id = ".$persos_id

	***********************
	editer_faction.php
	***********************
		"SELECT id AS faction_id FROM factions WHERE nom='$nom_faction' OR id='$nom_faction'"
		"SELECT id AS faction_id FROM factions WHERE nom='$faction_id' OR id='$faction_id'"
		"SELECT * FROM factions WHERE id = $faction_id"
		"SELECT race_id FROM persos WHERE utilisateur_id = $utilisateur_id"
		"SELECT perso_id FROM wait_faction WHERE faction_id = $faction_id AND demandeur=1"
		"SELECT faction_id FROM wait_faction WHERE perso_id = $demandeur_id"
		"SELECT COUNT(nom) AS nombre FROM persos WHERE faction_id ='$faction_id' AND nom REGEXP '^".$i."'"
		"SELECT * FROM persos WHERE faction_id ='$faction_id' AND nom LIKE '".$alpha."%' ORDER BY nom ASC"
		"SELECT faction_grade_id AS id FROM faction_membres WHERE faction_id = '$faction_id' AND perso_id='$membre_id'"
		"SELECT pos_x, pos_y, carte_id FROM damier_persos WHERE perso_id = '$membre_id'"
		"SELECT nom FROM cartes WHERE id = '$carte'"
		"SELECT nom FROM faction_grades WHERE faction_id = '$faction_id' AND grade_id='$membre_grade_id'"
		"SELECT grade_id,nom FROM faction_grades WHERE faction_id = '$faction_id'"
		"SELECT * FROM faction_grades WHERE faction_id ='$faction_id' ORDER BY nom ASC"
		"SELECT * FROM persos INNER JOIN faction_membres ON faction_membres.perso_id=persos.id
			WHERE persos.faction_id ='$faction_id' AND faction_membres.faction_grade_id=".$grade['grade_id']." ORDER BY persos.nom ASC"
		"SELECT faction_grade_id AS id FROM faction_membres WHERE faction_id = '$faction_id' AND perso_id='$membre_id'"
		"SELECT pos_x, pos_y, carte_id FROM damier_persos WHERE perso_id = '$membre_id'"
		"SELECT nom FROM cartes WHERE id = '$carte'"
		"SELECT nom FROM faction_grades WHERE faction_id = '$faction_id' AND grade_id='$membre_grade_id'"
		"SELECT grade_id,nom FROM faction_grades WHERE faction_id = '$faction_id'"
		"SELECT perso_id FROM wait_faction WHERE faction_id = $faction_id AND demandeur=1"
		"SELECT persos.nom AS nom, caracs.px AS xp FROM persos INNER JOIN caracs ON caracs.perso_id = persos.id WHERE id = '$demandeur_id'"
	
	***********************
	edition_faction.php
	***********************
		"UPDATE `ewo`.`factions` SET `description` = '$description' WHERE `factions`.`id` = '$faction_id' LIMIT 1 ;"
		"UPDATE `ewo`.`factions` SET `site_url` = '$url' WHERE `factions`.`id` = '$faction_id' LIMIT 1 ;"
		"UPDATE `ewo`.`factions` SET `nom` = '$nom' WHERE `factions`.`id` = '$faction_id' LIMIT 1 ;"
		"UPDATE `ewo`.`factions` SET `type` = '$type', `type_nom`='$type_nom' WHERE `factions`.`id` = '$faction_id' LIMIT 1 ;"
	
	***********************
	ennemis.php
	***********************


	***********************
	fonctions.php
	***********************
		"DELETE FROM `ewo`.`faction_membres` WHERE `faction_membres`.`perso_id` = '$mem_id' LIMIT 1 ;"
		"UPDATE `ewo`.`persos` SET `faction_id` = '0' WHERE `persos`.`id` = '$mem_id' LIMIT 1 ;"
		"SELECT perso_id FROM faction_membres WHERE faction_id=$faction_id AND faction_grade_id=$grade_id"
		"UPDATE `ewo`.`faction_membres` SET `faction_grade_id` = '4' WHERE `faction_membres`.`perso_id` = '$perso_id' LIMIT 1 ;"
		"DELETE FROM `ewo`.`faction_grades` WHERE (`faction_grades`.`grade_id` = '$grade_id' AND `faction_grades`.`faction_id` = '$faction_id') LIMIT 1 ;"
		"SELECT perso_id FROM faction_membres WHERE faction_id=$faction_id AND faction_grade_id=$grade_id"
		"SELECT perso_id FROM faction_membres WHERE faction_id=$faction_id"
		"SELECT id FROM persos WHERE faction_id=$faction_id"
		"DELETE FROM `ewo`.`faction_grades` WHERE `faction_grades`.`faction_id` = $faction_id"
		"DELETE FROM `ewo`.`factions` WHERE `factions`.`id` = $faction_id"
		"UPDATE `ewo`.`factions` SET `type` = '3', `type_nom`='Faction de Traitre' WHERE `factions`.`id` = '$faction_id' LIMIT 1 ;"
		"UPDATE `ewo`.`factions` SET `race` = $race_id WHERE `factions`.`id` = '$faction_id' LIMIT 1 ;"
		"INSERT INTO `factions`(`id`,`nom`,`race`,`description`,`type`,`type_nom`,`creation_date`,`site_url`,`logo_url`) 
			VALUES ('','$nom','$race','$description_faction','$type','$type_nom',CURRENT_TIMESTAMP(),'','')"
		"SELECT MAX(grade_id) FROM faction_grades WHERE faction_id=$faction_id"
		"INSERT INTO faction_grades(id,grade_id,faction_id,nom,description,droits) 
			VALUES ('','$id_grade','$faction_id','$nom','','$droits')"
		"SELECT grade_id, id FROM persos WHERE (nom REGEXP '$nom' OR id REGEXP '$nom') AND faction_id=0"
		"UPDATE `ewo`.`persos` SET `faction_id` = '$faction_id' WHERE `persos`.`id` = '$id_perso' LIMIT 1 ;"
		"INSERT INTO `ewo`.`faction_membres` (`id` ,`perso_id` ,`faction_id` ,`faction_grade_id`)
			VALUES (NULL , '$id_perso', '$faction_id', '1');"
		"SELECT grade_id, id FROM persos WHERE (nom REGEXP '$nom' OR id REGEXP '$nom') AND faction_id=0"
		"UPDATE `ewo`.`persos` SET `faction_id` = '$faction_id' WHERE `persos`.`id` = '$id_perso' LIMIT 1 ;"
		"INSERT INTO `ewo`.`faction_membres` (`id` ,`perso_id` ,`faction_id` ,`faction_grade_id`)
			VALUES (NULL , '$id_perso', '$faction_id', '4');"
		"UPDATE `ewo`.`faction_membres` SET `faction_grade_id` = '$grade_id' WHERE `faction_membres`.`perso_id` = '$mem_id' LIMIT 1 ;"
		"DELETE FROM `ewo`.`wait_faction` WHERE `wait_faction`.`faction_id` = $faction_id AND `wait_faction`.`perso_id` = $perso_id LIMIT 1"
		"SELECT type FROM `factions` WHERE id=$faction_id LIMIT 0, 30 "
		"SELECT race FROM `factions` WHERE id=$faction_id LIMIT 0, 30 "
		"SELECT faction_id FROM persos WHERE utilisateur_id=$utilisateur_id AND faction_id=$faction_id"
		"SELECT faction_grade_id FROM faction_membres WHERE perso_id=$perso_id"
		"SELECT damier_persos.perso_id AS perso_id , damier_persos.pos_y AS pos_y FROM damier_persos
			INNER JOIN persos ON persos.id=damier_persos.perso_id INNER JOIN races ON races.race_id=$faction_race AND races.grade_id=0
			INNER JOIN camps ON camps.id=races.camp_id LEFT JOIN factions ON factions.id=persos.faction_id
			WHERE (persos.race_id!=$faction_race OR (persos.race_id=$faction_race AND factions.type=3)) AND damier_persos.carte_id=camps.carte_id"
		"SELECT factions.id AS id FROM factions LEFT JOIN persos ON persos.id = $tueur_id WHERE factions.race=persos.race_id AND factions.type=1"
		"SELECT morgue.mat_victime, morgue.id_perso, morgue.date, morgue.nom_victime FROM morgue LEFT JOIN persos p1 ON p1.id = morgue.id_perso
			LEFT JOIN persos p2 ON p2.id = morgue.mat_victime WHERE ((p1.faction_id = $faction_id) AND (morgue.date>='$depuis') 
			AND p1.race_id=p2.race_id) GROUP BY morgue.mat_victime"
		"SELECT factions.id AS id FROM factions LEFT JOIN persos ON persos.id = $perso_id WHERE factions.race=persos.race_id AND factions.type=3 ORDER BY factions.id ASC"
	
	***********************
	impossible.php
	***********************

	
	***********************
	index.php
	***********************


	***********************
	liste_factions.php
	***********************
		"SELECT nom FROM factions WHERE nom = '$nom'"
		"SELECT races.nom AS nom FROM races WHERE races.race_id = $anim_race_id AND races.grade_id = 0"
		"SELECT COUNT(nom) AS nombre FROM factions WHERE race REGEXP '$anim_race_id|2' AND nom REGEXP '^".$i."'"
		"SELECT * FROM factions WHERE race REGEXP '$anim_race_id|2' AND nom LIKE '".$alpha."%' ORDER BY nom ASC"
	
	***********************
	liste_persos.php
	***********************
		"SELECT utilisateur_id, faction_id FROM persos WHERE id='$perso_id'"
		"SELECT faction_id FROM wait_faction WHERE perso_id='$perso_id' AND faction_id='$faction_id'"
		"INSERT INTO `ewo`.`wait_faction` (`id` ,`utilisateur_id` ,`perso_id` ,`faction_id` ,`demandeur`)
			VALUES (NULL , '$user_id', '$perso_id', '$faction_id', '1');"
		"SELECT persos.race_id AS race_id, races.nom AS nom FROM persos INNER JOIN races
			ON races.race_id = persos.race_id AND races.grade_id = 0 WHERE persos.utilisateur_id = $utilisateur_id"
		"SELECT COUNT(nom) AS nombre FROM factions WHERE race REGEXP '$perso_race_id|2' AND nom REGEXP '^".$i."'"
		"SELECT * FROM factions WHERE race REGEXP '$perso_race_id|2' AND nom LIKE '".$alpha."%' ORDER BY nom ASC"
		"SELECT persos.id AS id_perso,persos.nom AS nom_perso,races.color AS couleur,persos.superieur_id AS superieur_id, 
			persos.faction_id AS faction_id,caracs.px AS px,races.race_id AS race,races.grade_id AS grade
			FROM persos INNER JOIN races ON persos.race_id = races.race_id AND persos.grade_id = races.grade_id
			INNER JOIN caracs ON caracs.perso_id = persos.id WHERE utilisateur_id = $utilisateur_id"
		"SELECT faction_id FROM wait_faction WHERE perso_id = $perso_id AND demandeur=0"
		"SELECT persos.faction_id AS faction_id FROM persos WHERE id = '$perso_id'"
		"SELECT faction_id FROM wait_faction WHERE perso_id = $perso_id"
		"SELECT factions.nom AS nom_fact FROM factions WHERE id = '$faction_id'"
		"SELECT faction_membres.faction_grade_id AS faction_grade, faction_grades.droits AS droits
			FROM faction_membres INNER JOIN faction_grades ON faction_grades.faction_id = ".$faction_id." 
			AND faction_grades.grade_id = faction_membres.faction_grade_id WHERE faction_membres.perso_id = ".$id
		"SELECT COUNT(wait_faction.id) AS nombre FROM wait_faction INNER JOIN persos ON persos.id=$id
			WHERE wait_faction.faction_id = persos.faction_id AND demandeur = '1'"
		"SELECT id,nom FROM factions WHERE race REGEXP '$race|2'"
		"SELECT faction_id FROM wait_faction WHERE perso_id = $id AND demandeur=0"
		"SELECT factions.nom AS nom FROM factions WHERE id = $faction_id"
	
	***********************
	test.php
	***********************


	***********************
	traitres.php
	***********************
		"SELECT perso_id FROM damier_persos WHERE perso_id='$id_victime'"
		"SELECT faction_id FROM persos WHERE id='$id_victime' AND faction_id=0"
	
*/

?>	