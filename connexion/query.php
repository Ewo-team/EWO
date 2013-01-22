<?php

/*
	***********************
	action_ajax.php
	***********************

	
	***********************
	connexion.php
	***********************
		"SELECT id,nom,passwd,passwd_forum,email,droits,jabberid FROM utilisateurs WHERE nom = '$utilisateur'"
		"SELECT*FROM utilisateurs_ban WHERE utilisateur_id = '$utilisateur_id'"
		"DELETE FROM utilisateurs_ban WHERE utilisateur_id='$utilisateur_id'"
		"SELECT persos.id AS id, persos.nom AS nom, persos.race_id AS race_id, persos.grade_id AS grade_id,  persos.galon_id AS galon_id, persos.superieur_id AS superieur_id, persos.faction_id AS faction_id
			FROM persos WHERE persos.utilisateur_id = '".$connexion['id']."' ORDER BY persos.id ASC"
		"SELECT * FROM damier_persos WHERE perso_id='".$persos['id']."'"
		"SELECT faction_membres.faction_grade_id AS faction_grade, faction_grades.droits AS droits
			FROM faction_membres INNER JOIN faction_grades ON faction_grades.faction_id = ".$persos['faction_id']." AND faction_grades.grade_id = faction_membres.faction_grade_id
			WHERE faction_membres.perso_id = ".$persos['id']
		"INSERT INTO at_triche (utilisateur_id,utilisateur_id2,type,date)
			SELECT '".$utilisateur_id."','".$cookies_utilisateur_id."',0,CURRENT_TIMESTAMP()
			FROM dual WHERE NOT EXISTS
				(SELECT * FROM at_triche WHERE type=0 AND
				(utilisateur_id = ".$utilisateur_id." AND utilisateur_id2 = ".$cookies_utilisateur_id.")
				OR (utilisateur_id2 = ".$utilisateur_id." AND utilisateur_id = ".$cookies_utilisateur_id."));"
		"INSERT INTO logs (id, utilisateur_id, date, navigateur, ip, host, cookie_id, cookie_ip, cookie_date) VALUES ('', '$utilisateur_id', CURRENT_TIMESTAMP(), '$navigateur', '$ip_adresse', '$host', '$cookies_utilisateur_id', '$cookies_ip', '$cookies_date' )"
	
	***********************
	controle_connexion.php
	***********************
		"SELECT droits FROM utilisateurs WHERE id = '$utilisateur_id'"
		"SELECT*FROM utilisateurs_ban WHERE utilisateur_id = '$utilisateur_id'"
		"DELETE FROM utilisateurs_ban WHERE utilisateur_id='$utilisateur_id'"
	
	***********************
	index.php
	***********************

	
	***********************
	recup_password.php
	***********************
		"SELECT nom FROM utilisateurs WHERE email = '$email'"
		"UPDATE utilisateurs SET passwd = '$passencode' WHERE email = '$email'"
	
	***********************
	recuperation.php
	***********************

	
*/

?>	