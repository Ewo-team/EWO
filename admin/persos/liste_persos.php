<?php
//-- Header --
$root_url = "./../..";
include($root_url."/template/header_new.php");
include($root_url."/persos/fonctions.php");
/*-- Connexion basic requise --*/
ControleAcces('admin',1);
/*-----------------------------*/

if(isset($_POST['raz']) && $_POST['raz']=="RAZ"){
raz_all();
}

if (!isset($_GET['alpha'])){
	$alpha = 'A';
}else{
	$alpha = mysql_real_escape_string($_GET['alpha']);
}
?>
<div align='center' id='contact'>
<h2>Liste des personnages</h2>
<?php
// Liste alpha des lettres
for ($i='A';$i!='AA';$i++){
	$count = "SELECT COUNT(nom) AS nombre FROM persos WHERE nom LIKE '".$i."%'";
	$resultat = mysql_query ($count) or die (mysql_error());
	$counter = mysql_fetch_array ($resultat);
	
	echo "<a href='?alpha=$i'>$i (".$counter['nombre'].")</a> | ";
}

$persos = "SELECT*FROM persos WHERE nom LIKE '".$alpha."%' ORDER BY nom ASC";							

?>
<hr/>

<form name='option' action="editer_perso.php" method="post" style="width: 300px; text-align: left;">
  <input name="id_perso" type="text" value='' /><label for="id_perso">Id personnages</label><br>
  <input name="pseudo_perso" type="text" value='' id="pseudo_perso" /><label for="pseudo_perso">Pseudo</label><br>
  <input type="submit" value="Editer" class="bouton" />
</form>
</ul>

<hr/>
<ul>
<?php
	$resultat = mysql_query ($persos) or die (mysql_error());
	while ($perso = mysql_fetch_array ($resultat)){

		echo "<li>Id : ".$perso['id']." Nom : <a href='editer_perso.php?id=".$perso['id']."'>".$perso['nom']."</a> | <a href=''><img src='./../../images/site/delete.png' alt='Supprimer' style='border:0;'></a> |</li>";
	}
?>
</ul>

<hr/>

<p>Faire une raz des caracs de tous les persos et r&eacute;ini de la DLA, les plans autre que terre sont vid&eacute;s :</p> 
<form name='option' action="liste_persos.php" method="post">
  <input type="submit" value="RAZ" name="raz" class="bouton" />
</form>
</div>
<?php
    $js->addLib('jquery-ui');
    $js->addScript('autocomplete');
 ?>
<link rel="stylesheet" href="<?php echo $root_url ?>/css/pepper-grinder/jquery-ui-1.8.23.custom.css" type="text/css" media="all" />
<?php
//-- Footer --
		include($root_url."/template/footer_new.php");
//------------
?>
