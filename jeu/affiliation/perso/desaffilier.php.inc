<?php

namespace jeu\affiliation;

/**
 * Action pour se désaffilier
 *
 * @author Benjamin Herbomez <benjamin.herbomez@gmail.com>
 */

    if($_SESSION['persos']['superieur'][$id] != null){
        $sup_id = $_SESSION['persos']['superieur'][$id];
        $sup = Superieurs::getSups(array($_SESSION['persos']['superieur'][$id])); //get current perso sup
        $sup = $sup[$sup_id];
        if($sup->pv <= 0){ //Si on peut se désaffilier
            AffiliationDAO::getInstance()->desafillier($_GET['mat'], $sup->id,$sup->camp);//modif en bdd
            $_SESSION['persos']['superieur'][$id] =  null;//modif en session
        }
    }
    header('location: index.php');
    die();