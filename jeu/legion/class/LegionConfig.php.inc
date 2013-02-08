<?php
/**
 * Config, s'il faut changer quelque chose c'est ici
 *
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 * @package legions
 */

namespace legions;
global $root_url;
require_once($root_url.'jeu/legion/class/LegionDAO.php.inc');

	class LegionConfig{
		//Les différents camps
		const all 		= 0;
		const anges		= 1;
		const demons 	= 2;
		const humains 	= 3;

		const typeApcName		= 'legion.nameManager.type';
		const alignementApcName	= 'legion.nameManager.alignement';

		//Lien entre un camp et son entrée dans APC
		static $apcId = array(
			LegionConfig::all 		=> 'legion.managerFactory.type.all',
			LegionConfig::anges 	=> 'legion.managerFactory.type.anges',
			LegionConfig::demons 	=> 'legion.managerFactory.type.demons',
			LegionConfig::humains 	=> 'legion.managerFactory.type.humains'
		);

		//Lien entre un camp et son entrée dans la bdd, correspond au camp_id
		static $bddId = array(
			3	=> LegionConfig::anges,
			4	=> LegionConfig::demons,
			1	=> LegionConfig::humains
		);

		static $typeName = array(
			LegionConfig::all 		=> 'Toutes les l&eacute;gions',
			LegionConfig::anges 	=> 'L&eacute;gions ang&eacute;liques',
			LegionConfig::humains 	=> 'L&eacute;gions  humaines',
			LegionConfig::demons 	=> 'L&eacute;gions d&eacute;moniaques'
		);

		static $typeShortName = array(
			LegionConfig::all 		=> 'all',
			LegionConfig::anges 	=> 'ange',
			LegionConfig::humains 	=> 'humain',
			LegionConfig::demons 	=> 'demon'
		);

		static $typeAdjName = array(
			LegionConfig::anges 	=> 'ang&eacute;lique',
			LegionConfig::humains 	=> 'humaine',
			LegionConfig::demons 	=> 'd&eacute;monique'
		);

		/**
		 * raceFromIdBdd
		 *
		 * Obtenir un id de race depuis celui de la bdd.
		 * Permet de délier le code des valeurs en BDD (au cas où)
		 */
		static function raceFromIdBdd($race){
			/*if(array_search($race,LegionConfig::$bddId)){
				$r = array_keys(LegionConfig::$bddId,$race);
				return $r[0];
			}*/
			return LegionConfig::$bddId[$race];
		}

		/**
		 * loadUtilisateurRace
		 *
		 * Permet de charger en mémoire les races que possède l'utilisateur
		 */
		static function loadUtilisateurRace(){
			if(isset($_SESSION['utilisateur']['races']) && is_array($_SESSION['utilisateur']['races']))
				return;
			$_SESSION['utilisateur']['races'] = array();

			foreach($_SESSION['persos']['camp'] as $k => $v)
				if(!in_array($v,$_SESSION['utilisateur']['races']))
					$_SESSION['utilisateur']['races'][] = $v;


		}

	}


LegionConfig::loadUtilisateurRace();

unset($_SESSION['persos']['id'][0]);

if(!function_exists('apc_exists')){
	function apc_exists($keys){
		$r;
		apc_fetch($keys,$r);
		return $r;
	}
}
?>
