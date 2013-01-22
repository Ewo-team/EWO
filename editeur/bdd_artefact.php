<?php
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");
/*-- Connexion requise --*/
if (ControleAcces('admin',0) == false){
	echo "acces denied";exit;
};
/*-----------------------*/

if (isset($_GET['id'])){
	// Paramètres de connexion à la base de données
	$ewo_bdd = bdd_connect('ewo');
	
	$sql="SELECT icone_artefact_id FROM damier_artefact WHERE pos_x = '".$_GET['x']."' AND pos_y = '".$_GET['y']."' AND carte_id = '".$_GET['carte_id']."'";
  $resultat = mysql_query($sql) or die(mysql_error());
  $artefact = mysql_fetch_array ($resultat);
  
  if ($_GET['action'] == 'norm'){
		if ($artefact == true){
			$sql="UPDATE damier_artefact SET 
						icone_artefact_id = '".$_GET['id']."', 
						pos_x = '".$_GET['x']."', 
						pos_y = '".$_GET['y']."', 
						pv = '".$_GET['divers']."', 
						carte_id = '".$_GET['carte_id']."' 
						WHERE pos_x = '".$_GET['x']."' AND pos_y = '".$_GET['y']."' AND carte_id = '".$_GET['carte_id']."'";
			mysql_query($sql);
			echo "done";
		}else{
		  $sql1="INSERT INTO damier_artefact (id, icone_artefact_id, pos_x, pos_y, pv, carte_id) VALUE ('',".$_GET['id'].",".$_GET['x'].",".$_GET['y'].",".$_GET['divers'].",".$_GET['carte_id'].")";
			mysql_query($sql1);
			echo "done";
		}
  }elseif ($_GET['action'] == 'sup'){
  	$sql2="DELETE FROM damier_artefact WHERE pos_x = '".$_GET['x']."' AND pos_y = '".$_GET['y']."' AND carte_id = '".$_GET['carte_id']."'";
 		mysql_query($sql2);
  }
}else{
	echo 'lien null';
}
?>
