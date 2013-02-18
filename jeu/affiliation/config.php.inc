<?php

namespace jeu\affiliation;

/**
 * Liste des pages utilisables
 *
 * @author Benjamin Herbomez <benjamin.herbomez@gmail.com>
 */
    define('PAGE_PERSOS'    , 0);
    define('PAGE_PERSO'     , 1);
    define('PAGE_ANIM'      , 2);
    define('PAGE_ADMIN'     , 3);

    define('PAGE_PERSO_AFFILIER'    , 0);
    define('PAGE_PERSO_DESAFFILIER' , 1);
    define('PAGE_PERSO_ANNULER'     , 2);

    define('PAGE_ANIM_LISTE_MEMBRES'    , 0);
    define('PAGE_ANIM_BENE_MALE'        , 1);
    define('PAGE_ANIM_RENIER'           , 2);
    define('PAGE_ANIM_TP'               , 3);
    define('PAGE_ANIM_LIST'             , 4);
    define('PAGE_ANIM_GRADE'            , 5);
    define('PAGE_ANIM_STATS'            , 6);

    define('TP_PV_COEFF', 1/3); //Coeff de perte de pvs lors du TP

    $pages = array(
        PAGE_PERSOS => 'persos',
        PAGE_PERSO  => 'perso',
        PAGE_ANIM   => 'anim',
        PAGE_ADMIN  => 'admin'
    );

    $pages_perso = array(
        PAGE_PERSO_AFFILIER     => 'affilier',
        PAGE_PERSO_DESAFFILIER  => 'desaffilier',
        PAGE_PERSO_ANNULER      => 'annuler'

    );

    $pages_anim = array(
        PAGE_ANIM_LISTE_MEMBRES => 'listeDemandes',
        PAGE_ANIM_BENE_MALE     => 'bene',
        PAGE_ANIM_RENIER        => 'renier',
        PAGE_ANIM_TP            => 'tp',
        PAGE_ANIM_LIST          => 'list',
        PAGE_ANIM_GRADE         => 'grades',
        PAGE_ANIM_STATS         => 'stats'
    );

    $pages_anim_name = array(
        PAGE_ANIM_LISTE_MEMBRES => 'Liste des demandes',
        PAGE_ANIM_BENE_MALE     => 'Bénédiction/malédictions',
        PAGE_ANIM_RENIER        => 'Renier',
        PAGE_ANIM_TP            => 'téléportation',
        PAGE_ANIM_LIST          => 'liste des affiliés',
        PAGE_ANIM_GRADE         => 'G&eacute;rer les grades',
        PAGE_ANIM_STATS         => 'statistiques'
    );


?>