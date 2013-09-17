<?php
include_once('formatter.php');

class grade extends formatter{
	public function printPublic(&$bdd){
		$public = parent::getEvent()->infos->getPublicInfos();
		return $this->getText($public['i'], $public['f']);
	}
	// nom + race
	public function printPrivate(&$bdd){
		$private = parent::getEvent()->infos->getPrivateInfos();
		return '';
	}
	
        private function getText($gradeInitial, $gradeFinal) {
            $text = "";
            if ($gradeInitial < $gradeFinal) {
                    $text = "vient d'obtenir le grade $gradeFinal.";
            } else {
                    $text = "a fait le con, a perdu son grade $gradeInitial et se retrouve avec un pauvre grade $gradeFinal.";
            }

            return $text;
        }
}
