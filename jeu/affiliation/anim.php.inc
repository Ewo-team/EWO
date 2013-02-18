<?php

namespace jeu\affiliation;

/**
 * Point d'entrée pour toute la partie de gestion des affiliés
 *
 * @author Benjamin Herbomez <benjamin.herbomez@gmail.com>
 */

//vérif des droits d'accès
if(!isset($_GET['mat']) ||!array_key_exists($_GET['mat'], $persos_sups)){
    header('location : index.php');
    die();
}


$a = 0;
if(isset($_GET['a']))
    $a = $_GET['a'];

//Il faut être connecté et sur une bonne page
if(!array_key_exists($a, $pages_anim)){
    header('Location: index.php');
    die('ko');
}

$id = array_keys($_SESSION['persos']['id'], $_GET['mat']);
$id = $id[0];

$first = true;
echo '<div id="anim_tools">';
foreach($pages_anim_name as $page_anime_id => $page_anim_name){
    if($first)
        $first  = false;
    else
        echo ' | ';
    if($a==$page_anime_id)
        echo '<strong>',$page_anim_name,'</strong>';
    else
        echo '<a href="index.php?mat=',$_GET['mat'],'&amp;p=',$_GET['p'],'&amp;a=',$page_anime_id,'">',$page_anim_name,'</a>';
}
echo '</div><hr class="affiHr"/>';
include('anim/'.$pages_anim[$a].'.php.inc');

?>