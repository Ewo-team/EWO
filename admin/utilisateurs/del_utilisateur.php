<?php
session_start();
$root_url = "../..";
include ($root_url."/conf/master.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/

if((!empty($_POST['id_utilisateur'])) && ($_POST['supprimer'] == "supp")){
//-- Paramètres de connexion à la base de données
bdd_connect('ewo');

$id_utilisateur = mysql_real_escape_string($_POST['id_utilisateur']);

//-- Liste des ID des personnages
$sql="SELECT id FROM persos WHERE utilisateur_id = '$id_utilisateur'";
$resultat = mysql_query ($sql) or die (mysql_error());

	while($persos = mysql_fetch_array ($resultat)){
		//-- Supression dans : REPERTOIRE > perso_id, contact_id
			mysql_query("DELETE FROM repertoire WHERE perso_id = '".$persos['id']."'") or die (mysql_error());
			mysql_query("DELETE FROM repertoire WHERE contact_id = '".$persos['id']."'") or die (mysql_error());
	
		//-- Supression dans : PERSOS > id
			mysql_query("DELETE FROM persos WHERE id = '".$persos['id']."'") or die (mysql_error());
	
		//-- Supression dans : LOGS_ADMIN > perso_id
			mysql_query("DELETE FROM logs_admin WHERE perso_id = '".$persos['id']."'") or die (mysql_error());
	
		//-- Supression dans : INVENTAIRE > perso_id
			mysql_query("DELETE FROM inventaire WHERE perso_id = '".$persos['id']."'") or die (mysql_error());
	
		//-- Supression dans : FACTION_MEMBRES > perso_id	
		mysql_query("DELETE FROM faction_membres WHERE perso_id = '".$persos['id']."'") or die (mysql_error());
	
		//-- Supression dans : EVENEMENT > perso_id	
		mysql_query("DELETE FROM evenement WHERE perso_id = '".$persos['id']."'") or die (mysql_error());
		
		//-- Supression dans : DAMIER_PERSOS > perso_id	
		mysql_query("DELETE FROM damier_persos WHERE perso_id = '".$persos['id']."'") or die (mysql_error());	
	
		//-- Supression dans : CARACS_ALTER_PLAN > perso_id	
		mysql_query("DELETE FROM caracs_alter_plan WHERE perso_id = '".$persos['id']."'") or die (mysql_error());	
		
		//-- Supression dans : CARACS_ALTER_MAG > perso_id	
		mysql_query("DELETE FROM caracs_alter_mag WHERE perso_id = '".$persos['id']."'") or die (mysql_error());	
	
		//-- Supression dans : CARACS_ALTER > perso_id	
		mysql_query("DELETE FROM caracs_alter WHERE perso_id = '".$persos['id']."'") or die (mysql_error());		
	
		//-- Supression dans : CARACS > perso_id	
		mysql_query("DELETE FROM caracs  WHERE perso_id = '".$persos['id']."'") or die (mysql_error());		
		
		//-- Supression dans : BALS > perso_src_id
		mysql_query("DELETE FROM bals WHERE perso_src_id = '".$persos['id']."'") or die (mysql_error());	
	}
	
	//---- SUPPRESSION DES DONNEES UTILISATEUR

//-- Suppression dans : UTILISATEUR > id et UTILISATEUR_BAN > utilisateur_id
	mysql_query("DELETE FROM utilisateurs WHERE id='$id_utilisateur'") or die (mysql_error());
	mysql_query("DELETE FROM utilisateurs_ban WHERE utilisateur_id='$id_utilisateur'") or die (mysql_error());

//-- Supression dans : LOGS > utilisateur_id
	mysql_query("DELETE FROM logs WHERE utilisateur_id = '$id_utilisateur'") or die (mysql_error());	
	
//-- Suppression dans : UTILISATEURS_OPTION > utilisateur_id
	mysql_query("DELETE FROM utilisateurs_option WHERE utilisateur_id='$id_utilisateur'") or die (mysql_error());
	
	mysql_close();	
		$titre = "Suppression";
		$text = "Suppression total de l'utilisateur et de ses personnages effectué'.";
		$lien = "./../..";
		gestion_erreur($titre, $text, $lien);
}
