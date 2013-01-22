<?php
/**
 * Configuration de l'api
 *
 * Passe l'api en HTTPS si elle ne l'est pas.
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 0.1
 * @package api
 * @category configuration
 */
	if($_SERVER["HTTPS"] != "on") {
		 header("Location: https://" . $_URL . $_SERVER["REQUEST_URI"]);
		 exit();
	}
	$retour = api_verifconnexion($_SESSION['API']['id_utilisateur']);
?>
