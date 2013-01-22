<?php
//-- Header --
$root_url = "..";
$css_files = 'medailles';
include_once($root_url."/template/header_new.php");
include_once($root_url."/event/eventFormatter.php");

/*-- Connexion basic requise --*/

ControleAcces('utilisateur',1);
$bdd_event = bdd_connect('ewo');

if (isset($_GET['id']) && is_numeric($_GET['id'])){
	$perso_id = $_GET['id'];
}elseif(isset($_SESSION['persos']['current_id'])){
	$perso_id = $_SESSION['persos']['current_id'];
}else{$perso_id = -1;}

/*-----------------------------*/
?>
<style type="text/css">
#tbody_event tr td {
	border-bottom: 1px solid #E9E6C3;
	border-right: 1px solid #E9E6C3;
	text-align: left;
}

#tbody_event td.bottom {
	border-bottom: 1px dashed #000000;
}

#tbody_event td.bottomleft {
	border-bottom: 1px dashed #000000;
	border-right: 1px dashed #000000;
}

#tbody_event td.left {
	border-right: 1px dashed #000000;
}

.mort th{
	background-color: #C3B689;
	color: #634F18;
	padding: 5px;
	padding-left:30px;
}

.mort tr{
	margin: 1px;
}
</style>

<?php

$js->addScript('events');
$js->addVariables('nomPerso',nom_perso($perso_id, false, false));

if ($perso_id==-1 || isset($_GET['world'])) { $event_perso="Liste des dernières actions dans le monde";  }
else{$event_perso='Liste des actions de '.nom_perso($perso_id)/*.' depuis les '.eventFormatter::$NB_JOUR.' derniers jours'*/;}

//affichage des liens pour afficher les differentes pages
$nbEvent = eventFormatter::getNbEvents($bdd_event, $perso_id);
$nbPage = ceil($nbEvent/eventFormatter::$LIMIT_EVENT);
if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $nbPage){
	$page = $_GET['page'];
}else{
	$page = 1;
}
$from = ($page-1)*(eventFormatter::$LIMIT_EVENT);

for($i=0; isset($_SESSION['debug_event'.$i]);$i++){
	echo "<p>Info debug $i:";
	print_r($_SESSION['debug_event'.$i]);
	echo '</p>';
	unset($_SESSION['debug_event'.$i]);
}

?>

<h2><?php
echo $event_perso;
if($perso_id >= 0 && isset($_SESSION['persos']['current_id'])){
	echo (!isset($_GET['world']))?"<br/>Visible par : ".nom_perso($_SESSION['persos']['current_id']):'';
}
?></h2>

<div align='center'>
<div class="block conteneur" style="width: 75%;">
<div id="Event" class="conteneur_titre"
	style="font-weight: bold; font-size: larger; cursor: pointer; margin-left: 30px;"><a style="color: black; text-decoration: none;" href="#">&nbsp;Ev&egrave;nement(s)&nbsp;</a></div>
<?php if(!isset($_GET['world']) && $perso_id >= 1){ ?><div id="CV" class="conteneur_titre"
	style="font-weight: bold; font-size: larger; margin-left: 180px; cursor: pointer;"
	onclick="loadContentFrom('pageCV','<?php echo $root_url; ?>/event/cv.php?mat=<?php echo $perso_id;?>');"><a style="color: black; text-decoration: none;" href="#CV">&nbsp;C.V.&nbsp;</a></div>

<div id="Profil" class="conteneur_titre"
	style="font-weight: bold; font-size: larger; margin-left: 244px; cursor: pointer;"
	onclick="loadContentFrom('pageProfil','<?php echo $root_url; ?>/event/info.php?mat=<?php echo $perso_id;?>');"><a style="color: black; text-decoration: none;" href="#Profil">&nbsp;Profil&nbsp;</a></div>	
	<?php } ?>	
<div id="pageEvent" class="conteneur_corps" style="display: block;">
<div align="center" style="padding-bottom: 10px;"><?php
if(!isset($_GET['world'])&& $perso_id >= 1){
	echo 'Page : ';
	if($page>1)
	echo '<a href="'.$root_url.'/event/liste_events.php?page='.($page-1).'&id='.$perso_id.'"><</a>&nbsp;';
	echo (($page<=0)?1:$page).' / '.(($nbPage==0)?1:$nbPage);
	if($page<$nbPage && $nbPage > 1)
	echo '&nbsp;<a href="'.$root_url.'/event/liste_events.php?page='.($page+1).'&id='.$perso_id.'">></a>';
}
?></div>
<div id="spacer"></div>
<table id="tab_classement" cellspacing="0" width="100%">
	<col width="90" />
	<col width="44" />
	<col width="88" />
	<col width="*" />
	<col width="88" />
	<col width="44" />
	<tbody id="tbody_event">
	<?php
	if ($_SESSION['persos']['inc']!=0 && $perso_id >= 0 && !isset($_GET['world'])){
		eventFormatter::printEvents($bdd_event,$perso_id, $from);
	}else{
		eventFormatter::printEvents($bdd_event, $perso_id, $from, true);
	}
	?>
	</tbody>
</table>
<!-- fin conteneur --></div>
<div id="pageCV" class="conteneur_corps" style="display: block; text-align:left; margin: 0;"></div>
<div id="pageProfil" class="conteneur_corps" style="display: block; text-align:left; margin: 0;"></div>
</div>
</div>

	<?php
	//-- Footer --
	include($root_url."/template/footer_new.php");
	//------------
	?>
