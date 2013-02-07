<?php

/*
	***********************
	classement.php
	***********************
		"SELECT COUNT(id) AS nb	FROM persos "
			"WHERE persos.race_id=$race AND persos.race_id<5"
			"WHERE persos.race_id=3 OR persos.race_id=4 AND persos.race_id<5"
			"WHERE persos.race_id<5"
		
		"SELECT persos.nom AS nom, persos.id AS id, persos.race_id AS race, persos.grade_id AS grade, caracs.px AS px
			FROM persos
			INNER JOIN caracs ON caracs.perso_id=persos.id
			INNER JOIN races ON races.race_id = persos.race_id AND races.grade_id=0 "
			"WHERE persos.race_id=$race AND persos.race_id<5"		
			"WHERE persos.race_id=3 OR persos.race_id=4 AND persos.race_id<5"		
			"WHERE persos.race_id=$race AND persos.race_id<5"
			" ORDER BY persos.grade_id $croissant, caracs.px $croissant LIMIT $lim,$nb_el"
			" ORDER BY caracs.px $croissant LIMIT $lim,$nb_el"
		
		"SELECT MAX(niv_pv) AS max_pv, AVG(niv_pv) AS avg_pv, MAX(niv_pa) AS max_pa, AVG(niv_pa) AS avg_pa, MAX(niv_mouv) AS max_mouv, AVG(niv_mouv) AS avg_mouv, MAX(niv_des) AS max_des, AVG(niv_des) AS avg_des,
				MAX(niv_force) AS max_force, AVG(niv_force) AS avg_force, MAX(niv_perception) AS max_perception, AVG(niv_perception) AS avg_perception, MAX(niv) AS max_magie, AVG(niv) AS avg_magie
			FROM caracs
			INNER JOIN persos ON persos.id=caracs.perso_id
			WHERE persos.race_id=$inc AND persos.grade_id<=$grade"	
		"SELECT color FROM races WHERE races.race_id=$inc AND races.grade_id=0"
			
			
	
	***********************
	comptes.php
	***********************
	
	***********************
	fonctions.php
	***********************
		"SELECT * FROM morgue INNER JOIN persos ON persos.id = morgue.id_perso
			WHERE ($where_perso	(morgue.date>='$JourProf') AND (morgue.date<'$Jour') $where_race $where_grade)
			ORDER By morgue.date $asc, morgue.id ASC $limit"	
	
		"SELECT COUNT(morgue.id) FROM morgue LEFT JOIN persos p1 ON p1.id = morgue.id_perso
			LEFT JOIN persos p2 ON p2.id = morgue.mat_victime
          	WHERE ($where_perso	(morgue.date>='$JourProf') AND (morgue.date<'$Jour') 
				$where_killer_race 
				$where_killed_race
				$where_killer_grade
				$where_killed_grade)
			ORDER By morgue.date $asc, morgue.id ASC"	
			
		"SELECT COUNT(morgue.id) AS nb FROM morgue
		JOIN persos ON persos.id=morgue.id_perso
		WHERE ((morgue.date>='$JourProf') AND (morgue.date<'$Jour') $where_race $where_grade)"
	
	***********************
	necro.php
	***********************
	
	
	***********************
	record.php
	***********************
	
	
	***********************
	sidebar.php
	***********************
	
	
*/

?>	