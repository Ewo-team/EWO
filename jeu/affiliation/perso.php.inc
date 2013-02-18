<?php

namespace jeu\affiliation;

/**
 * Action uniques au niveau d'un perso
 *
 * @author Benjamin Herbomez <benjamin.herbomez@gmail.com>
 */

    //Il faut être connecté et sur une bonne page
    if(!isset($_GET['mat']) || !in_array($_GET['mat'], $_SESSION['persos']['id']) ||
       !isset($_GET['a']) || !array_key_exists($_GET['a'], $pages_perso)){
        header('Location: index.php');
        die();
    }

    $id = array_keys($_SESSION['persos']['id'], $_GET['mat']);
    $id = $id[0];

    //Controll les besoins en log ou non
    if($_GET['a'] == 0 && $_SESSION['persos']['superieur'][$id] != null ||
       $_GET['a'] == 1 && $_SESSION['persos']['superieur'][$id] == null){
         header('Location: index.php');
         die();
    }

    include('perso/'.$pages_perso[$_GET['a']].'.php.inc');
?>
