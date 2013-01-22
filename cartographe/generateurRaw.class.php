<?php

class GenerateurRaw {

        private function ecritHeader($x_min, $x_max,$y_min,$y_max) {
			if($handle = @fopen('raw/' . ($this->_nomFichier) . '_map.php','w')) {
                fwrite($handle , '<?php' . PHP_EOL . PHP_EOL);
                
                fwrite($handle , '$x_min = "' . $x_min. '";' . PHP_EOL);
                fwrite($handle , '$x_max = "' . $x_max. '";' . PHP_EOL);
                fwrite($handle , '$y_min = "' . $y_min. '";' . PHP_EOL);
                fwrite($handle , '$y_max = "' . $y_max. '";' . PHP_EOL . PHP_EOL);
                
                fwrite($handle , '$carte = array();' . PHP_EOL . PHP_EOL);
  		
				fclose($handle);                        
			} else {
				echo 'erreur d\'écriture dans le fichier raw/' . ($this->_nomFichier) . '_map.php';
				exit;
			}
        }
        
        private function ecritCodeCss($couleurs) {
			if($handle = @fopen('raw/' . ($this->_nomFichier) . '_palette.php','w')) {
                
                
                fwrite($handle , '<?php' . PHP_EOL . PHP_EOL);
                
                foreach($couleurs as $couleur) {
                    fwrite($handle , '$couleurs[] = "' . $couleur. '";' . PHP_EOL);
                }
		
				fclose($handle);    
			} else {
				echo 'erreur d\'écriture dans le fichier raw/' . ($this->_nomFichier) . '_palette.php';
				exit;
			}		
        }

        private function ecritFichier($data) {
		
		if($handle = @fopen('raw/' . ($this->_nomFichier) . '_map.php','a')) {

			fwrite($handle , $data);
			
			fclose($handle);
		} else {
			echo 'erreur d\'écriture dans le fichier raw/' . ($this->_nomFichier) . '_map.php';
			exit;
		}
		
	}
	
	private function ecritSauvegarde() {
		if($handle = @fopen('raw/' . ($this->_nomFichier) . '_sav.php','w')) {
            fwrite($handle , '<?php' . PHP_EOL . PHP_EOL);	
			fclose($handle);		
		} else {
			echo 'erreur d\'écriture dans le fichier raw/' . ($this->_nomFichier) . '_sav.php';
			exit;
		}
	}
	
	public function genereFromPng($fichier, $offsetx = null, $offsety = null) {
		
                $this->_nomFichier = $fichier;
		
		$couleurs = array();
	
		$im = imagecreatefrompng('ressource/'.$fichier.'.png');
		
		$x_max = imagesx($im);
		$y_max = imagesy($im);
		
		$x_min = 0;
		$y_min = 0;
                
                if(!isset($offsetx)) {
                    $offsetx = 0 - ($x_max / 2);
                }
                if(!isset($offsety)) {
                    $offsety = 0 - ($y_max / 2);
                }                
                
                $this->ecritHeader($x_min + $offsetx, $x_max + $offsetx, $y_min + $offsety, $y_max + $offsety);
		
		for($x = $x_min; $x < $x_max; $x++) {
			//echo "boucle X $x<br>";
			for($y = $y_min; $y < $y_max; $y++) {
				//echo "boucle Y $y<br>";
				$rgb = ImageColorAt($im, $x, ($y_max-$y)-1);
				
				$r = $g = $b = 0;
				
				$r = ($rgb >> 16) & 0xFF;
				$g = ($rgb >> 8) & 0xFF;
				$b = $rgb & 0xFF;
		
				//$couleur = '#'.dechex($r+0).dechex($g+0).dechex($b+0);
				
				if(!in_array($rgb,$couleurs)) {
					$couleurs[] = $rgb;
				}

				$coordonne_x = $x+$offsetx;
				$coordonne_y = $y+$offsety; // Inversion des corrdonn�es Y
				
				$data = '$carte[';
				$data .= $coordonne_x;
				$data .= '][';
				$data .= $coordonne_y;
				$data .= '] = "';
				$data .= $rgb;
				$data .= '";' . PHP_EOL;

				$this->ecritFichier($data);
				

			}
		}
                
        $this->ecritCodeCss($couleurs);
		$this->ecritSauvegarde();
	
	}

}
