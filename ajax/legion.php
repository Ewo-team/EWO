<?php
/**
* Afficher les persos et leurs mat
*
* @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
* @version 1.0
*/
require_once __DIR__ . '/../conf/master.php';

if(!isset($_SESSION['utilisateur']['id'])){
	exit;
}
	$root_url = '../';
    require_once(SERVER_ROOT.'/jeu/legion/class/LegionDAO.php.inc');

    use legions\LegionDAO as LegionDAO;

    $sql = LegionDAO::getInstance();

    $query_alter = '
        SELECT  id, nom, race, description, type, alignement
        FROM `factions`
        WHERE
            nature="LEGION" AND
            nom LIKE "%'.stripslashes ($_GET['term']).'%";
    ';
    $stmt = $sql->query($query_alter);

    $r = array();
    while($e = $sql->fetch($stmt,\PDO::FETCH_OBJ)){
        array_push($r,
            array(
                "id"    => $e->id,
                "label" => $e->nom,
                "value" => array(
                    'id'    => $e->id,
                    'nom'   => $e->nom,
                    'race'  => $e->race,
                    'descr' => $e->description,
                    'type'  => $e->type,
                    'align' => $e->alignement
                )
            )
        );
    }

    echo json_encode($r, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
?>
