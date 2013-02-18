<?php

namespace jeu\affiliation;

/**
 * Classque qui va permettre de donner la liste des supérieurs via leur liste de mat
 *
 * @author Benjamin Herbomez <benjamin.herbomez@gmail.com>
 */

class Superieurs{

    public static function getSups($mats){
        //Nettoyage des données
        $mats_c = $mats;
        $mats = array();
        foreach($mats_c as $v){
            if(in_array($v, $mats) || !is_numeric($v) || $v == 0)//On ne veut ni doublon ni valeur nulle
                continue;
            $mats[] = $v;
        }

        $sql = AffiliationDAO::getInstance();
        return $sql->getSup($mats);
    }

    public static function getAvailableSup($camp){
        $sql = AffiliationDAO::getInstance();
        return $sql->getSup($camp);
    }
}
?>
