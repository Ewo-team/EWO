<?php
/**
 * Légion - Fonctions des Légions
 *
 * @author Anarion <anarion@ewo.fr>
 * @version 1.0
 * @package Légion
 * @category fonctions
 */

function accept_event($perso_id)
{

}

function quit_event($perso_id)
{

}

function del_membre($faction_id, $mem_id)
{
	mysql_query("DELETE FROM `ewo`.`faction_membres` WHERE `faction_membres`.`perso_id` = '$mem_id' LIMIT 1 ;") or die (mysql_error());
	return mysql_query("UPDATE `ewo`.`persos` SET `faction_id` = '0' WHERE `persos`.`id` = '$mem_id' LIMIT 1 ;") or die (mysql_error());
}

function del_grade($faction_id, $grade_id)
{
	if ($grade_id!="4")
	{
		$requete_del = mysql_query("SELECT perso_id FROM faction_membres WHERE faction_id=$faction_id AND faction_grade_id=$grade_id") or die (mysql_error());
		while($reponse = mysql_fetch_array($requete_del))
		{
		 $perso_id = $reponse['perso_id'];
		 mysql_query("UPDATE `ewo`.`faction_membres` SET `faction_grade_id` = '4' WHERE `faction_membres`.`perso_id` = '$perso_id' LIMIT 1 ;") or die (mysql_error());
		}
		mysql_query("DELETE FROM `ewo`.`faction_grades` WHERE (`faction_grades`.`grade_id` = '$grade_id' AND `faction_grades`.`faction_id` = '$faction_id') LIMIT 1 ;") or die (mysql_error());
	}
}

function bal_grade($faction_id, $grade_id, $uperso_id)
{
	$requete_bal = mysql_query("SELECT perso_id FROM faction_membres WHERE faction_id=$faction_id AND faction_grade_id=$grade_id") or die (mysql_error());
	$perso_id = '';
	while($reponse = mysql_fetch_array($requete_bal))
	{
	 if ($perso_id=='')
		$perso_id = $reponse['perso_id'];
	 else $perso_id = $perso_id.'-'.$reponse['perso_id'];
	}
	echo "<script language='javascript' type='text/javascript' >document.location='../messagerie/index.php?id=".$uperso_id."&dest=".$perso_id."'</script>";exit;

}

function bal_faction($faction_id, $uperso_id)
{
	$requete_bal = mysql_query("SELECT perso_id FROM faction_membres WHERE faction_id=$faction_id") or die (mysql_error());
	$perso_id = '';
	while($reponse = mysql_fetch_array($requete_bal))
	{
	 if ($perso_id=='')
		$perso_id = $reponse['perso_id'];
	 else $perso_id = $perso_id.'-'.$reponse['perso_id'];
	}
	echo "<script language='javascript' type='text/javascript' >document.location='../messagerie/index.php?id=".$uperso_id."&dest=".$perso_id."'</script>";exit;
}

function del_faction($faction_id)
{
	$requete_del = mysql_query("SELECT id FROM persos WHERE faction_id=$faction_id") or die (mysql_error());
	while($reponse = mysql_fetch_array($requete_del))
	{
		del_membre($faction_id, $reponse['id']);
	}
	mysql_query("DELETE FROM `ewo`.`faction_grades` WHERE `faction_grades`.`faction_id` = $faction_id") or die (mysql_error());
	mysql_query("DELETE FROM `ewo`.`factions` WHERE `factions`.`id` = $faction_id") or die (mysql_error());
}

function race_faction($faction_id, $race_id)
{
	if($race_id==2)
	{
	mysql_query("UPDATE `ewo`.`factions` SET `type` = '3', `type_nom`='Faction de Traitre' WHERE `factions`.`id` = '$faction_id' LIMIT 1 ;") or die (mysql_error());
	}
	mysql_query("UPDATE `ewo`.`factions` SET `race` = $race_id WHERE `factions`.`id` = '$faction_id' LIMIT 1 ;") or die (mysql_error());
}

function creer_faction($nom, $description_faction, $type, $race)
{
	$nom = mysql_real_escape_string($nom);
	$description_faction = mysql_real_escape_string($description_faction);

	if($type==0)
	{
	$type_nom = 'Légion basique, non Loyaliste';
	}
	elseif($type==1)
	{
	$type_nom= 'Légion de Justice';
	}
	elseif($type==2)
	{
	$type_nom='Légion de D&eacute;fense';
	}
	elseif($type==3)
	{
	$type_nom='Légion de Traitres';
	}
	elseif($type==4)
	{
	$type_nom='Légion Loyaliste';
	}
	$type_nom = mysql_real_escape_string($type_nom);

	//Ajout de la Légion dans la liste des Légion

	$sql_faction = mysql_query("INSERT INTO `factions`(`id`,
												`nom`,
												`race`,
												`description`,
												`type`,
												`type_nom`,
												`creation_date`,
												`site_url`,
												`logo_url`) 
									VALUES ('', 
											'$nom', 
											'$race', 
											'$description_faction', 
											'$type', 
											'$type_nom', 
											CURRENT_TIMESTAMP(), 
											'', 
											'')
							") or die (mysql_error());
					
	$faction_id = mysql_insert_id();
	// Création des 4 grades élémentaires : Chef, Bras droit, Ancien membre, Membre à l'essai
	for ($inc=1; $inc<=4; $inc++)
	{
	$requete_creation = mysql_query("SELECT MAX(grade_id) FROM faction_grades WHERE faction_id=$faction_id") or die (mysql_error());
	$reponse = mysql_fetch_array($requete_creation);
	if(isset($reponse))
	{
	$id_grade=$reponse[0] + 1;
	}
	else $id_grade = 1;

	if($inc==1)
	{
	$droits='10000000';
	$nom='Chef';
	}
	if($inc==2)
	{
	$droits='01111111';
	$nom='Bras droit';
	}
	if($inc==3)
	{
	$droits='00111111';
	$nom='Vieux Membre';
	}
	if($inc==4)
	{
	$droits='00000001';
	$nom='Membre à l\'essai';
	}
	$nom = htmlentities(mysql_real_escape_string($nom), ENT_COMPAT, 'UTF-8');

	$sql_faction = mysql_query("INSERT INTO faction_grades(id,
												grade_id,
												faction_id,
												nom,
												description,
												droits) 
									VALUES ('',
											'$id_grade', 
											'$faction_id', 
											'$nom', 
											'', 
											'$droits')
							") or die (mysql_error());
	}	
	return $faction_id;
}

function add_chief($faction_id, $nom_chef)
{
	$nom = $nom_chef;
	// Vérification de l'absence de blason, et que le joueur n'est pas un tricheur
	$verif = mysql_query("SELECT grade_id, id FROM persos WHERE (nom REGEXP '$nom' OR id REGEXP '$nom') AND faction_id=0") or die (mysql_error());
	$rep = mysql_fetch_array($verif);
	if (isset($rep['grade_id']) && $rep['grade_id']!=-1)
	{
	$id_perso = $rep['id'];
	// Si c'est bon, on blasonne le joueur, puis on lui donne le grade de chef.
	$sql_faction = mysql_query("UPDATE `ewo`.`persos` SET `faction_id` = '$faction_id' WHERE `persos`.`id` = '$id_perso' LIMIT 1 ;") or die (mysql_error());
	$sql_faction = mysql_query("INSERT INTO `ewo`.`faction_membres` (`id` ,
																	`perso_id` ,
																	`faction_id` ,
																	`faction_grade_id`
																	)
																VALUES (
																		NULL , '$id_perso', '$faction_id', '1'
																		);") or die (mysql_error());
	}
}

function add_mem($faction_id, $nom)
{
	// Vérification de l'absence de blason, et que le joueur n'est pas un tricheur
	$verif = mysql_query("SELECT grade_id, id FROM persos WHERE (nom REGEXP '$nom' OR id REGEXP '$nom') AND faction_id=0") or die (mysql_error());
	$rep = mysql_fetch_array($verif);
	if (isset($rep['grade_id']) && $rep['grade_id']!=-1)
	{
	$id_perso = $rep['id'];
	// Si c'est bon, on blasonne le joueur, puis on lui donne le grade de chef.
	$sql_faction = mysql_query("UPDATE `ewo`.`persos` SET `faction_id` = '$faction_id' WHERE `persos`.`id` = '$id_perso' LIMIT 1 ;") or die (mysql_error());
	$sql_faction = mysql_query("INSERT INTO `ewo`.`faction_membres` (`id` ,
																	`perso_id` ,
																	`faction_id` ,
																	`faction_grade_id`
																	)
																VALUES (
																		NULL , '$id_perso', '$faction_id', '4'
																		);") or die (mysql_error());
	}
}

function upgrade_mem($mem_id, $grade_id)
{
 mysql_query("UPDATE `ewo`.`faction_membres` SET `faction_grade_id` = '$grade_id' WHERE `faction_membres`.`perso_id` = '$mem_id' LIMIT 1 ;") or die (mysql_error());
}

function refuse($faction_id, $perso_id)
{
	$sql_demande 	= "DELETE FROM `ewo`.`wait_faction` WHERE `wait_faction`.`faction_id` = $faction_id AND `wait_faction`.`perso_id` = $perso_id LIMIT 1";
	return $res_demande	= mysql_query (mysql_real_escape_string($sql_demande)) or die (mysql_error());
}

function accepte($faction_id, $perso_id)
{
	add_mem($faction_id, $perso_id);
	accept_event($perso_id);
	return refuse($faction_id, $perso_id);
}

function faction_type ($faction_id){

$sql = "SELECT type FROM `factions` WHERE id=$faction_id LIMIT 0, 30 ";
$reponse = mysql_query($sql) or die(mysql_error());
$reponse= mysql_fetch_array ($reponse);
return $reponse['type'];
}

function faction_race($faction_id){

$sql = "SELECT race FROM `factions` WHERE id=$faction_id LIMIT 0, 30 ";
$reponse = mysql_query($sql) or die(mysql_error());
$reponse= mysql_fetch_array ($reponse);
return $reponse['race'];
}

function faction_camps($faction_id){

	$sql = "SELECT camps.id as id, camps.carte_id as carte FROM `factions`
	INNER JOIN races ON factions.race=races.race_id
	INNER JOIN camps ON camps.id = races.camp_id
	WHERE factions.id=$faction_id LIMIT 1";
	$reponse = mysql_query($sql) or die(mysql_error());
	$reponse= mysql_fetch_array ($reponse);
	return array('id' => $reponse['id'], 'carte' => $reponse['carte']);
}

function is_membre($utilisateur_id, $faction_id){

$requete = "SELECT faction_id FROM persos WHERE utilisateur_id=$utilisateur_id AND faction_id=$faction_id";
$reponse = mysql_query ($requete) or die (mysql_error());
return $faction  = mysql_fetch_array ($reponse);
}


function member_faction_grade($perso_id){
$requete = "SELECT faction_grade_id FROM faction_membres WHERE perso_id=$perso_id";
$reponse = mysql_query ($requete) or die (mysql_error());
$faction  = mysql_fetch_array ($reponse);
return $faction['faction_grade_id'];
}

function recup_ennemis_prof($faction_id){

	if(faction_type($faction_id)!=2){
		return null;
		}

	$faction_camps = faction_camps($faction_id);

	$sql		= 'SELECT persos.nom, damier_persos.perso_id AS perso_id , damier_persos.pos_y AS pos_y
					FROM damier_persos
					INNER JOIN persos ON persos.id=damier_persos.perso_id
					INNER JOIN races ON races.race_id = persos.race_id AND races.grade_id = persos.grade_id
					WHERE damier_persos.carte_id='.$faction_camps['carte'].' AND races.camp_id != '.$faction_camps['id'].';';
	$resultat	= mysql_query($sql) or die(mysql_error());
	return $resultat;
}



function recup_pot_traitre($tueur_id, $depuis=""){

$sql = "SELECT factions.id AS id
			FROM factions
			LEFT JOIN persos ON persos.id = $tueur_id
				WHERE factions.race=persos.race_id AND factions.type=1";
$res = mysql_query($sql) or die(mysql_error());
$faction_id ="";
while($resultat = mysql_fetch_array($res)){
	if($faction_id=="")
		$faction_id = $resultat['id'];
		else $faction_id.=" OR p1.faction_id = ".$resultat['id'];
	}
if($depuis=="")
	$depuis = '0000-00-00 00:00:00';

	$sql="SELECT morgue.mat_victime, morgue.id_perso, morgue.date, morgue.nom_victime 
			FROM morgue
				LEFT JOIN persos p1
					ON p1.id = morgue.id_perso
				LEFT JOIN persos p2
					ON p2.id = morgue.mat_victime
						WHERE ((p1.faction_id = $faction_id) AND 
								(morgue.date>='$depuis') AND
								p1.race_id=p2.race_id)
							GROUP BY morgue.mat_victime";

	$resultat	= mysql_query($sql) or die(mysql_error());
	return $resultat;
}


function nvTraitre($perso_id){

$sql = "SELECT factions.id AS id
			FROM factions
			LEFT JOIN persos ON persos.id = $perso_id
				WHERE factions.race=persos.race_id AND factions.type=3
				ORDER BY factions.id ASC";
$resultat = mysql_query($sql) or die(mysql_error());
$resultat = mysql_fetch_array($resultat);
$faction_id = $resultat['id'];

accepte($faction_id, $perso_id);

}

?>
