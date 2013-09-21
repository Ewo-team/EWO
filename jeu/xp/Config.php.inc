<?php
/**
 * Configuration de l'xp
 * @author Kamule
 */

namespace jeu\xp;

class Config{
	const XP_BASE_LANCEUR	= 7; /* xp de base pour une action réussie à rang égale */
	const XP_BASE_RECEVEUR	= 3; /* xp de base pour une action esquivée à rang égale */
	
	const AMMORTISSEMENT_ESQUIVE 	= 0.4; /* ammortissement du gain dans le cas où l'attaque est esquivée */
	const AMMORTISSEMENT_PLAN 		= 0.75; /* Cas de frappe dans son propre plan */
	const AMMORTISSEMENT_COPAIN		= 0.75; /* Cas de frappe sur perso du même camp */
	const FACTEUR_MORT				= 1.5; /* Non c'est pas quand t'as un mec en short avec un vélo jaune
											  qui répend ses entrailles sur le trotoir
											  C'est le multiplicateur de gain lorsqu'il y a kill  
											*/
	const ECART_AUTRES_ACTION		= 2; /* ecart de rang fixe pour les actions sorts objets, sprint, ...
										  	=> permet d'avoir un gain fixe sans avoir de if merdique dans les formules 
										 */
}