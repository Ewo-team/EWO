<?php

/**
 *
 * @author Benjamin Herbomez <benjamin.herbomez@gmail.com>
 */

    require_once($root_url.'/affiliation/class/DemandePerso.php.inc');

    $demandes   = new DemandePerso($_GET['mat']);
    $demande    = ($demandes->getDemandes());
    if(count($demande) > 0){
        $demande    = $demande[0];
        $demandes->supDemande($demande->getSupId(), $demande->getPersoId());
    }

    header('location: index.php');
    die();
