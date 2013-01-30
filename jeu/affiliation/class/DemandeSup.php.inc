<?php
/**
 * Gestion des demandes pour un même supérieur
 * @author Benjamin Herbomez <benjamin.herbomez@gmail.com>
 */

require('DemandeManager.php.inc');

/**
 *
 */
class DemandeSup extends DemandeManager{
    protected function loadDemandes($id){
        $query = '
            SELECT
                    wf.vassal       as perso,
                    p1.nom          as persoNom,
                    wf.superieur    as sup,
                    p2.nom          as supNom
            FROM `wait_affil` wf
            INNER JOIN `persos` p1
            ON
                p1.id = wf.vassal
            INNER JOIN `persos` p2
            ON
                p2.id = wf.superieur
            WHERE
                wf.superieur = :id;
            ';
        $stmt = $this->sql->prepare($query);
        $stmt->execute(array(':id' => $id));
        while($entree = $this->sql->fetch($stmt,\PDO::FETCH_OBJ)){
            $this->demandes[] = new Demande($entree->perso, $entree->persoNom,$entree->sup, $entree->supNom);
        }
    }
}
?>
