<?php	
//-- Header --
$root_url = "..";
include($root_url."/template/header_new.php");
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/

if ((isset($_POST['mdj'])) AND (isset($_POST['id_perso']))){

$id_perso = mysql_real_escape_string($_POST['id_perso']);

$mdj = mysql_real_escape_string($_POST['mdj']);
$mdj = htmlspecialchars($mdj);

$utilisateur_id = $_SESSION['utilisateur']['id'];
   

	mysql_query("UPDATE persos SET mdj = '$mdj' WHERE id = '$id_perso'");   
	if(mysql_affected_rows() > 0)
	{
		$time = ceil(time() / 119);
		$sql = "REPLACE INTO  `ewo`.`persos_mdj` (`id` ,`perso_id` ,`date` ,`message`)
				VALUES ('$time',  '$id_perso', CURRENT_TIMESTAMP ,  '$mdj');";
		mysql_query($sql);	
	}
   
	//mysql_query("UPDATE persos SET mdj = '$mdj' WHERE utilisateur_id = '$utilisateur_id' AND id = '$id_perso'");
  echo "<script language='javascript' type='text/javascript' >document.location='".$_SESSION['temps']['page']."'</script>";exit;	
}else{
		echo "<h2>Modification de votre personnage</h2><p align='center'>Vous n'êtes pas autorisé à effectuer cette action.</p><p align='center'>[<a href='".$_SESSION['temps']['page']."'>Retour</a>]</p>";
	include($root_url."/template/footer_new.php");exit;
}

?>
