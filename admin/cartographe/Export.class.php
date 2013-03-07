<?php

class Export {

	private $generate;
	
	private $_nomFichier;
	private $_tailleFragments;
	private $_fichiersTraite = array();	

	private function ecritFragments($x, $y, $data) {
		
		$fragment_x = ceil($x/$this->_tailleFragments);
		$fragment_y = ceil($y/$this->_tailleFragments);
		$nomFragment = $this->_nomFichier.'_'.$fragment_x.'_'.$fragment_y;

		
		
		if(!in_array($nomFragment,$this->_fichiersTraite)) {
			$handle = fopen(SERVER_ROOT . '/jeu/decors/maps/'.$this->_nomFichier.'/'.$nomFragment.'.php','w');
			fwrite($handle , '<?php' . PHP_EOL . PHP_EOL);
			fwrite($handle , '$chargement[] = "MAP_'.strtoupper($nomFragment).'";' . PHP_EOL . PHP_EOL);
			$this->_fichiersTraite[] = $nomFragment;
		} else {
			$handle = fopen(SERVER_ROOT . '/jeu/decors/maps/'.$this->_nomFichier.'/'.$nomFragment.'.php','a');
		}

		fwrite($handle , $data);
		
		fclose($handle);
		
	}	

		
	public function ecritMaster() {
	
		// Suppression des anciens fichiers
		if (!is_dir(SERVER_ROOT . '/jeu/decors/maps/'.$this->_nomFichier .'/')) {
			mkdir(SERVER_ROOT . '/jeu/decors/maps/'.$this->_nomFichier .'/');
		} else {
			unlink(SERVER_ROOT . '/jeu/decors/maps/'.$this->_nomFichier .'/*.php');
		}		
		
		$data = '<?php ' . PHP_EOL .'$carte_taille = '.$this->_tailleFragments.'; '. PHP_EOL . ' ?>';

		$handle = fopen(SERVER_ROOT . '/jeu/decors/maps/'.$this->_nomFichier .'/'.$this->_nomFichier .'.php','w');

		fwrite($handle , $data);
		
		fclose($handle);	
	}
		
	//$gc->genereFromPng('Althian', 'althian.png', -200, -200, 15);	
	public function Build($fichier, $taillefragments) {
		
        $this->_nomFichier = $fichier;
		
		$this->generate = array();
		
		include('raw/'.$fichier.'_map.php');

		include('palette/'.$fichier.'.php');

		include('raw/'.$fichier.'_sav.php');		
		
		$this->_tailleFragments = $taillefragments;
		
		$this->ecritMaster();
		
		for($x = $x_min; $x < $x_max; $x++) {
			for($y = $y_min; $y < $y_max; $y++) {
			
			

			
				/*$rgb = ImageColorAt($im, $x, ($y_max-$y)-1);
				
				$r = $g = $b = 0;
				
				$r = ($rgb >> 16) & 0xFF;
				$g = ($rgb >> 8) & 0xFF;
				$b = $rgb & 0xFF;*/
		
				//$couleur = '#'.dechex($r+0).dechex($g+0).dechex($b+0);
				
				/*if(!in_array($rgb,$couleurs)) {
					$couleurs[] = "$rgb => ($r,$g,$b)";
				}
				
				switch($rgb) {
					case 110: $image = 'cascade'; break;//Eau barrage
					case 255: case 23039: $image = 'eau'; break;//eau
					case 30208: $image = 'champs'; break;//champs
					case 52736: $image = 'savane'; break;//savane
					case 65535: $image = 'neige'; break;//	glace
					case 4473924: $image = 'dallage'; break;//sol barrage
					case 5592405: $image = 'mur_bas_vertical'; break;//Barri?re basse
					case 5921370: $image = 'mur'; break;//Mur
					case 5987163: $image = 'mur_centre_vertical'; break;//Barri??re centrale
					case 6250335: $image = 'mur_haut_vertical'; break;//Barri?re haute
					case 6776679: $image = 'montagne'; break;//Montage
					case 8421504: $image = 'pont'; break;//Pont
					case 11534336: $image = 'roche-lave'; break;//Terre volcanique
					case 16053492: $image = 'route'; break;//	route
					case 16711680: $image = 'lave'; break;//Lave
					case 16776960: $image = 'sable'; break;//	sable
					case 16777215:
					default: $image = 'herbe'; break;// herbe
				}*/
				
				$index = $carte[$x][$y];
				
				$data = $css[$index];	

				foreach($data as $key => $ligne) {
					$write = '$carte["'.$x.'"]["'.$y.'"]["'.$key.'"] = "'.$ligne.'";' . PHP_EOL;

					$this->ecritFragments($x, $y, $write);				
				}

			}
		}
		
		
		
	}
		
		
		
		
		/*
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
        private function ecritHeader($taille) {
			if($handle = @fopen('map/'.$this->_nomFichier.'.php','w')) {
				fwrite($handle, '<?php $carte_taille = 15; ?>');
				fclose($handle);                        
			} else {
				echo 'erreur d\'écriture dans le fichier map/'.$this->_nomFichier.'.php';
				exit;
			}
        }
		
		
		
		
        
        private function ecritFichier($fragment_x, $fragment_y, $x, $y, $data) {
		
		$file = ($this->_nomFichier) . '_' . $fragment_x . '_' . $fragment_y;
		

		if($handle = @fopen('map/'.$file.'.php','a')) {
			
			if(!in_array($file, $this->generate)) {			
			
				fwrite($handle , '<?php' . PHP_EOL . PHP_EOL);	
				
				$up = strtoupper($this->_nomFichier);
				
				fwrite($handle , '$chargement[] = "MAP_'.$up.'_'.$fragment_x.'_'.$fragment_y.'";' . PHP_EOL);	
				
				$this->generate[] = $file;
				
						
			} else {

				foreach($data as $key => $value) {
					fwrite($handle , '$carte['.$x.']['.$y.']["'.$key.'"] = "'.$value.'";' . PHP_EOL);
				}
			}
		
		} else {
			echo 'erreur d\'écriture dans le fichier map/'.$file.'.php';
			exit;
		}		

		
		fclose($handle);
		
	}*/
	/*
	public function Build($fichier, $taille) {
		
        $this->_nomFichier = $fichier;
		
		$this->generate = array();
		
		include('raw/'.$fichier.'_map.php');

		include('palette/'.$fichier.'.php');

		include('raw/'.$fichier.'_sav.php');
		
		// Effacer l'export
		
        $this->ecritHeader($taille);
		
		for($x = $x_min; $x < $x_max; $x++) {

			for($y = $y_min; $y < $y_max; $y++) {

				
				$index = $carte[$x][$y];
				
				$data = $css[$index];
				
				$fragment_x = ceil($x / $taille);
				$fragment_y = ceil($y / $taille);

				$this->ecritFichier($fragment_x, $x, $y, $fragment_y, $data);
				

			}
		}
                
        //$this->ecritCodeCss($couleurs);
		//$this->ecritSauvegarde();
	
	}*/

}
