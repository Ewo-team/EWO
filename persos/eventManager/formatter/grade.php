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
	
		$textes[0][1] = 'up g1';
		$textes[1][2] = 'up g2';
		$textes[2][3] = 'up g3';
		$textes[3][4] = 'up g4';
		$textes[4][5] = 'up g5';
		
		$textes[1][0] = 'down g0';
		$textes[2][1] = 'down g1';
		$textes[3][2] = 'down g2';
		$textes[4][3] = 'down g3';
		$textes[5][4] = 'down g4';		
		
		if(isset($textes[$gradeInitial][$gradeFinal])) {
			return $textes[$gradeInitial][$gradeFinal];
		}
		
		if($gradeFinal > $gradeInitial) {
			return 'up';
		}
		
		return 'down';
	
	}
}
