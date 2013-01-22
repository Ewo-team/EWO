<?php
//-- Header --
$root_url = "./../..";
include($root_url."/template/header_new.php");
/*-- Connexion basic requise --*/
ControleAcces('admin',1);
/*-----------------------------*/

if (!isset($_GET['alpha'])){
	$alpha = 'A';
}else{
	$alpha = mysql_real_escape_string($_GET['alpha']);
}
?>
<div align='center' id='contact'>
<h2>Liste des utilisateurs</h2>

<div>
<?php
// Liste alpha des lettres
for ($i='A';$i!='AA';$i++){
	$count = "SELECT COUNT(nom) AS nombre FROM utilisateurs WHERE nom LIKE '".$i."%'";
	$resultat = mysql_query ($count) or die (mysql_error());
	$counter = mysql_fetch_array ($resultat);
	
	echo "<a href='?alpha=$i'>$i (".$counter['nombre'].")</a> | ";
}

$persos = "SELECT*FROM utilisateurs WHERE nom LIKE '".$alpha."%' ORDER BY nom ASC";							

?>
</div>

<hr/>
<ul>
<?php
	$resultat = mysql_query ($persos) or die (mysql_error());
	while ($perso = mysql_fetch_array ($resultat)){

		echo "<li>Id : ".$perso['id']." Nom : <a href='editer_utilisateur.php?id=".$perso['id']."'>".$perso['nom']."</a> | <a href=''><img src='./../../images/site/delete.png' alt='Supprimer' style='border:0;'></a> |</li>";
	}
?>
</ul>
</div>
<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
