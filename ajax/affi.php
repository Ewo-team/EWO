<?php
/**
* Afficher la liste des affiliés
*
* @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
* @version 1.0
*/
require_once __DIR__ . '/../conf/master.php';

if(!isset($_SESSION['utilisateur']['id'])){
	exit;
}

    require_once(SERVER_ROOT . '/jeu/affiliation/class/AffiliationDAO.php.inc');



    if(!isset($_GET['term']) || !isset($_GET['sup']) ||
            !is_numeric($_GET['sup']) || !in_array($_GET['sup'], $_SESSION['persos']['superieur']))
        die();

    $q = strtolower($_GET["term"]);
    if (!$q) die();


    $sql    = AffiliationDAO::getInstance();
    //Selection des persos qui n'ont pas de faction
    $query_alter  = '
        SELECT
            id,
            nom
        FROM `persos`
        WHERE
            `superieur_id` = '.$_GET['sup'].' AND
    ';
    if(is_numeric($q))
        $query_alter .= 'id = '.$q;
    else
        $query_alter .= 'nom LIKE  "%'.stripslashes ($q).'%"';

    $stmt = $sql->query($query_alter.';');

    $items = array();
    while($entree = $sql->fetch($stmt,\PDO::FETCH_OBJ)){
        $items[stripcslashes($entree->nom).' ('.$entree->id.')'] = $entree->id;
    }

    $result = array();
    foreach ($items as $key=>$value) {
        array_push($result, array("id"=>$value, "label"=>$key, "value" => strip_tags($key)));
    }
    echo json_encode($result, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);

?>
