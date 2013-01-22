<?php
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");
include ($root_url."/jeu/fonctions.php");
/*-- Connexion requise --*/
if (ControleAcces('admin',0) == false){
	echo "acces denied";exit;
};
/*-----------------------*/

if (isset($_GET['id'])){
	// Paramètres de connexion à la base de données
	$ewo_bdd = bdd_connect('ewo');
	
  if ($_GET['action'] == 'open'){
		$sql="UPDATE damier_".$_GET['type']." SET 
					statut = '1' 
					WHERE id = '".$_GET['id']."'";
		mysql_query($sql) or die(mysql_error());
		echo "update open";
  }elseif ($_GET['action'] == 'close'){
		$sql="UPDATE damier_".$_GET['type']." SET 
					statut = '0' 
					WHERE id = '".$_GET['id']."'";
		mysql_query($sql) or die(mysql_error());
		echo "update close";
  }elseif ($_GET['action'] == 'supprimer'){
	if($_GET['type']=='porte' || $_GET['type']=='bouclier'){
		$sql		="SELECT objet_lie FROM damier_".$_GET['type']." WHERE id=".$_GET['id'];
		$resultat 	= mysql_query ($sql) or die (mysql_error());
		$pos		= mysql_fetch_array($resultat);
		desincarne($pos['objet_lie'], "objet_complexe");
	
		}
		desincarne($_GET['id'], $_GET['type']);
 		echo "supprimer";
  }
}else{
	echo 'lien null';
}
?>
