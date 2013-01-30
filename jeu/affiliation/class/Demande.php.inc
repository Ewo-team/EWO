<?php
/**
 * Class pour la gestion des demandes (dans les deux sens)
 *
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 */
class Demande{
    private $persoId;
    private $persoNom;
    private $supId;
    private $supNom;

    public function __construct($persoId, $persoNom, $supId, $supNom){
        $this->persoId	= $persoId;
        $this->persoNom = $persoNom;
        $this->supId    = $supId;
        $this->supNom   = $supNom;
    }

    /**
     * Getters
     */
     public function getPersoId(){
         return $this->persoId;
     }

     public function getPersoNom(){
         return $this->persoNom;
     }

     public function getSupId(){
         return $this->supId;
     }

     public function getSupNom(){
         return $this->supNom;
     }
}
?>
