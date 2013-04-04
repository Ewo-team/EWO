<?php
//-- Header --
require_once __DIR__ . '/../../conf/master.php';

include(SERVER_ROOT . "/template/header_new.php");

include(SERVER_ROOT ."/persos/fonctions.php");
/*-- Connexion basic requise --*/
ControleAcces('admin',1);
/*-----------------------------*/
/*
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
*/
?>
<!--

<form name='option' action="editer_perso.php" method="post" style="width: 300px; text-align: left;">
  <input name="id_perso" type="text" value='' /><label for="id_perso">Id personnages</label><br>
  <input name="pseudo_perso" type="text" value='' id="pseudo_perso" /><label for="pseudo_perso">Pseudo</label><br>
  <input type="submit" value="Editer" class="bouton" />
</form>
</ul>

<hr/>-->
<link rel="stylesheet" href="../../css/tablesorter/theme.default.css" type="text/css" media="print, projection, screen" />
<link rel="stylesheet" href="../../css/tablesorter/pager/jquery.tablesorter.pager.css" type="text/css" media="print, projection, screen" />

<table id="liste_persos" class="tablesorter"> 
	<thead>
		<tr>
			<th class="sorter-false">Mat.</th>
			<th class="sorter-false">Pseudo</th>
			<th class="sorter-false">Utilisateur</th>
			<th class="sorter-false">Camps</th>
			<th class="sorter-false">Type</th>
			<th class="sorter-false">Grade & Galon</th>
			<th class="sorter-false">Special</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$persos = "SELECT p.id as id, p.nom as nom, u.id as user_id, u.nom as username, 
						c.nom as camps, r.type as type, p.grade_id as grade, p.galon_id as galon, p.titre as titre, p.nom_race as nom_race, p.pnj as pnj, p.mortel as mortel
						FROM persos p
						INNER JOIN utilisateurs u ON (u.id = p.utilisateur_id) 
						INNER JOIN races r ON (p.race_id = r.race_id AND r.grade_id = -2) 
						INNER JOIN camps c ON (r.camp_id = c.id) ORDER BY nom ASC LIMIT 20";		
			$resultat = mysql_query ($persos) or die (mysql_error());

			while ($perso = mysql_fetch_array ($resultat)){


				$grade = 'G'.$perso['grade'].'g'.$perso['galon'];
				$special = array();

				if($perso['titre'] != null) 	{ $special[] = 'Titre'; }
				if($perso['nom_race'] != null)	{ $special[] = 'Race personnalisée'; }
				if($perso['pnj'] != 0) 		{ $special[] = 'PNJ'; }
				if($perso['mortel'] != 0) 	{ $special[] = 'Mortel'; }

				echo '<tr>';
				echo "<td><a href='editer_perso.php?id=".$perso['id']."'>".$perso['id']."</td>";
				echo "<td><a href='editer_perso.php?id=".$perso['id']."'>".$perso['nom']."</td>";
				echo "<td><a href='../utilisateurs/editer_utilisateur.php?id=".$perso['user_id']."'>".$perso['username']."</td>";
				echo "<td>".$perso['camps']."</td>";
				echo "<td>".$perso['type']."</td>";
				echo "<td>".$grade."</td>";
				echo "<td>".implode(", ", $special)."</td>";
				//echo "<td>Id : ".$perso['id']." Nom : <a href='editer_perso.php?id=".$perso['id']."'>".$perso['nom']."</a> | <a href=''><img src='./../../images/site/delete.png' alt='Supprimer' style='border:0;'></a> |</li>";
				echo '</tr>';
			}	

			$js->addLib('tablesorter/jquery.tablesorter.min');
			$js->addLib('tablesorter/addons/pager/jquery.tablesorter.pager');	
			$js->addLib('tablesorter/jquery.tablesorter.widgets');		
			$js->addScript('admin/tablesorter.persos');

		?>
	</tbody>
</table>
<div class="pager">
	Page: <select class="gotoPage"></select>
	<img src="../../css/tablesorter/pager/icons/first.png" class="first" alt="First" title="First page" />
	<img src="../../css/tablesorter/pager/icons/prev.png" class="prev" alt="Prev" title="Previous page" />
	<span class="pagedisplay"></span> <!-- this can be any element, including an input -->
	<img src="../../css/tablesorter/pager/icons/next.png" class="next" alt="Next" title="Next page" />
	<img src="../../css/tablesorter/pager/icons/last.png" class="last" alt="Last" title= "Last page" />
	<select class="pagesize">
		<option selected="selected" value="10">10</option>
		<option value="20">20</option>
		<option value="30">30</option>
		<option value="40">40</option>
	</select>
</div>
<!--
<ul>
<?php
	/*$resultat = mysql_query ($persos) or die (mysql_error());
	while ($perso = mysql_fetch_array ($resultat)){

		echo "<li>Id : ".$perso['id']." Nom : <a href='editer_perso.php?id=".$perso['id']."'>".$perso['nom']."</a> | <a href=''><img src='./../../images/site/delete.png' alt='Supprimer' style='border:0;'></a> |</li>";
	}*/
?>
</ul>
-->
<hr/>

<p>Faire une raz des caracs de tous les persos et r&eacute;ini de la DLA, les plans autre que terre sont vid&eacute;s :</p> 
<form name='option' action="liste_persos.php" method="post">
  <input type="submit" value="RAZ" name="raz" class="bouton" disabled />
</form>
</div>
<?php
    //$js->addLib('jquery-ui');
    //$js->addScript('autocomplete');
 ?>
<link rel="stylesheet" href="<?php echo SERVER_URL ?>/css/pepper-grinder/jquery-ui-1.8.23.custom.css" type="text/css" media="all" />
<?php
//-- Footer --
		include(SERVER_ROOT."/template/footer_new.php");
//------------
?>
