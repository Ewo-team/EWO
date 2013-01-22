<?php
/**
 *  Point d'entrée
 *
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version
 * @package affiliation
 */
    //-- Header --
    $root_url = "..";
    include($root_url."/template/header_new.php");

    //Il faut être connecté
    ControleAcces('utilisateur',1);
    require_once('config.php.inc');

    /**
     * Selection de la pae actuelle
     */
    if(isset($_GET['p']) && array_key_exists($_GET['p'],$pages)){
        $p = $pages[$_GET['p']];
    }
    else
        $p = $pages[0];

    $persos_sups = array(); //tableau qui reprend les indices de persos qui peuvent être supérieur
    $links = '';

    //Détection du nombre de demandes
    $demandes = array();
    $utilisateur_id = $_SESSION['utilisateur']['id'];
    $query = 'SELECT COUNT(w.vassal) AS nb, p.id as id
                            FROM persos p
                            JOIN wait_affil w
                                ON w.superieur = p.id
                            WHERE p.utilisateur_id = '.$utilisateur_id.'
                            GROUP BY p.id';
    $query = mysql_query ($query);
    while($demande = mysql_fetch_object($query)){
        $demandes[$demande->id] = $demande->nb;
    }
    $first = true;
    foreach($_SESSION['persos']['grade'] as $k => $v){
        if($v == 5 && $_SESSION['persos']['galon'][$k] > 0 || $v == 4 && $_SESSION['persos']['galon'][$k] == 4){
            $perso_id = $_SESSION['persos']['id'][$k];
            $persos_sups[$perso_id] = $k;
            if($first)
                $first = false;
            else
                $links .= ' | ';

            $txt = 'Affiliés de '.$_SESSION['persos']['nom'][$k];
            if(array_key_exists($perso_id, $demandes) && $demandes[$perso_id] > 0){
                $txt .= ' <span style="color:#27f127;">('.$demandes[$perso_id].')</span>';
            }

            if(isset($_GET['mat']) && isset($_GET['p']) && $_GET['p'] == PAGE_ANIM && $_GET['mat'] == $perso_id)
                $links .= '<strong>'.$txt.'</strong>';
            else
                $links .= '<a href="index.php?mat='.$perso_id.'&amp;p=2">'.$txt.'</a> ';
       }
    }
    if($links != ''){
        if(isset($_GET['p']) && $_GET['p'] == PAGE_ANIM)
            $links  .= ' | <a href="index.php">retour à l\'index</a>';
        $links  = '<div id="affiBoard">'.$links.'</div><hr class="affiHr"/>';
    }
    echo '
	<link rel="stylesheet" href="',$root_url,'/affiliation/style.css" type="text/css" />
        '.$links.'
        <div id="legion">
    ';
    //Inclusion de la bonne page
    require($p.'.php.inc');
    echo '
        </div>
        ';

    //-- Footer --
    include($root_url."/template/footer_new.php");
    //------------
?>