<?php
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");
/*-- Connexion requise --*/
ControleAcces('admin',1);
/*-----------------------*/

if (isset($_GET['id'])){
	// Paramètres de connexion à la base de données
	$ewo_bdd = bdd_connect('ewo');
	
	$sql="SELECT terrain_id FROM damier_terrain WHERE pos_x = '".$_GET['x']."' AND pos_y = '".$_GET['y']."' AND carte_id = '".$_GET['carte_id']."'";
  $resultat = mysql_query($sql);
  $decor = mysql_fetch_array ($resultat);
  
  if ($_GET['action'] == 'norm'){
		if ($decor == true){
			$sql="UPDATE damier_terrain SET 
						carte_id = '".$_GET['carte_id']."', 
						terrain_id = '".$_GET['id']."', 
						pos_x = '".$_GET['x']."', 
						pos_y = '".$_GET['y']."' 
						WHERE pos_x = '".$_GET['x']."' AND pos_y = '".$_GET['y']."' AND carte_id = '".$_GET['carte_id']."'";
			mysql_query($sql) or die(mysql_error());
			echo "update";
		}else{
		  $sql1="INSERT INTO damier_terrain (id, carte_id, terrain_id, pos_x, pos_y) VALUE ('','".$_GET['carte_id']."','".$_GET['id']."','".$_GET['x']."','".$_GET['y']."')";
			mysql_query($sql1) or die(mysql_error());
			echo "insert";
		}
  }elseif ($_GET['action'] == 'sup'){
  	$sql2="DELETE FROM damier_terrain WHERE pos_x = '".$_GET['x']."' AND pos_y = '".$_GET['y']."' AND carte_id = '".$_GET['carte_id']."'";
 		mysql_query($sql2);
 		echo "supprimer";
  }
}else{
	echo 'lien null';
}
?>
