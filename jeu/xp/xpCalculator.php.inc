<?php
/**
 * @abstract Gestion de l'xp
 * 
 * @author Kamule
 */

namespace jeu\xp;
use jeu\xp\Config as Config;
use jeu\xp\XpAction as Action;
use jeu\xp\XpActor as Actor;

/**
 * @abstract Classe qui va gérer le calcul d'xp pour les différentes actions et faire les MAJ
 * @author Kamule
 */
class XpCalculator{		
	public static function getXp(Actor $atq, Actor $def, Action $action){
		$facteurMort = 1;
		$amortissementEsquiveAtq = 1;
		$amortissementEsquiveDef = 1;
		$amortissementPlan = 1;
		$amortissementCopain = 1;
		
		if($action->mort)
			$facteurMort = Config::FACTEUR_MORT;
		if($action->esquive)
			$amortissementEsquive = Config::AMMORTISSEMENT_ESQUIVE;
		else
			$amortissementEsquiveDef = Config::AMMORTISSEMENT_ESQUIVE;
		//TODO : camps plan
		if($atq->getCamps() == $def->getCamps())
		
		return new XpAtqDef(0,0);	
	}
}