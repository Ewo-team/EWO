<?php
session_start(); 
$root_url = "./../..";
$admin_mode = 1;
//-- Header --
include($root_url."/conf/master.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/

//-- Paramètres de connexion à la base de données
$ewo = bdd_connect('ewo');

include("../../persos/fonctions.php");
include("../../jeu/fonctions.php");
include("./../../admin/logs/fonctions.php");

$id_perso = mysql_real_escape_string($_POST['id_perso']);

//-- Noms personnages
if (isset($_POST['nom_perso'])){
	$nom_perso = mysql_real_escape_string($_POST['nom_perso']);
	mysql_query("UPDATE persos SET nom = '$nom_perso' WHERE id = '$id_perso'") or die (mysql_error());
	//surveillance
	surveillance($id_perso,"Changement de pseudo");
	echo "<script language='javascript' type='text/javascript' >document.location='".$_SESSION['temps']['page']."'</script>";exit;	
}

//-- Id utilisateur
if (isset($_POST['utilisateur_id'])){
	$utilisateur_id = $_POST['utilisateur_id'];
	mysql_query("UPDATE persos SET utilisateur_id = '$utilisateur_id' WHERE id = '$id_perso'") or die (mysql_error());
	echo "<script language='javascript' type='text/javascript' >document.location='".$_SESSION['temps']['page']."'</script>";exit;	
}

//-- PNJ & Mortel
if (isset($_POST['change_attribute'])){
	$pnj = (isset($_POST['pnj'])) ? 1 : 0;
        $mortel = (isset($_POST['mortel'])) ? 1 : 0;
	mysql_query("UPDATE persos SET pnj = '$pnj', mortel = '$mortel' WHERE id = '$id_perso'") or die (mysql_error());
	//echo "<script language='javascript' type='text/javascript' >document.location='".$_SESSION['temps']['page']."'</script>";
        exit;	
}


//-- Icône personnelle
if (isset($_POST['icone_id'])){
	$icone_id = $_POST['icone_id'];
	mysql_query("UPDATE persos SET icone_id = '$icone_id' WHERE id = '$id_perso'") or die (mysql_error());
	echo "<script language='javascript' type='text/javascript' >document.location='".$_SESSION['temps']['page']."'</script>";exit;	
}

//-- galon personnel
if (isset($_POST['galon_id'])){
	$galon_id = $_POST['galon_id'];
	mysql_query("UPDATE persos SET galon_id = '$galon_id' WHERE id = '$id_perso'") or die (mysql_error());
	//surveillance
	surveillance($id_perso,"Changement de galon");
	echo "<script language='javascript' type='text/javascript' >document.location='".$_SESSION['temps']['page']."'</script>";exit;	
}


//-- Race
if (isset($_POST['race'])){
	$new_race = $_POST['race'];
	$new_grade= $_POST['grade'];
	change_race_grade($id_perso, $new_race, $new_grade);
	
        if (isset($_POST['nom_race'])){

                $nom_race = $_POST['nom_race'];

                if($nom_race != '') {
                    mysql_query("UPDATE persos SET nom_race = '$nom_race' WHERE id = '$id_perso'") or die (mysql_error());
                } else {
                    mysql_query("UPDATE persos SET nom_race = NULL WHERE id = '$id_perso'") or die (mysql_error());
                }
        }        
        
	//ADD_EVENT
	echo "<script language='javascript' type='text/javascript' >document.location='".$_SESSION['temps']['page']."'</script>";
	exit;	
}



//-- Grade
if (isset($_POST['grade'])){
	$new_race = $_POST['race'];
	$new_grade= $_POST['grade'];
	change_race_grade($id_perso, $new_race, $new_grade);
	
	$event_grade_up = new EventsManager($id_perso,"grade_up");
  $event_grade_up->addEvent('grade',$new_grade);
  $event_grade_up->commitEvent();
	
	echo "<script language='javascript' type='text/javascript' >document.location='".$_SESSION['temps']['page']."'</script>";
	exit;	
}

//-- Supérieur d'affiliation
if (isset($_POST['sup_id'])){
	$sup_id = $_POST['sup_id'];
	mysql_query("UPDATE persos SET superieur_id = '$sup_id' WHERE id = '$id_perso'") or die (mysql_error());
	//surveillance
	surveillance($id_perso,"Changement de supérieur d\'affiliation");
	echo "<script language='javascript' type='text/javascript' >document.location='".$_SESSION['temps']['page']."'</script>";exit;	
}

//-- Mortalitée
if (isset($_POST['mortel'])){
	$mortel = $_POST['mortel'];
	mysql_query("UPDATE persos SET mortel = '$mortel' WHERE id = '$id_perso'") or die (mysql_error());
	//surveillance
	surveillance($id_perso,"Changement de l\'état de mortalité");
	echo "<script language='javascript' type='text/javascript' >document.location='".$_SESSION['temps']['page']."'</script>";exit;	
}

//-- Date
if (isset($_POST['date'])){
	$date = $_POST['date'];
	mysql_query("UPDATE persos SET date_tour = '$date' WHERE id = '$id_perso'")or die (mysql_error());
	echo "<script language='javascript' type='text/javascript' >document.location='".$_SESSION['temps']['page']."'</script>";exit;	
}

//-- Position
if (isset($_POST['desincarne'])){
	desincarne($id_perso);
	//surveillance
	surveillance($id_perso,"Désincarnation du perso");
	//-----
	echo "<script language='javascript' type='text/javascript' >document.location='".$_SESSION['temps']['page']."'</script>";exit;
}

if (isset($_POST['pos_x'])){
	$pos_x = $_POST['pos_x'];
	$pos_y = $_POST['pos_y'];
	$carte = $_POST['carte'];
	set_pos($id_perso, $pos_x, $pos_y, $carte);
	//surveillance
	surveillance($id_perso,"Changement de position : X : $pos_x / Y : $pos_y / Plan : $carte");
	//-----
	echo "<script language='javascript' type='text/javascript' >document.location='".$_SESSION['temps']['page']."'</script>";exit;	
}
//-- Options
if (isset($_POST['options'])){
	$options = $_POST['options'];
	mysql_query("UPDATE persos SET options = '$options' WHERE id = '$id_perso'")or die (mysql_error());
	echo "<script language='javascript' type='text/javascript' >document.location='".$_SESSION['temps']['page']."'</script>";exit;	
}

//-- Mdj
if (isset($_POST['mdj'])){
	$mdj = mysql_real_escape_string($_POST['mdj']);
	$time = ceil(time() / 119);
	$sql = "REPLACE INTO  `ewo`.`persos_mdj` (`id` ,`perso_id` ,`date` ,`message`)
			VALUES ('$time',  '$id_perso', CURRENT_TIMESTAMP ,  '$mdj');";
	mysql_query($sql)or die (mysql_error());
	mysql_query("UPDATE persos SET mdj = '$mdj' WHERE id = '$id_perso'")or die (mysql_error());
	echo "<script language='javascript' type='text/javascript' >document.location='".$_SESSION['temps']['page']."'</script>";exit;	
}

//-- Signature
if (isset($_POST['signature'])){
	$signature =  mysql_real_escape_string($_POST['signature']);
	mysql_query("UPDATE persos SET signature = '$signature' WHERE id = '$id_perso'")or die (mysql_error());
	echo "<script language='javascript' type='text/javascript' >document.location='".$_SESSION['temps']['page']."'</script>";exit;	
}

//-- Background
if (isset($_POST['background'])){
	$background = mysql_real_escape_string($_POST['background']);
	mysql_query("UPDATE persos SET background = '$background' WHERE id = '$id_perso'")or die (mysql_error());
	echo "<script language='javascript' type='text/javascript' >document.location='".$_SESSION['temps']['page']."'</script>";exit;	
}

//-- Liste complète des caracteristiques
if (isset($_POST['pi'])){
	mysql_query("UPDATE caracs SET px = '".$_POST['px']."', 
	pi = '".$_POST['pi']."', 
	pv = '".$_POST['pv']."', 
	niv_pv = '".$_POST['niv_pv']."', 
	recup_pv = '".$_POST['recup_pv']."', 
	malus_def = '".$_POST['malus_def']."', 
	niv_recup_pv = '".$_POST['niv_recup_pv']."', 
	niv = '".$_POST['niv']."', 
	cercle = '".$_POST['cercle']."', 
	res_mag = '".$_POST['res_mag']."', 
	mouv = '".$_POST['mouv']."', 
	niv_mouv = '".$_POST['niv_mouv']."', 
	pa = '".$_POST['pa']."', 
	niv_pa = '".$_POST['niv_pa']."', 
	des_attaque = '".$_POST['des_attaque']."', 
	niv_des = '".$_POST['niv_des']."',
	`force` = '".$_POST['force']."',
	`niv_force` = '".$_POST['niv_force']."',
	perception = '".$_POST['perception']."',
	niv_perception = '".$_POST['niv_perception']."'
		WHERE perso_id = '$id_perso'")or die (mysql_error());
		
	mysql_query("UPDATE caracs_alter SET 
	alter_pv = '".$_POST['pv_alter']."',  
	alter_recup_pv = '".$_POST['recup_pv_alter']."', 
	alter_def = '".$_POST['def_alter']."',  
	alter_res_mag = '".$_POST['res_mag_alter']."', 
	alter_niv_mag = '".$_POST['niv_mag_alter']."', 
	alter_mouv = '".$_POST['mouv_alter']."', 
	alter_pa = '".$_POST['pa_alter']."', 
	alter_att = '".$_POST['att_alter']."',
	`alter_force` = '".$_POST['force_alter']."',
	alter_perception = '".$_POST['perception_alter']."'
		WHERE perso_id = '$id_perso'")or die (mysql_error());
		
	/*mysql_query("UPDATE caracs_alter_mag SET 
	alter_pv = '".$_POST['pv_mag']."',  
	alter_recup_pv = '".$_POST['recup_pv_mag']."', 
	alter_def = '".$_POST['def_mag']."',  
	alter_niv_mag = '".$_POST['niv_mag_mag']."',
	alter_res_mag = '".$_POST['res_mag_mag']."', 
	alter_mouv = '".$_POST['mouv_mag']."', 
	alter_pa = '".$_POST['pa_mag']."', 
	alter_att = '".$_POST['att_mag']."',
	`alter_force` = '".$_POST['force_mag']."',
	alter_perception = '".$_POST['perception_mag']."'
		WHERE perso_id = '$id_perso' LIMIT 1")or die (mysql_error());*/
		
	mysql_query("UPDATE caracs_alter_plan SET 
	alter_pv = '".$_POST['pv_plan']."',  
	alter_recup_pv = '".$_POST['recup_pv_plan']."', 
	alter_def = '".$_POST['def_plan']."',  
	alter_res_mag = '".$_POST['res_mag_plan']."', 
	alter_niv_mag = '".$_POST['niv_mag_plan']."', 
	alter_mouv = '".$_POST['mouv_plan']."', 
	alter_pa = '".$_POST['pa_plan']."', 
	alter_att = '".$_POST['att_plan']."',
	`alter_force` = '".$_POST['force_plan']."',
	alter_perception = '".$_POST['perception_plan']."'
		WHERE perso_id = '$id_perso'")or die (mysql_error());
	echo "<script language='javascript' type='text/javascript' >document.location='".$_SESSION['temps']['page']."'</script>";exit;	
}

mysql_close($ewo);
?>
