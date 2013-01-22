<?php
/**
 * Deconnexion de l'api d'EWO
 *
 * 
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 0.1
 * @package api
 */
session_start();
session_destroy();
echo json_encode(array('statut'=>'logout'));
?>
