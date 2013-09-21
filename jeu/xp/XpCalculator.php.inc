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
		$facteurMortAtq = 1;
		$facteurMortDef = 0;
		$amortissementEsquiveAtq = 1;
		$amortissementEsquiveDef = 1;
		$amortissementPlan = 1;
		$amortissementCopain = 1;
		
		//calcul des amortissements
		if($action->mort){
			$facteurMortAtq = Config::FACTEUR_MORT;
			$facteurMortDef = Config::FACTEUR_MORT;
		}
		if($action->esquive)
			$amortissementEsquiveAtq = Config::AMMORTISSEMENT_ESQUIVE;
		else
			$amortissementEsquiveDef = Config::AMMORTISSEMENT_ESQUIVE;
		if($atq->getCamps() == $action->planCamps){
			if(!$atq->getLegionType() == Actor::LEGION_DEFENSE)
				$amortissementPlan = Config::AMMORTISSEMENT_PLAN;
		}
		else if($atq->getCamps() == $def->getCamps() && !$atq->getLegionType() == Actor::LEGION_JUSTICE)
			$amortissementCopain = Config::AMMORTISSEMENT_COPAIN;
		
		$ecartRang = $atq->getRang() - $def->getRang();

		$xpAtq = max(1, (Config::XP_BASE_LANCEUR - $ecartRang)*$facteurMortAtq*$amortissementEsquiveAtq*
				$amortissementPlan * $amortissementCopain* $atq->getRatioPA());
		$xpDef = max(1, (Config::XP_BASE_RECEVEUR + $ecartRang) * $amortissementEsquiveDef) + ($ecartRang - 3.5)*$facteurMortDef;
		
		return new XpAtqDef(XpCalculator::ajustXp($xpAtq),
				XpCalculator::ajustXp($xpDef)
		);	
	}
	
	/**
	 * Permet d'ajouter l'xp lorsque le compte n'est pas rond (la virgurle est retranscrite par une probabilité d'avoir +1/-1 xp
	 * @param unknown $xp
	 * @return number
	 */
	public static function ajustXp($xp){
		$a = $xp == -4.05;
		$xp = round($xp, 1);
		if($xp && floor($xp) != $xp){
			if($xp > 0){
 				$v = 10*($xp - floor($xp));
				$xp = floor($xp);
			}
			else{
				$v = -10*($xp - ceil($xp));
				$xp = ceil($xp);
			}
			$r = mt_rand(0, 9);
			if($r <= $v){
				if($xp > 0)
					$xp +=  1;
				else
					$xp -= 1;
			}
		}
		return $xp;
	}
}