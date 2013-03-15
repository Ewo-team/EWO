<?php

namespace jeu\affiliation;

/**
 * DAO pour les affiliations
 *
 * @author Benjamin Herbomez <benjamin.herbomez@gmail.com>
 */

use \conf\ConnecteurDAO as ConnecteurDAO;
use \messagerie\messagerieDAO as messagerieDAO;
use \persos\eventManager\eventManager as eventManager;

    require_once(SERVER_ROOT.'/conf/grille_gainsxp.php');

    class AffiliationDAO extends ConnecteurDAO {

        public function getSup($params){
            if(is_array($params))
                return $this->getSupFromMats($params);
            return $this->getSupFromCamp($params);
        }

        /**
         * Donne la liste des supérieurs via une liste de matricules
         *
         * @param type $mats
         * @return array
         */
        protected function getSupFromMats($mats){
            $r = array();
            $query = '';
            foreach ($mats as $k => $v){
                if($query != '')
                    $query .= ' OR ';
                $query .= '`id` = ?';
            }
            if($query == '') //Il n'y a aucun supérieur
                return $r;

            $query = '
                SELECT
                    p.id        as id,
                    p.nom       as nom,
                    p.grade_id  as grade_id,
                    p.galon_id  as galon_id,
                    p.sexe      as sexe,
                    c.pv        as pv,
                    r.camp_id   as camp

                FROM `persos` p
                INNER JOIN
                    `caracs` c
                    ON c.perso_id = p.id
                INNER JOIN
                    `races` r
                    ON  r.race_id = p.race_id AND r.grade_id = p.grade_id;
                WHERE '.$query;
            $stmt = $this->prepare($query);
            $stmt->execute($mats);
            foreach ($stmt->fetchAll(\PDO::FETCH_OBJ) as $v){
                $r[$v->id] = $v;
            }
            return $r;
        }

        /**
         * Donne la liste des supérieurs potentiel en fonction du camp
         *
         * @param type $camp
         * @return array
         */
        protected function getSupFromCamp($camp){
            $r = array();
            if ($camp == 1)
                $query = '(r.camp_id = 3 || r.camp_id = 4)';
            else if ($camp == 3 || $camp == 4 )
                $query = 'r.camp_id = '.$camp;
            else
                return $r;

            $query = '
                SELECT
                    p.id                as id,
                    p.nom               as nom,
                    p.grade_id          as grade_id,
                    p.galon_id          as galon_id,
                    p.race_id           as race_id,
                    c.pv                as pv,
                    c.niv_pv            as niv_pv,
                    c.niv_recup_pv      as niv_recup_pv,
                    c.niv_mouv          as niv_mouv,
                    c.niv               as niv,
                    c.niv_pa            as niv_pa,
                    c.niv_des           as niv_des,
                    c.niv_force         as niv_force,
                    c.niv_perception    as niv_perception,
                    p.description_affil as descr,
                    p.sexe              as sexe,
                    r.camp_id           as camp
                FROM `persos` p
                INNER JOIN
                    `caracs` c
                    ON c.perso_id = p.id
                INNER JOIN
                    `races` r
                    ON r.race_id = p.race_id AND r.grade_id = p.grade_id
                WHERE
                    (p.grade_id = 5 AND p.galon_id >= 1 || p.grade_id = 4 AND p.galon_id = 4) AND
                    '.$query;
            $stmt = $this->prepare($query);
            $stmt->execute();
            foreach ($stmt->fetchAll(\PDO::FETCH_OBJ) as $v)
                $r[$v->id] = $v;
            return $r;
        }

        /**
         * Changer le supérieur d'un perso
         *
         * @param id $perso_id
         * @param id $sup_id
         * @param id $camp
         */
        public function setSup($perso_id, $sup_id, $camp){
            $query = '
                UPDATE `persos`
                SET `superieur_id`= :supId
                WHERE id = :persoId';
            $stmt = $this->prepare($query);
            $stmt->execute(array(':persoId'=>$perso_id, ':supId'=>$sup_id));

            BonusAffiManager::process($camp);
        }

        /**
         * Supprime le supérieur d'un perso
         *
         * @param id $perso_id
         * @param id $camp
         */
        protected function removeSup($perso_id, $sup_id, $camp, $type){
            $this->setSup($perso_id, null, $camp);
            $this->delSuicidePx($perso_id);
            $this->desincarnPerso($perso_id);

            //Ajout de l'event
            $eventManager = new EventManager();
                $event = $eventManager->createEvent('affilia');
                $event->setSource($sup_id, 'perso');
                $event->setState($type);
                $event->setAffected($perso_id, 'perso');

        }

        public function delSuicidePx($perso_id){
            global $gain;
            $query = 'SELECT nb_suicide AS nb FROM persos WHERE id=:id';
            $stmt = $this->prepare($query);
            $stmt->execute(array(':id' => $perso_id));
            $nb = $this->fetch($stmt, \PDO::FETCH_ASSOC);
            $nb_suicide = $nb['nb'];

            $perte = $gain['suicide']['val']  + ($gain['suicide']['attaque'] * $nb_suicide );
            $perte *= -1;

            $query = 'UPDATE `caracs`
                SET `px` = `px` - :perte, `pi` = `pi` - :perte, `pv` = 0
                WHERE `perso_id` = :id';
            $stmt = $this->prepare($query);
            $stmt->execute(array(
                ':id' => $perso_id,
                ':perte' => $perte));

            $nb_suicide++;
            $query = 'UPDATE persos SET `nb_suicide`=:nb WHERE id=:id';
            $stmt = $this->prepare($query);
            $stmt->execute(array(
                ':id' => $perso_id,
                ':nb' => $nb_suicide));

            return $perte;
        }

        public function desincarnPerso($perso_id){
            $query = '
                DELETE FROM `damier_persos`
                    WHERE perso_id=:id;
                DELETE FROM `caracs_alter_plan`
                    WHERE perso_id=:id;
                DELETE FROM `caracs_alter_mag`
                    WHERE perso_id=:id AND nb_tour != -1;';
            $stmt = $this->prepare($query);
            $stmt->execute(array(
                ':id' => $perso_id));

            $query = '
               INSERT INTO `caracs_alter_plan` (`perso_id`) VALUES (:id);';
            $stmt = $this->prepare($query);
            $stmt->execute(array(
                ':id' => $perso_id));
        }

        public function desafillier($perso_id, $sup_id, $camp){
            $this->removeSup($perso_id, $sup_id, $camp, affilia::AFFI_DESAFFILIER);
        }

        public function renier($sup_id, $perso_id, $camp){
            $this->removeSup($perso_id, $sup_id, $camp, affilia::AFFI_RENIER);
        }

        /**
         * Ajout une demande d'affiliation
         *
         * @warning il n'y a aucune vérification sur l'unicité, elle est à faire avant (ou via la bdd)
         *
         * @param id $sup_id
         * @param id $affi_id
         */
        public function addAffiliationQuery($sup_id, $affi_id){
            $query = '
                INSERT INTO `wait_affil` (`superieur`,`vassal`)
                VALUES (:supId, :vassalId)
            ';
            $stmt = $this->prepare($query);
            $stmt->execute(array(':supId'=>$sup_id, ':vassalId'=>$affi_id));
        }

        /**
         * Supprimer une demande d'affiliation entre un supérieur et un perso
         *
         * @param id $sup_id
         * @param id $affi_id
         */
        public function supAffiliationQuery($sup_id, $affi_id){
            $query = '
                DELETE FROM `wait_affil`
                WHERE
                    `superieur` = :supId AND
                    `vassal`    = :vassalId
            ';
            $stmt = $this->prepare($query);
            $stmt->execute(array(':supId'=>$sup_id, ':vassalId'=>$affi_id));
        }

        /**
         * Accepte une demande d'affiliation
         *
         * @param mat $sup_id
         * @param string $sup_nom
         * @param mat $affi_id
         * @param id $camp
         */
        public function acceptAffiliationQuery($sup_id, $sup_nom, $affi_id, $camp){
            $this->supAffiliationQuery($sup_id, $affi_id);
            $this->setSup($affi_id, $sup_id, $camp);
            $this->sendBal($sup_id, $affi_id, '[Affiliation] réponse', 'Bonjour,

Votre demande d\'affiliation à '.$sup_nom.' a été acceptée.');

            //Ajout de l'event
            $eventManager = new EventManager();
                $event = $eventManager->createEvent('affilia');
                $event->setSource($affi_id, 'perso');
                $event->setState(affilia::AFFI_AFFILIER);
                $event->setAffected($sup_id, 'perso');
        }

        /**
         * Refuse une demande d'affiliation
         *
         * @param type $sup_id
         * @param string $sup_nom
         * @param type $affi_id
         */
        public function refuseAffiliationQuery($sup_id, $sup_nom, $affi_id){
            $this->supAffiliationQuery($sup_id, $affi_id);
            $this->sendBal($sup_id, $affi_id, '[Affiliation] refus', 'Bonjour,

Votre demande d\'affiliation à '.$sup_nom.' a été refusée.');
        }

       function sendBal($expediteur, $mat, $titre, $corps){

            $bal = messagerieDAO::getInstance();
            $flag           = '0';
            $type_message   = 'joueur';

            $bal->InsertBalSend($expediteur, $mat,$titre, $corps, $flag, $type_message, NULL);
            $corps_id = $bal->InsertCorpsBal($titre, $corps, $mat, NULL);

            $sql_query = $bal->PrepareInsertBal('bals');
            $corps_id = $bal->InsertCorpsBal($titre, $corps, $mat, NULL);

            $parametres = array($expediteur, $mat, $corps_id, $flag, $type_message, 0);
            //-- Envoie de la bal a son destinataire
            $sql_bal = $bal->InsertBalPrepare($sql_query, $parametres);
        }

        function getAffiList($sup){

            $query = 'SELECT id, nom FROM persos WHERE superieur_id = :mat ORDER BY nom';
            $stmt = $this->prepare($query);
            $stmt->execute(array(':mat'=>$sup));
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        }
        
        function isSup($sup_id, $affi_id){
            $query = 'SELECT superieur_id FROM persos WHERE  id = :mat';
            $stmt = $this->prepare($query);
            $stmt->execute(array(':mat'=>$affi_id));
            $val = $stmt->fetch(\PDO::FETCH_OBJ);
            return $val && $val->superieur_id == $sup_id;
        }
        
        function updateGradeGalon($mat, $grade, $galon){
            $query = 'UPDATE persos SET `grade_id` = :grade,`galon_id` = :galon  WHERE id = :mat';
            $stmt = $this->prepare($query);
            $stmt->execute(array(
                ':mat'=>$mat,
                ':grade'=>$grade,
                ':galon'=>$galon
            ));
        }

    }
?>
