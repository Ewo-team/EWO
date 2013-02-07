<?php

/**
 * Class pour la gestion des droits dans une faction
 * 
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 * @package legions
 */

namespace legions;

class DroitManager {

    private $gererChef = false;
    private $gererBrasDroit = false;
    private $gererMembre = false;
    private $gererGrade = false;
    private $gererInvitation = false;
    private $gererBal = false;
    private $utiliserGPS = false;
    private $voirInfos = false;

    public function __construct($tbl) {
        $var = array('gererChef', 'gererBrasDroit', 'gererMembre', 'gererGrade', 'gererInvitation', 'gererBal', 'utiliserGPS', 'voirInfos');
        foreach ($var as $k => $v) {
            $this->$v = $tbl[$k] == 1;
        }
    }

    /**
     * Getters
     */
    public function gererChef() {
        return $this->gererChef;
    }

    public function gererBrasDroit() {
        return $this->gererBrasDroit;
    }

    public function gererMembre() {
        return $this->gererMembre;
    }

    public function gererGrade() {
        return $this->gererGrade;
    }

    public function gererInvitation() {
        return $this->gererInvitation;
    }

    public function gererBal() {
        return $this->gererBal;
    }

    public function utiliserGPS() {
        return $this->utiliserGPS;
    }

    public function voirInfos() {
        return $this->voirInfos;
    }

    public function getDroits() {
        $r = array();
        $var = array('gererChef', 'gererBrasDroit', 'gererMembre', 'gererGrade', 'gererInvitation', 'gererBal', 'utiliserGPS', 'voirInfos');
        
        foreach ($var as $k => $v) {
            $r[$k] = $this->$v;
        }
        return $r;
    }

}

?>
