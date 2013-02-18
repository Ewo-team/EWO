<?php

namespace jeu\legion;

/**
 * Quelques fonction utiles pour la gestion des traitres
 *
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 */

use legions\LegionDAO as LegionDAO;

function recup_pot_traitre($tueur_id, $depuis="") {

    $sql = LegionDAO::getInstance();
    $query = 'SELECT factions.id AS id
			FROM factions
			LEFT JOIN persos ON persos.id = '.$tueur_id.'
				WHERE factions.race=persos.race_id AND factions.type=4';
    $stmt = $sql->query($query);

    $faction_id = '';
    while($result = $sql->fetch($stmt,\PDO::FETCH_OBJ)){
        if ($faction_id == '')
            $faction_id = $result->id;
        else
            $faction_id .= ' OR p1.faction_id = ' . $result->id;
    }

    if ($depuis == "")
        $depuis = '0000-00-00 00:00:00';

   $sql = 'SELECT morgue.mat_victime, morgue.id_perso, morgue.date, morgue.nom_victime
			FROM morgue
                            JOIN persos p1
                                ON p1.id = morgue.id_perso
                            JOIN persos p2
                                ON p2.id = morgue.mat_victime AND p2.grade_id != -3
                            JOIN races r1
                                ON r1.id = p1.race_id
                            JOIN races r2
                                ON r2.id = p2.race_id
                        WHERE (((p1.faction_id = '.$faction_id.') OR p1.grade_id >= 3 AND p1.galon_id >= 2) AND
                            (morgue.date>="'.$depuis.'") AND
                            r1.camp_id=r2.camp_id)
                        GROUP BY morgue.mat_victime';
   
    $resultat = mysql_query($sql) or die(mysql_error());
    return $resultat;
}

function nvTraitre($perso_id) {

    $sql    = LegionDAO::getInstance();
    $query  = '
        UPDATE `persos` p SET
            p.grade_id = -3
            WHERE p.id = "'.$perso_id.'"';

    $stmt = $sql->exec($query);
}

?>
