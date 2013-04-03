<?php	
require_once __DIR__ . '/../conf/master.php';
ControleAcces('utilisateur',1);
/*-----------------------------*/

if (isset($_POST['id_perso'])){

$id_perso = filter_input(INPUT_POST, 'id_perso', FILTER_SANITIZE_NUMBER_INT);
$utilisateur_id = $_SESSION['utilisateur']['id'];
   
	$sql="SELECT options FROM persos WHERE utilisateur_id = '$utilisateur_id' AND id = '$id_perso'";
	$resultat = mysql_query ($sql) or die (mysql_error());
	$options = mysql_fetch_array ($resultat);

	if(!empty($_POST['signature_defaut']) AND $_POST['signature_defaut'] == 'ok'){
		$signature = 1;
	}else{
		$signature = 0;
	}

	$options['options'][0] = $signature;
	//echo $options['options'];
 
	if(mysql_query("UPDATE persos SET options = '".$options['options']."' WHERE utilisateur_id = '$utilisateur_id' AND id = '$id_perso'")){
		header("location:editer_perso.php?id=".$id_perso."");
	}else{
		echo "erreur sql";exit;
	}
}else{
	//header("location:editer_perso.php?id=".$id_perso."");
	echo "erreur";exit;
}

?>
