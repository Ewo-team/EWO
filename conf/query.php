<?php

/*
	***********************
	carac_perso_base.php
	***********************

	
	***********************
	config.php
	***********************

	
	***********************
	connect.conf.php
	***********************

	
	***********************
	detect_navi.php
	***********************

	
	***********************
	fonctions.php
	***********************
		"SELECT options FROM persos WHERE id = '".$perso_id."'"
		"SELECT grille FROM utilisateurs_option WHERE utilisateur_id = '".$id_utilisateur."'"
		"SELECT redirection FROM utilisateurs_option WHERE utilisateur_id = '".$id_utilisateur."'"
		"SELECT count(race_id) AS nbperso FROM persos WHERE race_id=".$race.""
		"SELECT count(id) AS nbjoueur FROM utilisateurs"
		"SELECT count(persos.id) AS nbpersos FROM persos
			INNER JOIN damier_persos ON damier_persos.perso_id = persos.id
			WHERE persos.race_id = '".$race."'"
		"SELECT count(persos.id) AS nbpersos FROM persos
			INNER JOIN damier_persos ON damier_persos.perso_id = persos.id
			WHERE persos.race_id = '".$race."' AND persos.grade_id='".$grade."'"
		"SELECT count(persos.id) AS nbpersos FROM persos
			INNER JOIN damier_persos ON damier_persos.perso_id = persos.id
			WHERE persos.race_id = '".$race."' AND persos.grade_id='".$grade."' AND damier_persos.carte_id = '".$plan."'"	
		"SELECT nom FROM cartes WHERE id=".$id.""
		"SELECT nom FROM persos WHERE id=".$id.""
		"SELECT nom FROM action WHERE id=".$id.""
		"SELECT nom FROM categorie_objet_simple WHERE id=".$id.""
		"SELECT nom FROM categorie_objet_complexe WHERE id=".$id.""
		"SELECT nom FROM damier_porte WHERE id=".$id.""
		"SELECT nom FROM damier_bouclier WHERE id=".$id.""
		"SELECT nom FROM races WHERE race_id=$race_id AND grade_id=-2"
		"SELECT utilisateur_id FROM persos WHERE id='".$idduperso."'"
		"SELECT utilisateur_id FROM persos WHERE id='".$idduperso."' AND utilisateur_id='".$idutilisateur."'"
		"SELECT galon_id, grade_id FROM persos WHERE id = '$id'"
		"SELECT*FROM icone_galons	WHERE id= '$id_galon'"
		"SELECT persos.icone_id	FROM persos	WHERE persos.id = '$id_perso'"
		"SELECT persos.grade_id, persos.race_id FROM persos WHERE persos.id='$id_perso'"
		"SELECT camp_id FROM `races` WHERE race_id=$race_id LIMIT 1 "
		"SELECT C.px FROM caracs C WHERE C.perso_id = '$id_perso'"
		"SELECT icone_persos.icone_url FROM icone_persos 
			WHERE icone_persos.race_id = $race_id AND icone_persos.grade_id = $grade_id AND ($px BETWEEN icone_persos.xp_min AND icone_persos.xp_max)"
		"SELECT icone_persos.icone_url FROM icone_persos 
			WHERE icone_persos.race_id = $race_id AND icone_persos.grade_id = 0 AND ($px BETWEEN icone_persos.xp_min AND icone_persos.xp_max)"
		"SELECT persos.race_id, icone_persos.icone_url FROM persos
			INNER JOIN icone_persos ON icone_persos.id = persos.icone_id
			WHERE persos.id = '$id_perso'"
		"SELECT valeur AS valeur FROM ewo.record
			INNER JOIN ewo.persos ON record.perso_id=persos.id
			WHERE (record.type='$type' AND persos.race_id=$race $regval)"
		"SELECT * FROM ewo.record"
		"SELECT race_id FROM persos WHERE id = $perso_id"
		"UPDATE ewo.record INNER JOIN ewo.persos ON record.perso_id=persos.id
			SET record.perso_id=$perso_id, record.valeur='$valeur'
			WHERE record.type='$type' AND persos.race_id=$race"
		"UPDATE ewo.record INNER JOIN ewo.persos ON record.perso_id=persos.id
			SET record.perso_id=$perso_id, record.valeur='$valeur'
			WHERE record.type='$type' AND persos.race_id=$race AND valeur REGEXP '".$val."'"	
		"INSERT INTO ewo.record (id, type, perso_id, valeur)
				VALUES ('','$type','$perso_id','$valeur')"
				
	***********************
	generation_pass.php
	***********************

	
	***********************
	index.php
	***********************

	
	***********************
	master.php
	***********************

	
	***********************
	proxy.php
	***********************

	
*/

?>	