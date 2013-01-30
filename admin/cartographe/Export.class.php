<?php

class Export {

		private $generate;

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
		

		if($handle = @fopen('map/'.$file.'.php','w')) {
			
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
		
	}
	
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
	
	}

}
