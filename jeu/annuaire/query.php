<?php
/**
 * SQL de l'annuaire
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 
 * @package annuaire
 */
/*
	***********************
	ajout_repertoire.php
	***********************
		"SELECT persos.id AS id_perso, persos.nom AS nom_perso
			FROM persos WHERE utilisateur_id = '$utilisateur_id'"
			
	***********************
	ajouter_contact.php
	***********************
		"SELECT nom FROM persos WHERE id = '$contact'"
		"SELECT id FROM repertoire WHERE perso_id = $perso AND contact_id='$contact'"
		"INSERT INTO repertoire (id, perso_id, contact_id) VALUES ('', '$perso', '$contact')"
		"SELECT nom FROM persos WHERE id = '$contact'"
		"INSERT INTO repertoire (id, perso_id, contact_id) VALUES ('', '$perso', '$contact')"
	
	***********************
	index.php
	***********************

	
	***********************
	rechercher_matricule.php
	***********************
		"SELECT persos.id AS id_personnage, persos.nom AS nom_perso, races.color AS couleur, races.nom AS nom_race   
			FROM persos 
			INNER JOIN races ON persos.race_id = races.id 
			WHERE persos.nom = '$pseudo'"
	
	***********************
	rechercher_personnage.php
	***********************	
		"SELECT persos.nom AS nom_perso, races.color AS couleur, races.nom AS nom_race   
			FROM persos 
			INNER JOIN races ON persos.race_id = races.id 
			WHERE persos.id = '".$mat."'"
	
*/

?>	
