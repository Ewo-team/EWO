<?php

/*
	***********************
	formatter/attaque.php
	***********************

	
	***********************
	formatter/basic.php
	***********************


	***********************
	formatter/entraine.php
	***********************

	
	***********************
	formatter/formatter.php
	***********************


	***********************
	formatter/mouv.php
	***********************

	
	***********************
	formatter/sort.php
	***********************
		"SELECT nom FROM action WHERE id = $id;"

	***********************
	formatter/sprint.php
	***********************

	
	***********************
	formatter/suicide.php
	***********************


	***********************
	event.php
	***********************
		"INSERT ewo.evenements (id, id_perso_source, type_source, id_perso_desti, type_desti, id_event, date_ev, type_ev, public_data, private_data, result) 
			VALUES (NULL, $this->src, ".(($this->actor_type[0] != NULL)?$this->actor_type[0]:'NULL').", $this->dst, ".(($this->actor_type[1] != NULL)?$this->actor_type[1]:'NULL')." , $id ,'$this->date', '$this->type', ".$this->infos->sql($bdd).", $this->state);"
	
	***********************
	eventFormatter.php
	***********************
		"SELECT ev.*, COUNT(*) as sub  FROM evenements AS ev WHERE ((id_perso_source = $mat AND type_source = 1) 
			OR (id_perso_desti = $mat AND type_desti = 1)) GROUP BY ev.id_perso_source, ev.type_ev, ev.date_ev 
			ORDER BY ev.id DESC LIMIT $from,".self::$LIMIT_EVENT.";"
		"SELECT cs.image FROM case_$type AS cs WHERE cs.id = $mat;"
		"SELECT nom_image FROM damier_$type WHERE id = $mat"
		"SELECT nom FROM persos WHERE id = $id"
		"SELECT nom FROM case_$type WHERE id = $id"
		"SELECT nom, nom_image FROM damier_$type WHERE id = $id"
		"SELECT COUNT(*) AS nb FROM evenements WHERE (id_perso_source = $idP OR id_perso_desti = $idP) AND id_event IS NULL"
		"SELECT ev.*, COUNT(ev.id_event) as sub  FROM evenements AS ev WHERE type_ev != 'mouv' 
			GROUP BY ev.id_perso_source, ev.type_ev, ev.date_ev ORDER BY ev.id DESC LIMIT 0,".self::$LIMIT_EVENT.";"
		"SELECT * FROM evenements WHERE id_event = $key ORDER BY id DESC;"

	***********************
	eventManager.php
	***********************
		"SELECT nom FROM cartes WHERE id = $plan"
		"INSERT INTO `ewo`.`morgue` (`id`, `id_perso`, `date`, `type`, `mat_victime`, `nom_victime`, `plan_victime`) 
			VALUES (NULL, $perso, '".$this->date."', $type, $mat_vic, '$nom_vic', '$plan_nom[0]')"
	
	***********************
	fonctions.php
	***********************
		"SELECT evenement.id, evenement.evenement_type_id, evenement.perso_id , evenement.date, evenement.champs, persos.nom
          	FROM evenement INNER JOIN persos ON persos.id = evenement.perso_id 
          	WHERE ".$where_perso." (evenement.date>=(NOW()-".$nbJours."))    
			AND (evenement.evenement_type_id IN(".$list_idTypeEvent.")) ORDER By evenement.date desc, evenement.id ASC"
		"SELECT evenement.id, evenement.evenement_type_id, evenement.perso_id , evenement.date, evenement.champs, persos.nom
          	FROM evenement INNER JOIN persos ON persos.id = evenement.perso_id 
          	WHERE ($where_perso (evenement.evenement_type_id =$list_idTypeEvent) 
			AND (evenement.date>=($JourProf)) AND (evenement.date<($Jour)) $where_race $where_grade) 
			ORDER By evenement.date $asc, evenement.id ASC $limit"

	***********************
	index.php
	***********************

	
	***********************
	liste_events.php
	***********************


	***********************
	liste_meurtres.php
	***********************

	
	***********************
	sidebar.php
	***********************


	***********************
	subEvent.php
	***********************




	
*/

?>	