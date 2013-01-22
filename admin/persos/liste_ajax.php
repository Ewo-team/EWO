<?php
//-- Header --
$root_url = "./../..";

include($root_url."/conf/master.php");
//include($root_url."/persos/fonctions.php");
include($root_url."/jeu/fonctions.php");

require_once ($root_url."/admin/AdminDAO.php");


/*-- Connexion basic requise --*/
ControleAcces('admin',0);

$requete = @$_GET['term'];

if(isset($requete)) {
    $bdd = AdminDAO::getInstance();
    
    $liste = $bdd->SelectPersosFromString($requete);
    
    //print_r($liste);
    
    echo '[ ' . implode(", ", $liste) . ' ]';

}
