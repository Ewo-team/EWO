<?php

class GenerateurPalette {
        
        public function listerImages() {
            $dirname = '../images/decors/motifs/';
            $dir = opendir($dirname); 
            $liste_images = array();
            while($file = readdir($dir)) {
                    if($file != '.' && $file != '..' && !is_dir($dirname.$file))
                    {
                            $liste_images[$dirname.$file] =  $file;
                    }
            }  
            return $liste_images;
        }
        
	public function afficheImages($liste) {
            $dirname = '../images/decors/motifs/';
            $dir = opendir($dirname); 
            echo '<div class="liste_images"><h3>Liste des motifs</h3><ul>';
            foreach($liste as $url => $img) {
                echo '<li><img src="'.$url.'"> - '.GenerateurPalette::Camel($img).'</li>';
            }  
            echo '</ul></div>';
        }
        
		private static function Camel($string) {
			$tab = explode('_',$string);
			if(count($tab) > 1) {
				foreach($tab as $k => $v) {
					$tab[$k] = ucfirst(substr($v,0,3));
				}
			}
			
			return implode($tab);
		}
		
        public function affichePalette($raw) {
            include('raw/' . $raw . '_palette.php');
            
            
            
            if(file_exists('palette/' . $raw . '.php')) { 
                include('palette/' . $raw . '.php');
                $liste_couleurs = array_keys($css);
                $liste_couleurs[] = end($liste_couleurs) + 1;
            }
            
            $liste_images = $this->listerImages();
            
            $this->afficheImages($liste_images);
            
            echo '<form action="edition_palette.php" method="post">
                <input type="hidden" name="raw" value="'.$raw.'">
                <h3>Style de l\'image</h3>
                <table border="1" style="width: 50%">
                <tr>
                    <th>ID Couleur</th>
                    <th>Nom classe CSS</th>
                    <th>Image</th>
                    <th>Background</th>
                    <th>Bloquante</th>					
                    <th>Coût mouv.</th>
                    <th>Coût PV</th>
                </tr>';
            
            sort($couleurs);
            foreach($couleurs as $couleur) {
                
                $nom = (isset($css[$couleur]['nom'])) ? $css[$couleur]['nom'] : '';
                $img = (isset($css[$couleur]['img'])) ? $css[$couleur]['img'] : '';
                $back = (isset($css[$couleur]['back'])) ? $css[$couleur]['back'] : '';
				
                $block = (isset($css[$couleur]['block'])) ? " checked" : "";
				
                $mouv = (isset($css[$couleur]['mouv'])) ? $css[$couleur]['mouv'] : '';
                $pv = (isset($css[$couleur]['pv'])) ? $css[$couleur]['pv'] : '';
                
				
				
                echo '<tr>
                    <td>'.$couleur.'</td>
                    <td><input type="text" value="'.$nom.'" name="nom_'.$couleur.'"></td>
                    <td><select name="image_'.$couleur.'"><option></option>';
                        foreach ($liste_images as $url => $image) {
                            if($image == $img) {
                                 echo '<option selected value="'.$image.'" data-image="../images/decors/motifs/'.$image.'">'.static::Camel($image).'</option>';
                            } else {
                                echo '<option value="'.$image.'" data-image="../images/decors/motifs/'.$image.'">'.static::Camel($image).'</option>';
                            }
                        }                            
                echo '</select></td>
                    <td><select name="background_'.$couleur.'"><option></option>';
                        foreach ($liste_images as $url => $image) {
                            if($image == $back) {
                                 echo '<option selected value="'.$image.'" data-image="../images/decors/motifs/'.$image.'">'.static::Camel($image).'</option>';
                            } else {
                                echo '<option value="'.$image.'" data-image="../images/decors/motifs/'.$image.'">'.static::Camel($image).'</option>';
                            }
                        }                            
                echo '</select></td>     
					<td><input type="checkbox" '.$block.' name="block_'.$couleur.'"></td>
					<td><input type="text" value="'.$mouv.'" name="mouv_'.$couleur.'"></td>
					<td><input type="text" value="'.$pv.'" name="pv_'.$couleur.'"></td>
                </tr>';
                
                if(in_array($couleur, $liste_couleurs)) {
                    $key = array_search($couleur, $liste_couleurs);
                    unset($liste_couleurs[$key]);
                }
                
            }
            echo '</table>';
                
            sort($liste_couleurs);
            
            echo '<h3>styles supplémentaires</h3><table border="1" style="width: 50%">
                <tr>
                    <th>Nom classe CSS</th>
                    <th>Image</th>
                    <th>Background</th>
                    <th>Bloquante</th>					
                    <th>Coût mouv.</th>
                    <th>Coût PV</th>					
                </tr>';
            foreach($liste_couleurs as $couleur) {
                
                $nom = (isset($css[$couleur]['nom'])) ? $css[$couleur]['nom'] : '';
                $img = (isset($css[$couleur]['img'])) ? $css[$couleur]['img'] : '';
                $back = (isset($css[$couleur]['back'])) ? $css[$couleur]['back'] : '';
				
                $block = (isset($css[$couleur]['block'])) ? " checked" : "";
				
                $mouv = (isset($css[$couleur]['mouv'])) ? $css[$couleur]['mouv'] : '';
                $pv = (isset($css[$couleur]['pv'])) ? $css[$couleur]['pv'] : '';				
                
                echo '<tr>
                    <td><input type="text" value="'.$nom.'" name="nom_'.$couleur.'"></td>
                    <td><select name="image_'.$couleur.'"><option></option>';
                        foreach ($liste_images as $url => $image) {
                            if($image == $img) {
                                 echo '<option selected value="'.$image.'">'.static::Camel($image).'</option>';
                            } else {
                                echo '<option value="'.$image.'">'.static::Camel($image).'</option>';
                            }
                        }                            
                echo '</select></td>
                    <td><select name="background_'.$couleur.'"><option></option>';
                        foreach ($liste_images as $url => $image) {
                            if($image == $back) {
                                 echo '<option selected value="'.$image.'">'.static::Camel($image).'</option>';
                            } else {
                                echo '<option value="'.$image.'">'.static::Camel($image).'</option>';
                            }
                        }                            
                echo '</select></td> 
					<td><input type="checkbox" '.$block.' name="block_'.$couleur.'"></td>
					<td><input type="text" value="'.$mouv.'" name="mouv_'.$couleur.'"></td>
					<td><input type="text" value="'.$pv.'" name="pv_'.$couleur.'"></td>
                </tr>';        
            }
            echo '</table>';

            echo '<input type="submit" name="sauver" value="Sauver la palette">
                </form>';
            
        }
        
	public function generer($raw, $formulaire) {
            
            $palette = array();
            $data = '';
            
            // Préparation des palettes
            foreach($formulaire as $nom => $value) {
                $val = null;
                //echo "$nom => $value<br>";
                if(!empty($value)) {
                    if(preg_match('/(?P<name>\w+)_(?P<digit>\d+)/',$nom,$val)) {
                        $couleur = $val[2];
                        $index = $val[1];
                        $palette[$couleur][$index] = $value;
                    }
                }
            }
            
            foreach($palette as $k => $v) {
                if(isset($v['nom'])) {
                    $data .= '$css['.$k.']["nom"] = "'.$v['nom'].'";' . PHP_EOL;
                }
                
                if(isset($v['image'])) {
                    $data .= '$css['.$k.']["img"] = "'.$v['image'].'";' . PHP_EOL;
                }          
                
                if(isset($v['background'])) {
                    $data .= '$css['.$k.']["back"] = "'.$v['background'].'";' . PHP_EOL;
                }  

                if(isset($v['block'])) {
                    $data .= '$css['.$k.']["block"] = "'.$v['block'].'";' . PHP_EOL;
                }  	

                if(isset($v['mouv']) && $v['mouv'] != 0) {
                    $data .= '$css['.$k.']["mouv"] = "'.$v['mouv'].'";' . PHP_EOL;
                }  	

                if(isset($v['pv']) && $v['pv'] != 0) {
                    $data .= '$css['.$k.']["pv"] = "'.$v['pv'].'";' . PHP_EOL;
                }  					                
            }
            
            $handle = fopen('palette/' . $raw . '.php','w');
            fwrite($handle , '<?php' . PHP_EOL . PHP_EOL);

            fwrite($handle , $data);

            fclose($handle);    

            
        }

}
