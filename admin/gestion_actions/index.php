<?php
//-- Header --
$root_url = "./../..";
include($root_url."/template/header_new.php");
include ("Actions.class.php");
include ("Effet.class.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/

require_once ("../AdminDAO.php");

echo '<link rel="stylesheet" href="../../css/tablesorter/style.css" type="text/css" media="print, projection, screen" />';

$conn = AdminDAO::getInstance();

$tableau = $conn->SelectActions();

$cercles = array(
	"Novice",
	"Feu",
	"Glace",
	"ET",
	"Soin",	
	"Effrois",
	"Paria",
	"Techno"
);

echo '<h3><a href="editer_action.php">Ajouter une nouvelle action</a></h3><table id="actions" class="tablesorter" border="0" cellpadding="0" cellspacing="1">

<thead>
	<tr>
		<th id="nom">Nom</th>
		<th id="desc">Description</th>
		<th id="cout">Cout</th>
		<th id="cercle">Cercle</th>
		<th id="niveau">Niveau</th>
		<th id="race">Races</th>
		<th id="grade">Grade</th>
		<th id="zone">Zone</th>
		<th id="cible">Cible</th>
		<th id="lanceur">Lanceur</th>
		<th id="type">Type</th>
		<th></th>
	</tr>
</thead>
<tbody>';

foreach($tableau as $ligne) {

$desc = $ligne['description'];

if(strlen($desc) > 40) {
	$desc = substr($ligne['description'],0,40).' ...';
}

$cercle_id = $ligne['cercle_id'];
$races = Actions::raceStrToArray($ligne['race']);
if(count($races) == 0) {
	$race = "aucune";
} else {
	$race = implode(", ", array_keys($races));
}

$grade = $ligne['grade'];

if($grade == -2) {
	$grade = "aucun";
}

$nom = explode("|",$ligne['nom']);

if(count($nom) > 1) {

	if($nom[0] != "") {
		$nom = $nom[0].', (...)';
	}
	elseif($nom[1] != "") {
		$nom = $nom[1].', (...)';
	}
	elseif($nom[2] != "") {
		$nom = $nom[2].', (...)';
	}
	elseif($nom[3] != "") {
		$nom = $nom[3].', (...)';
	} else {
		$nom = "Pas de nom";
	}
	
} else {
	$nom = $nom[0];
}

echo '<tr>
<td><a href="editer_action.php?id=',$ligne['id'],'">',$nom,'</a></td>
<td>',$desc,'</td>
<td>',$ligne['cout'],'</td>
<td>',$cercles[$cercle_id],'</td>
<td>',$ligne['niv'],'</td>
<td>',$race,'</td>
<td>',$grade,'</td>
<td>',$ligne['zone'],'</td>
<td>',$ligne['cible'],'</td>
<td>',$ligne['lanceur'],'</td>
<td>',$ligne['type_action'],'</td>
<td><a class="delete" href="supprime_action.php?id=',$ligne['id'],'"><img src="../../images/site/delete.png"></a></td>
</tr>';

}

?>
</tbody></table>
<div id="pager" class="pager">
	<form>
		<img src="../../css/tablesorter/first.png" class="first"/>
		<img src="../../css/tablesorter/prev.png" class="prev"/>
		<input type="text" class="pagedisplay"/>
		<img src="../../css/tablesorter/next.png" class="next"/>
		<img src="../../css/tablesorter/last.png" class="last"/>
		<select class="pagesize">
			<option selected="selected"  value="25">25</option>
			<option value="50">50</option>
			<option value="100">100</option>
		</select>
	</form>
</div>

<?php

$js->addLib('jquery.tablesorter.min');
$js->addLib('jquery.tablesorter.pager');
$js->addScript('admin/tablesorter');
        

//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>