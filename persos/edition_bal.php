<?php	
require_once __DIR__ . '/../conf/master.php';
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/

if (isset($_POST['id_perso'])){

$id_perso = mysql_real_escape_string($_POST['id_perso']);
$utilisateur_id = $_SESSION['utilisateur']['id'];
   
	$sql="SELECT options FROM persos WHERE utilisateur_id = '$utilisateur_id' AND id = '$id_perso'";
	$resultat = mysql_query ($sql) or die (mysql_error());
	$options = mysql_fetch_array ($resultat);

	if(!empty($_POST['bal_mail']) AND $_POST['bal_mail'] == 'ok'){
		$bal_mail = 1;
	}else{
		$bal_mail = 0;
	}

	if(!empty($_POST['bal_type']) AND $_POST['bal_type'] == 'html'){
		$bal_type = 0;
	}else{
		$bal_type = 1;
	}

	$options['options'][3] = $bal_mail;
	$options['options'][4] = $bal_type;
	//echo $options['options'];
 
	if(mysql_query("UPDATE persos SET options = '".$options['options']."' WHERE utilisateur_id = '$utilisateur_id' AND id = '$id_perso'")){
		header("location:editer_perso.php?id=".$id_perso."");
	}else{
		echo "erreur sql";exit;
	}
}else{
	echo "erreur";
}
?>
