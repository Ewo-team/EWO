<?php

/**
 * Class pour la gestion des grades
 * 
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 * @package legions
 */

namespace legions;

    class Grade {

        private $id;
        private $grade_id;
        private $nom;
        private $description;
        private $droits;
        
        function __construct($id, $grade_id, $nom, $description, $droits) {
            $this->id = $id;
            $this->grade_id = $grade_id;
            $this->nom = $nom;
            $this->description = $description;
            $this->droits = $droits;
        }

        public function getId() {
            return $this->id;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function getGrade_id() {
            return $this->grade_id;
        }

        public function setGrade_id($grade_id) {
            $this->grade_id = $grade_id;
        }

        public function getNom() {
            return $this->nom;
        }

        public function setNom($nom) {
            $this->nom = $nom;
        }

        public function getDescription() {
            return $this->description;
        }

        public function setDescription($description) {
            $this->description = $description;
        }

        public function getDroits() {
            return $this->droits;
        }
        public function getDroitsArray() {
            $r = array();
            $j = strlen($this->droits);
            for($i=0; $i < $j;++$i)
                $r[$i] = $this->droits[$i];
            return $r;
        }

        public function setDroits($droits) {
            $this->droits = $droits;
        }
    }

?>
