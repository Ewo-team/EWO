<?php 
//-- Header --
$root_url = "./../..";
include($root_url."/template/header_new.php");
include($root_url."/persos/fonctions.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('anim;admin',1);
$admin = false;

if(ControleAcces('admin',0)) {
    $admin = true;
}
/*-----------------------------*/

require_once ("../AdminDAO.php");

echo '<link rel="stylesheet" href="../../css/tablesorter/style.css" type="text/css" media="print, projection, screen" />';

$conn = AdminDAO::getInstance();

if(isset($_GET['t'])) {
	$page_type = $_GET['t'];
}

if($page_type == 'ia') {

	$tableau = $conn->SelectIa();

	echo '<h3>Liste des IA</h3>
	<a href=\'liste_pnj.php\'>pnj joué</a> | <a href=\'#\'>IA</a>
	<table id="actions" class="tablesorter" border="0" cellpadding="0" cellspacing="1">

	<thead>
		<tr>
			<th id="mat">Mat</th>
			<th id="nom">Nom</th>
			<th id="dla">Tour</th>
			<th id="position">Position</th>
			<th id="espece">Espèce</th>
			<th id="dna">Code génétique (cliquer pour voir)</th>
		</tr>
	</thead>
	<tbody>';	

	foreach($tableau as $ligne) {
		$mort = 0;
		$x			= $ligne['pos_x'];
		$y			= $ligne['pos_y'];
		$plan		= $ligne['cartes'];
		
		if (isset($x) && isset($y)){
			$position = 'X: '.$x.' <br /> Y: '.$y.' ('.$plan.')';
		}
		else{
			$position= 'Désincarné';
		}	

		$lien_mat = ($admin) ? '<a href="editer_perso.php?id='.$ligne['id'].'">'.$ligne['id'].'</a>' : $ligne['id'];
		
		$dna = var_export(unserialize($ligne['dna']), true);
		
		
		echo '<tr>
		<td>',$lien_mat,'</a></td>
		<td>',$ligne['nom'],'</td>
		<td>',$ligne['date_tour'],'</td>
		<td>',$position,'</td>
		<td>',$ligne['type'],'</td>
		<td class="dialog">...<span class="value ui-helper-hidden"><pre>',$dna,'</pre></span></td>				
		</tr>';
		
	}	
	
} else {



	$tableau = $conn->SelectPnj();

	echo '<h3>Liste des PNJ</h3>
	<a href=\'#\'>pnj joué</a> | <a href=\'liste_pnj.php?t=ia\'>IA</a>
	<table id="actions" class="tablesorter" border="0" cellpadding="0" cellspacing="1">

	<thead>
		<tr>
			<th id="mat">Mat</th>
			<th id="nom">Nom</th>
			<th id="user">Propriétaire</th>
			<th id="position">Position</th>
			<th id="survie">Survivance</th>
			<th id="activite">Activitée</th>
		</tr>
	</thead>
	<tbody>';

	foreach($tableau as $ligne) {
		$mort = 0;
		$x			= $ligne['pos_x'];
		$y			= $ligne['pos_y'];
		$plan		= $ligne['cartes'];
		$mortel		= $ligne['mortel'];

		$caracs		= calcul_caracs($ligne['id']);
		
		$pv_max		= carac_max ($caracs['race_id'], $caracs['grade_id'], 'pv', $caracs['niv_pv'], $ligne['id']);	
		
		if (isset($x) && isset($y)){
			$position = 'X: '.$x.' <br /> Y: '.$y.' ('.$plan.')';
		}
		else{
			$mort = 1;
			if($mortel == 1) {
				$position = 'Mort';
			} else {
				$position= 'Désincarné';
			}
		}


		$datetour = new DateTime($ligne['date_tour']);
		$jour = new DateTime('now');
		$interval = $datetour->diff($jour);
		$activite = $interval->format('%R%a jour(s)');


		if(!$mort) {
			$pourcent_pv = round((($caracs['pv'] / $pv_max) * 100),2) + '%';
		} else {
			$pourcent_pv = ' - ';
		}
		
		$lien_mat = ($admin) ? '<a href="editer_perso.php?id='.$ligne['id'].'">'.$ligne['id'].'</a>' : $ligne['id'];
		
		echo '<tr>
		<td>',$lien_mat,'</a></td>
		<td>',$ligne['nom'],'</td>
		<td>',$ligne['user'],'</td>
		<td>',$position,'</td>
		<td>',$pourcent_pv,'</td>
		<td>',$activite,'</td>
		</tr>';

	}
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
$js->addScript('admin/pnj');
        

//-- Footer --
include($root_url."/template/footer_new.php");
//------------

?>