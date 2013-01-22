<?php
namespace compte;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'config_vacances.php';

/**
 * Description of Compte
 *
 * @author Leo
 */
class Compte {

    public $id;
    private $dao;
    private $options = array();
    private $changed = array();
    private $initialized;

    public function __construct($id) {
        $this->id = $id;
        $this->dao = CompteDAO::getInstance();
        $this->initialized = false;
        $this->getUserOptions();
    }
    
    public function __destruct() {
        if(count($this->changed > 0)) {
            $change = array();

            foreach($this->changed as $key) {
                $change[$key] = $this->options[$key];
            }

            $this->dao->SaveUser($this->id, $change);
        }
    }

    public function __get($name) {
        if(isset($this->options[$name])) {
            return $this->options[$name];
        }
    }
    
    public function __set($name, $value) {
        if($this->options[$name] != $value) {
            
            if($name == 'email') {
                if(count($this->dao->checkEmail($value)) > 0) {
                    // Email déjà utilisé
                    return;
                }
            }
            
            $this->changed[] = $name;  
            $this->options[$name] = $value;        
        }
    }
    
    public static function encodePassword($pass) {
        return hash ('sha256',$pass);     
    }

    public function getUserOptions() {
        
        $result = $this->dao->SelectUser($this->id);
        if($result !== null) {
     
            foreach($result as $name => $value) {
                $this->options[$name] = $value;
            }
            
            $this->initialized = true;
        }
    }

function departVacances(){

        $this->dao->UpdateGoVacancies($this->id);
        
        /*
        
	if(mysql_query($sql)){
		/*Gestion des évènements*/
		/*foreach($persos as $matricule){
			addEventVacances($matricule, 1);
		}
		return true;
	}
	else{
		return false;
	}*/
}    
    
    function retourVacances() {

        $date_retour = date('Y-m-d H:i:s', time() + (intval(VACANCES_DELAI_RETOUR) * 3600));
        
        $this->dao->UpdateBackVacancies($this->id, $date_retour);
    }

    public function getVacancesButton() {
        $vacance = $this->dao->SelectUserVacancies($this->id);
        if (count($vacance) == 0) {
            //Pas de demande en vacances en cours
            return '<td><input type="checkbox" name="check_vacances" /><input type="hidden" name="v_action" value="depart" /></td><td><input type="submit" value="Partir en vacances" /></td>';
        } else {
            $row = $vacance[0];
            if ($row['date_retour'] != '0000-00-00 00:00:00') {
                //Le retour est programmé
                return '<td colspan="2"><span>Retour prévu le ' . date('d/m/Y H:i:s', strtotime($row['date_retour'])) . '</span></td>';
            } elseif ($row['date_depart'] == '0000-00-00 00:00:00') {
                //Départ en vacances prévu
                $date_depart = date('d/m/Y H:i:s', strtotime($row['date_demande']) + (intval(VACANCES_DELAI_DEPART) * 3600));
                return '<td colspan="2"><span>Départ prévu le ' . $date_depart . '</span></td>';
            } else {
                //Personnage en vacances
                //TODO : checker si le delai entre le depart et le retour est OK (pas demandé pour le moment)
                return '<td><input type="checkbox" name="check_vacances" /><input type="hidden" name="v_action" value="retour" /></td><td><input type="submit" value="Revenir de vacances" /></td>';
            }
        }
    }
    
    function statutVacances(){

            $vacance = $this->dao->SelectUserVacancies($this->id);
            
            if (count($vacance) == 0) {
                    //Pas de demande en vacances en cours
                    return 'jeu';
            }
            else{
                    $row = $vacance[0];
                    if($row['date_retour'] != '0000-00-00 00:00:00'){
                            //Le retour est programmé
                            return 'retour';
                    }
                    elseif($row['date_depart'] == '0000-00-00 00:00:00'){
                            return 'depart';
                    }
                    else{
                            return 'vacances';
                    }
            }
    }    

}

?>
