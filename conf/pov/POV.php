<?php

class POV {
	
	private $taille;
	private $grid;
	public $liste_rayon;

	public static function getPOV($taille) {
	
		if(!file_exists(__DIR__ . '/template/'.$taille.'.pov')) {
			$pov = new POV($taille);
			$content = $pov->liste_rayon;
		} else {
			$content = unserialize(file_get_contents(__DIR__ . '/template/'.$taille.'.pov'));
		}
		
		return $content;
	}
	
	private function __construct($taille) {
		$this->taille = $taille;
		
		$this->initialise();
		
		$this->run();
	
		file_put_contents(__DIR__ . '/template/'.$taille.'.pov', serialize($this->liste_rayon));	
	}
	
	private function recurse($search, $arr) {
		foreach($arr as $x => $line) {
			foreach($line as $y => $col) {
				if($col == $search) {
					return array($x,$y);
				}
			}
		}
		
		return false;
	}

	private function initialise() {


		$this->grid = array();

		for($ligne = 0; $ligne < $this->taille; $ligne++) {
			$arr = array();
			for($colonne = 0; $colonne < $this->taille; $colonne++) {
				$arr[] = 0;
			}
			$this->grid[] = $arr;
		}

	}

	/**
	* Virtually draw a line from (x1, y1) to (x2, y2) using Bresenham algorithm, returning the coordinates of the points that would belong to the line.
	* @param $x1 (Int)
	* @param $y1 (Int)
	* @param $x2 (Int)
	* @param $y2 (Int)
	* @param $guaranteeEndPoint By default end point (x2, y2) is guaranteed to belong to the line. Many implementation don't have this. If you don't need it, it's a little faster if you set this to false.
	* @return (Array of couples forming the line) Eg: array(array(2,100), array(3, 101), array(4, 102), array(5, 103))
	* Public domain Av'tW
	*/
	private function bresenham($x1, $y1, $x2, $y2, $guaranteeEndPoint=true) {
		$xBegin = $x1;
		$yBegin = $y1;
		$xEnd = $x2;
		$yEnd = $y2;
		$dots = array();        // Array of couples, returned array</p>

		$steep = abs($y2 - $y1) > abs($x2 - $x1);

		// Swap some coordinateds in order to generalize
		if ($steep) {
			$tmp = $x1;
			$x1 = $y1;
			$y1 = $tmp;
			$tmp = $x2;
			$x2 = $y2;
			$y2 = $tmp;
		}
		
		if ($x1 > $x2) {
			$tmp = $x1;
			$x1 = $x2;
			$x2 = $tmp;
			$tmp = $y1;
			$y1 = $y2;
			$y2 = $tmp;
		}
		
		$deltax = floor($x2 - $x1);
		$deltay = floor(abs($y2 - $y1));
		$error = 0;
		$deltaerr = $deltay / $deltax;
		$y = $y1;
		$ystep = ($y1 > $y2) ? 1 : -1;

		for ($x = $x1; $x < $x2; $x++) {
			$dots[] = $steep ? array($y, $x) : array($x, $y);
			$error += $deltaerr;

			if ($error >= 0.5) {
				$y += $ystep;
				$error -= 1;
			}
		}

		if ($guaranteeEndPoint) {
			// Bresenham doesn't always include the specified end point in the result line, add it now.
			if ((($xEnd - $x) * ($xEnd - $x) + ($yEnd - $y) * ($yEnd - $y)) <
			(($xBegin - $x) * ($xBegin - $x) + ($yBegin - $y) * ($yBegin - $y))) {
				// Then we're closer to the end
				$dots[] = array($xEnd, $yEnd);
			} else {
				$dots[] = array($xBegin, $yBegin);
			}
		}
		
		return $dots;
	}
	
	private function run() {
	


		$cases = array();
		for($i=0;$i<$this->taille;$i++) {
			$cases[] = array($i,$this->taille-1);
			$cases[] = array($this->taille-1,$i);
		}

		$this->liste_rayon = array();

		$this->parcours($cases);
		
		$recurse = true;

		do {
			$case = $this->recurse(0, $this->grid);
						
			if($case == false) {
				$recurse = false;
			} else {
				$this->parcours(array($case));
			}
		} while($recurse);	
		
	}
	
	private function parcours($cases) {

		foreach($cases as $rayon) {

			$position_x = $rayon[0];
			$position_y = $rayon[1];

			$line = $this->bresenham(0, 0, $position_x, $position_y, false);
			$line[] = array($position_x,$position_y);
			
			$current_ray = array();
			
			$couleur = 1;
			foreach($line as $point) {
				$x = abs($point[0]);
				$y = abs($point[1]);
				
				if($this->grid[$x][$y] != $couleur) {
				
					$this->grid[$x][$y] = $couleur;	
					$current_ray[] = $point;
				}
			}
			
			$this->liste_rayon[] = $current_ray;
		}
	}

}