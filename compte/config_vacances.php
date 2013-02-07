<?php

namespace compte;

/**
 * Compte, define vacance
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package compte
 */
 
/**#@+
 * Constants
 */
/**
 * Delai entre la demande et le retour de vacances (en heures)
 */
	define('VACANCES_DELAI_RETOUR', 24);
	
/**
 * Delai entre la demande et le départ en vacances (en heures)
 */	
	define('VACANCES_DELAI_DEPART', 48);
	
/**
 * Durée maximum des vacances en jours
 */	
	define('VACANCES_DELAI_MAX', 60);
	
/**
 * Gain d'xp par jour de vacance
 */	
	define('VACANCES_GAIN_XP', 1.5);
	
?>
