<?php
/**
* Afficher les persos et leurs mat
*
* @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
* @version 1.0
*/
session_start();

    require('ref.php.inc');

    require_once($root_url.'legion/class/LegionDAO.php.inc');

    use legions\LegionDAO as LegionDAO;


    if(!isset($_GET['term']) || !isset($_GET['race']) || !is_numeric($_GET['race']))
        die();

    $q = strtolower($_GET["term"]);
    if (!$q) die();


    $sql    = LegionDAO::getInstance();
    //Selection des persos qui n'ont pas de faction
    $query_alter  = '
        SELECT
            p.id    as id,
            p.nom   as nom
        FROM `persos` p
        INNER JOIN `races` r
        ON
            r.race_id = p.race_id
        LEFT JOIN `faction_membres` m
        ON
            m.perso_id = p.id
        WHERE
            m.perso_id IS NULL  AND
            r.camp_id = '.$_GET['race'].' AND
    ';
    if(is_numeric($q))
        $query_alter .= 'p.id = '.$q;
    else
        $query_alter .= 'p.nom LIKE  "%'.stripslashes ($q).'%"';

    $stmt = $sql->query($query_alter.';');

    $items = array();
    while($entree = $sql->fetch($stmt,\PDO::FETCH_OBJ)){
        $items[$entree->nom.' ('.$entree->id.')'] = $entree->id;
    }

    $result = array();
    foreach ($items as $key=>$value) {
        array_push($result, array("id"=>$value, "label"=>$key, "value" => strip_tags($key)));
    }
    echo json_encode($result, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);

?>
