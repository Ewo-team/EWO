<?php

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
echo "<script language='javascript' type='text/javascript' >document.location='../../messagerie/index.php?id=".$uperso_id."&dest=".$perso_id."'</script>";exit;

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
echo "<script language='javascript' type='text/javascript' >document.location='../../messagerie/index.php?id=".$uperso_id."&dest=".$perso_id."'</script>";exit;
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
$type_nom = 'Faction basique, non Loyaliste';
}
elseif($type==1)
{
$type_nom= 'Faction de Justice';
}
elseif($type==2)
{
$type_nom='Faction de D&eacute;fense';
}
elseif($type==3)
{
$type_nom='Faction de Traitres';
}
elseif($type==4)
{
$type_nom='Faction Loyaliste';
}
$type_nom = mysql_real_escape_string($type_nom);

//Ajout de la faction dans la liste des factions

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
?>