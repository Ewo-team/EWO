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
	
	$sql="SELECT case_objet_simple_id FROM damier_objet_simple WHERE pos_x = '".$_GET['x']."' AND pos_y = '".$_GET['y']."' AND carte_id = '".$_GET['carte_id']."'";
  $resultat = mysql_query($sql);
  $decor = mysql_fetch_array ($resultat);
  
  if ($_GET['action'] == 'norm'){
		if ($decor == true){
			$sql="UPDATE damier_objet_simple SET 
						case_objet_simple_id = '".$_GET['id']."', 
						pos_x = '".$_GET['x']."', 
						pos_y = '".$_GET['y']."', 
						pv = '".$_GET['divers']."', 
						carte_id = '".$_GET['carte_id']."' 
						WHERE pos_x = '".$_GET['x']."' AND pos_y = '".$_GET['y']."' AND carte_id = '".$_GET['carte_id']."'";
			mysql_query($sql);
			echo "update";
		}else{
		  $sql1="INSERT INTO damier_objet_simple (id, case_objet_simple_id, pos_x, pos_y, pv, carte_id) VALUE ('','".$_GET['id']."','".$_GET['x']."','".$_GET['y']."','".$_GET['divers']."','".$_GET['carte_id']."')";
			mysql_query($sql1);
			echo "insert"; 
		}
  }elseif ($_GET['action'] == 'sup'){
  	$sql2="DELETE FROM damier_objet_simple WHERE pos_x = '".$_GET['x']."' AND pos_y = '".$_GET['y']."' AND carte_id = '".$_GET['carte_id']."'";
 		mysql_query($sql2);
 		echo "deledt";
  }
}else{
	echo 'lien null';
}
?>
