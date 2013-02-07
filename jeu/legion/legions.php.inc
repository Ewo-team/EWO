<?php
/**
 * Affichage de la liste des légions
 * 
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 */

	include_once('class/LegionConfig.php.inc');
	include_once('class/ManagerFactory.php.inc');
	include_once('class/NameManager.php.inc');
	include_once('class/DemandeUtilisateur.php.inc');
	
	use legions\LegionConfig as LegionConfig;
	use legions\ManagerFactory as ManagerFactory;
	use legions\NameManager as NameManager;
	use legions\DemandeUtilisateur as DemandeUtilisateur;
	
	if(isset($_GET['l_filter']) && array_key_exists($_GET['l_filter'],LegionConfig::$typeName))
		$type = $_GET['l_filter'];
	else
		$type = LegionConfig::all;
	
	//La liste des demandes
	$demandes = new DemandeUtilisateur($_SESSION['utilisateur']['id']);
	$freePerso = array();
	foreach($_SESSION['persos']['id'] as $k => $id){
		$freePerso[$id] = $_SESSION['persos']['faction']['id'][$k] == 0;
	}
	foreach($demandes->getDemandes() as $demande){
		$freePerso[$demande->getPersoId()] = false;
	}
	
	/**
	 * Le titre de la page en fonction du filtre et la liste des filtres restants
	 */
	$txt = '<h2>'.LegionConfig::$typeName[$type].'</h2>
	<br />
	Filtre : ';
	$i = false;
	foreach(LegionConfig::$typeName as $k => $v){
		if($k == $type)
			continue;
		if($i)
			$txt .= ' | ';
		$txt .= '<a href="index.php?l_filter='.$k.'" class="legionTxt_'.LegionConfig::$typeShortName[$k].'">'.$v.'</a>';
		$i = true;
	}
	
	/**
	 * affichage des factions
	 */
	
	$factory = new ManagerFactory();
	$legions = $factory->get($type);
	
	$names = new NameManager();
	$txt .= '
	<hr class="legionHr"/><br />
	<table class="legionTable">
		<tr>
			<th width="30%">Nom</th>
			<th width="30%">Type</th>
			<th width="30%">Alignement</th>
		</tr>';
	foreach($legions->getLegions() as $legion){
		$race = LegionConfig::raceFromIdBdd($legion->getRace());
		$txt .= '
		<tr>
			<td>
				<a href="index.php?p=2&amp;id='.$legion->getId().'"
				class="legionTxt_'.LegionConfig::$typeShortName[$race].'">'.$legion->getNom().'</a>
			</td>
			<td>'.$names->getTypeName($legion->getType()).'</td>
			<td>'.$names->getAlignementName($legion->getAlignement()).'</td>';
			if(in_array($legion->getRace(),$_SESSION['utilisateur']['races'])){
				$txt .= '
			<td>
				<a href="index.php?p=2&amp;id='.$legion->getId().'"">postuler</a>';
					
				$txt .= '
			</td>';
			}
			$txt .= '
		</tr>
		';
	}
	$txt .= '
	</table>';
	
	echo $txt;
?>
