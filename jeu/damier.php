<?php

namespace jeu;

class Damier {

	private $pov;
	private $decors;
	private $grid = array();
	private $list_cases = array();

	public function __construct($perso_x, $perso_y, $percept, $plan) {
		
		$this->pov = \conf\pov\POV::getPOV($percept);
		$this->decors = decors\Decors::prepareDecors($plan);
			
		for($x = $perso_x - $percept; $x <= $perso_x + $percept; $x++) {
			for($y = $perso_y - $percept; $y <= $perso_y + $percept; $y++) {
				$this->grid[$x][$y] = new DamierCase();
			}
		}
	}
	
	public function initialize() {
	
		$cases = array();
	
		foreach($this->grid as $x => $col) {
			foreach($col as $y => $value) {
			
				$case = $this->grid[$x][$y];
				
				if(!$case->initialized) {
					if(!isset($cases[$x][$y])) {
						$new = $this->decors->getCases($x,$y);
						if(is_array($new)) {

							$cases = array_merge_recursive($new, $cases);
						}
					}
					
					if(isset($cases[$x][$y]['img'])) {
						$case->background = $cases[$x][$y]['img'];
					}
					
					if(isset($cases[$x][$y]['block'])) {
						$case->visible = false;
					}					
					
					$case->initialized = true;
					$this->grid[$x][$y] = $case;
					
				}
			}
		}

		echo '<pre>';
		print_r($cases);
		echo '</pre>';		
		

	}
	
	public function getCase($x,$y) {
		if(isset($this->grid[$x][$y])) {
			return $this->grid[$x][$y];
		}
		
		return false;
	}
}

class DamierCase {
	public $initialized = false;
	public $background = null;
	public $visible = true;
}