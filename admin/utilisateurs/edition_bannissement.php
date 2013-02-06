<?php
require_once __DIR__ . '/../../conf/master.php';
//-- Paramètres de connexion à la base de données
bdd_connect('ewo');
/*-- Connexion basic requise --*/
ControleAcces('admin',1);
/*-----------------------------*/
if(!empty($_POST['ban_fin'])){


//--------Gestion du bannissement ------

$ban_date = time();
$ban_fin = strtotime(mysql_real_escape_string($_POST['ban_fin']));
$ban_motif = mysql_real_escape_string($_POST['ban_motif']);
$id_utilisateur = mysql_real_escape_string($_POST['id_utilisateur']);

if (isset($_POST['ban_check']) && $_POST['ban_check'] == 1){
	if (isset($_POST['ban_existe']) && $_POST['ban_existe'] == 'existe'){
		mysql_query("UPDATE utilisateurs_ban SET date = '$ban_date', date_fin = '$ban_fin', motif = '$ban_motif' WHERE utilisateur_id = '$id_utilisateur'") or die (mysql_error());
	}else{
		mysql_query("INSERT INTO utilisateurs_ban(utilisateur_id, date, date_fin, motif, statut) VALUES ('$id_utilisateur', '$ban_date','$ban_fin','$ban_motif','')") or die (mysql_error());	
	}
}elseif(!isset($_POST['ban_check']) && $_POST['ban_check'] != 1){
	mysql_query("DELETE FROM utilisateurs_ban WHERE utilisateur_id='$id_utilisateur'") or die (mysql_error());
}	
//---------------------------------------


//--------Gestion du bannissement pour le forum ------
/*
mysql_close();
		
mysql_connect($_FSERVEUR,$_FUSER,$_FPASS);
mysql_select_db($_FBDD);
		
while($perso_id['id']){		
	if (isset($_POST['ban_check']) && $_POST['ban_check'] == 1){
		if (isset($_POST['ban_existe']) && $_POST['ban_existe'] == 'existe'){
			//-- Update ban
			mysql_query("UPDATE phpbb_banlist SET ban_start ='$ban_date', ban_end = '$ban_fin', ban_reason = '$ban_motif', ban_give_reason = '$ban_motif'") or die (mysql_error());
		}else{
			//-- Insert ban
			mysql_query("INSERT INTO phpbb_banlist(`ban_id`, `ban_userid`, `ban_ip`, `ban_email`, `ban_start`, `ban_end`, `ban_exclude`, `ban_reason`, `ban_give_reason`) VALUES (NULL, '$perso_id['id']', '', '', '$ban_date', '$ban_fin', '0', '$ban_motif', '$ban_motif')") or die (mysql_error());
		}
	}elseif(!isset($_POST['ban_check']) && $_POST['ban_check'] != 1){
		//-- Delete ban
		mysql_query("DELETE FROM phpbb_banlist	WHERE ban_userid = '$perso_id['id']'") or die (mysql_error());
	}
}

*/
//---------------------------------------

mysql_close();
echo "<script language='javascript' type='text/javascript' >document.location='".$_SESSION['temps']['page']."'</script>";exit;
}else{
echo "<script language='javascript' type='text/javascript' >document.location='".$_SESSION['temps']['page']."'</script>";exit;}
?>
