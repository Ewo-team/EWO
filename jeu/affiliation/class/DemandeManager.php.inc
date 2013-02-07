<?php

/**
 * Classe abstraite qui permet de gérer les demandes.
 *
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 * @package legions
 */


require_once($root_url . '/jeu/affiliation/class/Demande.php.inc');
require_once($root_url . '/jeu/affiliation/class/AffiliationDAO.php.inc');

abstract class DemandeManager {

    protected $demandes = array();
    protected $sql;

    /**
     * Charge les demandes pour le supérieur $id
     *
     * @param type $id matricul du supérieur en question
     */
    public function __construct($id) {
        $this->sql = AffiliationDAO::getInstance();
        $this->loadDemandes($id);
    }


    protected abstract function loadDemandes($id);

    public function getDemandes() {
        return $this->demandes;
    }

    /**
     * addDemande
     * Créé une nouvelle demande
     *
     * /!\ Ne fait aucune vérification d'unicité, c'est à faire avant l'appel
     */
    public function addDemande($perso, $sup){
        $this->sql->addAffiliationQuery($sup, $perso);
    }

    /**
     * Supprimer une demande
     *
     * @param type $perso
     * @param type $sup
     */
    public function supDemande($sup, $perso) {
        $this->sql->supAffiliationQuery($sup, $perso);
    }

}

?>
