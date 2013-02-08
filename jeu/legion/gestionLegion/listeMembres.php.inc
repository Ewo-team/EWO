<?php
/**
 * Gestion des légions en interne : voir la liste des membres
 *
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 */
include_once('class/LegionConfig.php.inc');
include_once('class/ManagerFactory.php.inc');
include_once('class/DroitManager.php.inc');
include_once('class/LegionDAO.php.inc');

include_once($root_url.'persos/event/eventManager.php');

use legions\ManagerFactory  as ManagerFactory;
use legions\LegionConfig    as LegionConfig;
use legions\DroitManager    as DroitManager;
use legions\LegionDroits    as LegionDroits;
use legions\LegionDAO       as LegionDAO;


$factory 	= new ManagerFactory();
$legions	= $factory->get(LegionConfig::$bddId[$_SESSION['persos']['camp'][$id]]);
$legion 	= $legions->getLegions($_SESSION['persos']['faction']['id'][$id]);

$droitManager 	= new DroitManager($_SESSION['persos']['faction']['droits'][$id]);

$droits         = LegionDroits::droitsFromPersoId($id);

//Génération de la liste des grades attribuables
$attr = array();

foreach($legion->getListGrades() as $g){
	$d = $g->getDroitsArray();
	$i = $g->getGrade_id();
	if(
			$d[0] == 1 && $i != 1 && $droits->canDo(LegionDroits::GERER_CHEF) ||
			$d[0] == 1 && $i == 1 && $_SESSION['persos']['faction']['grade'][$id] == 1 ||
			$d[0] == 0 && $d[1] == 1 && $droits->canDo(LegionDroits::GERER_BRAS_DROIT) ||
			$d[0] == 0 && $d[1] == 0 && $droits->canDo(LegionDroits::GERER_MEMBRE)
	){
		$attr[$i] = $g->getNom();
	}
}

$js->addScript('legion/listSize');
$js->addScript('legion/listeMembres');


echo '
		<div id="t"></div>';

echo '
		<h3>Liste des membres</h3>
		<ul id="listeMembres">';
//Liste des persos
foreach($legion->getListMembres() as $m){
	//Virer un membre
	if(isset($_POST['matDelMembre']) && is_numeric($_POST['matDelMembre'])
			&& $_POST['matDelMembre'] == $m['id'] && ($m['grade_id'] == null || array_key_exists($m['grade_id'], $attr))){//A virer et possible de le virer

		$sql = LegionDAO::getInstance();
		$query_alter = '
				DELETE FROM `faction_membres`
				WHERE `perso_id` =  :id;';
		$stmt = $sql->prepare($query_alter);
		$stmt->execute(array(':id' => $_POST['matDelMembre']));

		//On bal
		$expediteur     = $_SESSION['persos']['id'][$id];
		$mat            = $m['id'];
		$titre          = '['.$legion->getNom().']';
		$corps          = 'Bonjour,

				Vous avez été viré de la légion '.$legion->getNom();
		sendBal($expediteur, $mat,$titre, $corps);

		//event
		$eventManager = new EventManager();
		$event = $eventManager->createEvent('legion');
		$event->setSource($mat, 'perso');
		$event->setState(1); //virer
		$event->setAffected($legion->getId(), 'legion');

		continue;//On n'affiche pas
	}
	if($m['x'] != null)
		$pos = 'pos : '.$m['x'].'/'.$m['y'].'/'.$m['z'];
	else
		$pos = 'd&eacute;sincarn&eacute;';
	echo '
			<li>'.$m['nom'].' ('.$m['id'].') '.$pos;
	//Si on peut modifier le grade
	if($m['grade_id'] == null || array_key_exists($m['grade_id'], $attr)){
		echo '
				<select onchange="updateGrade(',$_GET['mat'],',',$m['id'],',this.value);">
						';
		foreach($attr as $k => $v){
			$c = '';
			if($k == $m['grade_id'])
				$c = 'selected="selected"';
			echo '<option value=',$k,' ',$c,'>',$v,'</option>
					';
		}
		echo'
				</select>
				<input type="button" value="Virer" onclick="virer(',$m['id'],',\'',$m['nom'],'\');"/>';
	}
	echo '</li>
			';
}
echo '
		</ul>';

if( $droits->canDo(LegionDroits::GERER_MEMBRE) ||
		$droits->canDo(LegionDroits::GERER_BRAS_DROIT) ||
		$droits->canDo(LegionDroits::GERER_CHEF))
	echo '
			<div id="confirmDelMembre" style="display:none;background-color: red;color:white;width:300px;margin:auto;text-align:center;">
			<form method="post" action="index.php?p=',$_GET['p'],'&amp;mat=',$_GET['mat'],'">
					<strong>
					Virer <span id="nomDelMembre"></span> de votre légion :
					</strong><br />
					<input type="hidden" name="matDelMembre" id="matDelMembre" />
					<input type="submit" name="delMembre" value="Confirmer" />
					<input type="button" id="cancelMembre" value="Annuler" />
					</form>
					</div>';
?>
