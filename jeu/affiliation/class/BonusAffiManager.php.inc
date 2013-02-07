<?php
/**
 * Classe qui va gèrer les bonus d'affiliation pour les supérieurs
 *
 * @author Benjamin Herbomez <benjamin.herbomez@gmail.com>
 */

    require_once('AffiliationDAO.php.inc');
    require_once($root_url.'/jeu/affiliation/class/Superieurs.php.inc');

require_once($root_url.'/persos/fonctions.php');


class BonusAffiManager{
    private $camp;
    private static $sql = null;

    private $updateRq = '';

    //carac => tranche d'up en %, valeur d'up en %, valeur fixe
    //toutes les  X % d'affiliés, AA/SD gagne Y % d'une carac +  Z en valeur fixe de la carac
    private $config = array(
        'pv'            => array(100,5,0),
        'force'         => array(100,5,0),
        'perception'    => array(100,10,0),
    );

    public static function process($camp){
        //Set de l'accès à la bdd
        if(BonusAffiManager::$sql == null)
            BonusAffiManager::$sql = AffiliationDAO::getInstance();
        $obj = new BonusAffiManager($camp);
        $obj->exec();
    }

    protected function __construct($camp) {
        $this->camp = $camp;
    }

    protected function exec(){
        $campsCount = $this->getAffiCount();
        $tot = 0;
        foreach($campsCount as $v){
            $tot += $v;
        }
        $sups = Superieurs::getAvailableSup($this->camp);
        foreach($sups as $k => $v){
            if(array_key_exists($k, $campsCount))
                $this->prepareUpdate($v,100*$campsCount[$k]/$tot);
            else
                $this->prepareUpdate($v,0);
        }
        $this->commitUpdate();
    }

    /**
     * retourne le nombre d'affilié par supérieur
     * @return type
     */
    protected function getAffiCount(){
        $query = '
        SELECT
            p.superieur_id as id, COUNT(p.id) as nb
        FROM `persos` p
        INNER JOIN
            `races` r
            ON r.race_id = p.race_id AND r.grade_id = p.grade_id
        WHERE
            r.camp_id = :camp AND p.superieur_id IS NOT NULL AND  p.superieur_id != 0
        GROUP BY
            p.superieur_id
        ';
        $stmt = BonusAffiManager::$sql->prepare($query);
        $stmt->execute(array(':camp' => $this->camp));
        $r = array();
        foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $v)
            $r[$v->id] = $v->nb;
        return $r;
    }

    protected function prepareUpdate($sup, $ratio){
        $rq = '';
        $vs = '';
        foreach($this->config as $k => $v){
            $caracNvName = 'niv_'.$k;
            $carac = carac_max($sup->race_id, $sup->grade_id, $k, $sup->$caracNvName, $sup->id, $sup->galon_id);
            $bonus = floor((floor($ratio/$v[0])*($v[1]*$carac/100 + $v[2])));

            $rq .= ','.$bonus;
            $vs .= ',`alter_'.$k.'`';
        }
        $rq = '
            DELETE FROM `caracs_alter_affi` WHERE `perso_id` = '.$sup->id.';
            INSERT INTO `caracs_alter_affi`
                (`perso_id`'.$vs.')
                VALUES ('.$sup->id.$rq.');';
        $this->updateRq .= $rq;
    }

    protected function commitUpdate(){
        $stmt = BonusAffiManager::$sql->prepare($this->updateRq);
        $stmt->execute();
    }

}
?>
