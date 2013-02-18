<?php
//-- Header --
$root_url = "./../..";
include($root_url."/template/header_new.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/
?>

<h2>Liste des dernières éditions de personnages</h2>

<!-- Debut du coin -->
<div>

			
<div class='news' align='center'>

<table>

<?php
// Paramètres de connexion à la base de données

$logs = "SELECT*FROM logs_admin ORDER BY date DESC LIMIT 0,50";									
				
$switch = 0;
																				
$resultat = mysql_query ($logs) or die (mysql_error());
while ($log = mysql_fetch_array ($resultat)){

$utilisateurs = "SELECT nom FROM utilisateurs WHERE id='".$log['admin_id']."'";																		
$resul = mysql_query ($utilisateurs) or die (mysql_error());
$user = mysql_fetch_array ($resul);

$personnages = "SELECT nom FROM persos WHERE id='".$log['perso_id']."'";							
$result = mysql_query ($personnages) or die (mysql_error());
$users = mysql_fetch_array ($result);

//== modulo pour la couleur
if (($switch % 2) == 0){
	$color = '#fff';
}else{
	$color = '#CCFF33';
}

echo "
<tr style='background-color:$color;'>
	<td><a href=''>".$log['date']."</a></td>
	<td><b><a href=''>".$user['nom']."</a></b> a édité </td>
	<td><a href=''>".$users['nom']."</a></td>
	<td>".$log['message']."</td>
</tr>
";

$switch++;
} ?>
</table>
</div>


</div>
<!-- Fin du coin -->

<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
