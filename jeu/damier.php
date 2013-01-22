<!-- Debut contour -->
<div class="block conteneur" id="block-7" style="z-index:800">
<div class='conteneur_titre'>Damier</div>
<!-- conteneur -->
<?php

$l_allie="";
$l_ennemi="";
$l_tous="";

$image_loc = "./../images/";
$perso_x = $_SESSION['persos']['pos_x'][$id];
$perso_y = $_SESSION['persos']['pos_y'][$id];



if($is_spawn){
    
    // Préparation des listes de BAL
    $bal_percept = false;
    if($grade >= 3 || ($grade==2 && $galon >=2)) {
            $bal_percept = true;
    }


    for ($inci = 1; $inci <= $liste_perso['case']['inc']; $inci++) {

        if ($liste_perso['case']['camp']['id'][$inci] == $camp && $liste_perso['case']['id'][$inci] != $perso_id) {
            if ($l_allie == "") {
                $l_allie = $liste_perso['case']['id'][$inci];
            } else {
                $l_allie.="-" . $liste_perso['case']['id'][$inci];
            }
        } elseif ($liste_perso['case']['id'][$inci] != $perso_id) {
            if ($l_ennemi == "") {
                $l_ennemi = $liste_perso['case']['id'][$inci];
            } else {
                $l_ennemi.="-" . $liste_perso['case']['id'][$inci];
            }
        }
        if ($liste_perso['case']['id'][$inci] != $perso_id) {
            if ($l_tous == "") {
                $l_tous = $liste_perso['case']['id'][$inci];
            } else {
                $l_tous.="-" . $liste_perso['case']['id'][$inci];
            }
        }
    }

        if($l_allie!="" && $bal_percept){?>
	<span class="tab_list_perso"><a href="../messagerie/index.php?id=<?php echo $perso_id ?>&dest=<?php echo $l_allie ?>#reponse">Message aux alli&eacute;s visibles</a></span> 
	<?php }
		if($l_ennemi!="" && $bal_percept){?>
	<span class="tab_list_perso"><a href="../messagerie/index.php?id=<?php echo $perso_id ?>&dest=<?php echo $l_ennemi ?>#reponse">Message aux ennemis visibles</a></span> 
	<?php }
		if($l_tous!="" && $bal_percept){?>
	<span class="tab_list_perso"><a href="../messagerie/index.php?id=<?php echo $perso_id ?>&dest=<?php echo $l_tous ?>#reponse">Message &agrave; tous</a></span> 
	<?php }    
 
    
// Chargement de la classe Carte, uniquement pour Althian pour le moment
$decors = null;
if($nom_decors != null) {
	include ('../carte/carte.class.php'); 
	$decors = Carte::prepareCarte($nom_decors);
}
//--------- Damier ---------//
$zindex = 5000;
echo '<table class="damier_corps" CELLPADDING="0" CELLSPACING="0" border="0">';
	for($height=(2*$vision+2);$height>=0;$height--){
		if($height==(2*$vision+2)) {
			echo '<thead>';
		}
		if($height==(2*$vision+2)-1) {
			echo '<tbody>';
		}	
		if($height==0) {
			echo '<tfoot>';
		}				
		echo '<tr height="39">';
			for($width=0;$width<=(2*$vision+2);$width++){
					if($x_min>$x_max){
							if(($x_min+$width-1)>($x_max_carte)){
								$pos_x_case = -($x_max_carte-$x_min_carte)+($width-1)+$x_min;
								}
								else {
									$pos_x_case = $x_min+($width-1);
									}
						}else{
							$pos_x_case = $x_min+($width-1);
							}												
					if($y_min>$y_max){
							if(($y_min+$height-1)>($y_max_carte)){
								$pos_y_case = -($y_max_carte-$y_min_carte)+($height-1)+$y_min;
								}
								else {
										$pos_y_case = $y_min+($height-1);
									}
								}else{
									$pos_y_case = $y_min+($height-1);
							}												
					$plan = $carte_pos;
					//$terrain = rchch_case($pos_x_case,$pos_y_case,$plan,$liste_terrain);
					$artefact = rchch_case($pos_x_case,$pos_y_case,$plan,$liste_artefact);
					$objet_simple = rchch_case($pos_x_case,$pos_y_case,$plan,$liste_objet_simple);
					$objet_complexe = rchch_case($pos_x_case,$pos_y_case,$plan,$liste_objet_complexe);
					$porte = rchch_case($pos_x_case,$pos_y_case,$plan,$liste_porte);
					$bouclier = rchch_case($pos_x_case,$pos_y_case,$plan,$liste_bouclier);
					$perso = rchch_case($pos_x_case,$pos_y_case,$plan,$liste_perso);
					$cout = rchch_cout($pos_x_case,$pos_y_case,$plan, $liste_perso, $liste_terrain, $liste_objet_simple, $liste_objet_complexe, $liste_bouclier, $decors);

                                        $deplacement = null;
                                        
                                        
                                        if(!isset($perso) && $rose) {
                                            
                                            $offset = $vision+1;
                                            
                                            if($width == $offset + 1) {
                                                if($height == $offset) {
                                                    $deplacement = 'droite';
                                                    $liendeplacement = 'deplacement.php?persoid='.$id.'&dep23=1';
                                                }elseif($height == $offset + 1) {                                                    
                                                    $deplacement = 'hautdroite';
                                                    $liendeplacement = 'deplacement.php?persoid='.$id.'&dep13=1';
                                                }elseif($height == $offset - 1) {       
                                                    $deplacement = 'basdroite';
                                                    $liendeplacement = 'deplacement.php?persoid='.$id.'&dep33=1';
                                                }

                                            }

                                            if($width == $offset - 1) {
                                                if($height == $offset) {
                                                    $deplacement = 'gauche';
                                                    $liendeplacement = 'deplacement.php?persoid='.$id.'&dep21=1';
                                                }elseif($height == $offset + 1) {   
                                                    $deplacement = 'hautgauche';
                                                    $liendeplacement = 'deplacement.php?persoid='.$id.'&dep11=1';
                                                }elseif($height == $offset - 1) {       
                                                    $deplacement = 'basgauche';
                                                    $liendeplacement = 'deplacement.php?persoid='.$id.'&dep31=1';
                                                }
                                            }

                                            if($width == $offset && $height == $offset + 1) {
                                                $deplacement = 'haut';
                                                $liendeplacement = 'deplacement.php?persoid='.$id.'&dep12=1';
                                            }

                                            if($width == $offset && $height == $offset - 1) {
                                                $deplacement = 'bas';
                                                $liendeplacement = 'deplacement.php?persoid='.$id.'&dep32=1';
                                            }
                                        }

            $position = $pos_x_case.":".$pos_y_case;
					$get_plan = 'terre';
					if($plan == 1){
						$get_plan = 'terre';
					}elseif($plan == 3){
						$get_plan = 'paradis';
					}elseif($plan == 2){
						$get_plan = 'enfer';
					}elseif($plan == 7){
						$get_plan = 'eau';
					}
					
					if($decors) {
						$case = $decors->getCase($pos_x_case,$pos_y_case);
						if($case) {
							$get_plan = $case;
						}
					}					
					
				if($pos_y_case == $y_max+1){
						if($pos_x_case == $x_max+1){
							echo '<td width="45" height="39" class="damier_hd" align="center">';
							echo "<div><img style='width:45px;height:39px;' src='".$root_url."/images/transparent.png' /></div>";
							echo '</td>';
						}elseif($pos_x_case != $x_min-1){
							echo '<td width="45" height="39" class="damier_case" align="center">';
							echo "<div>$pos_x_case</div>";
							echo '</td>';
						}else{
							echo '<td width="45" height="39" class="damier_hg" align="center">';
							echo "<div><img style='width:30px;height:39px;' src='".$root_url."/images/transparent.png' /></div>";
							echo '</td>';
							}
				}elseif($pos_x_case == $x_max+1 && $pos_y_case != $y_min-1){
						echo '<td width="45" height="39" class="damier_case" align="center">';
						echo "<div>$pos_y_case</div>";
						echo '</td>';
				}elseif($pos_y_case == $y_min-1){
						if($pos_x_case == $x_min-1){
							echo '<td width="45" height="39" class="damier_bg" align="center">';
							echo "<div></div>";
							echo '</td>';
						}elseif($pos_x_case != $x_max+1){
							echo '<td width="45" height="39" class="damier_case" align="center">';
							echo "<div>$pos_x_case</div>";
							echo '</td>';
						}else{
							echo '<td width="45" height="39" class="damier_bd" align="center">';
							echo "<div></div>";
							echo '</td>';
							}
				}elseif($pos_x_case == $x_min-1){
						echo '<td width="45" height="39" class="damier_case" align="center">';
						echo "<div>$pos_y_case</div>";
						echo '</td>';
				}else{
				
					if($pos_y_case == $y_max){
							if($pos_x_case == $x_max){
								$class = 'int_damier_hd';
							}elseif($pos_x_case != $x_min){
								$class = 'int_normal';
							}else{
								$class = 'int_damier_hg';
							}
					}elseif($pos_x_case == $x_max && $pos_y_case != $y_min){
						$class = 'int_normal';
					}elseif($pos_y_case == $y_min){
							if($pos_x_case == $x_min){
								$class = 'int_damier_bg';
							}elseif($pos_x_case != $x_max){
								$class = 'int_normal';
							}else{
								$class = 'int_damier_bd';
							}
					}elseif($pos_x_case == $x_min){
						$class = 'int_normal';
					}
						
						if($pos_y_case == $pos_y_perso+1 && $pos_x_case == $pos_x_perso && $damier_grille=="damier_grille")
							echo '<td width="45" height="39" class="damier_'.$get_plan.' '.$class.' '.$damier_grille.'_perso_haut">';
							elseif($pos_y_case == $pos_y_perso && $pos_x_case == $pos_x_perso-1 && $damier_grille=="damier_grille")
							echo '<td width="45" height="39" class="damier_'.$get_plan.' '.$class.' '.$damier_grille.'_perso_gauche">';
							elseif($pos_y_case == $pos_y_perso && $pos_x_case == $pos_x_perso && $damier_grille=="damier_grille")
							echo '<td width="45" height="39" class="damier_'.$get_plan.' '.$class.' '.$damier_grille.'_perso"><a name="perso" />';
							else echo '<td width="45" height="39" class="damier_'.$get_plan.' '.$class.' '.$damier_grille.'">';

						if(isset($deplacement)){
                                                    echo '<a href="'.$liendeplacement.'"><div id="'.$position.'-case" class="case deplacement">';
						} else {                                                          
                                                    echo "<div id='".$position."-case' class='case'>";
                                                }
						$type_info='aucun';

                                                if(isset($terrain)){
							echo "<div class='damier_terrain'><img id='decor' src='".$image_loc.$terrain['img'][0]."'></div>";
							$type_id=$terrain['id'][0];
							$type_info='terrain';
							}
							else {
									echo "<div class='damier_terrain'></div>";
									}
						if(isset($objet_complexe)){
								$inc=0;
									while(isset($objet_complexe['img'][$inc])){
										echo '<div class="damier_objet_c"><img src="'.$image_loc.$objet_complexe['img'][$inc].'"></div>';
										$type_id=$objet_complexe['id'][$inc];
										$type_info='objet_complexe';
										$inc++;
										}
									}
						if(isset($bouclier)){
									echo '<div class="damier_bouclier"><img src="'.$image_loc.$bouclier['img'][0].'"></div>';
									$type_id=$bouclier['id'][0];
									$type_info='bouclier';
									}
						if(isset($objet_simple)){
									echo "<div class='damier_objet'><img id='objet' src='".$image_loc.$objet_simple['img'][0]."'></div>";
									$type_id=$objet_simple['id'][0];
									$type_info='objet_simple';
									}else{
										echo "<div class='damier_objet'></div>";
									}
 						if(isset($artefact)){
									echo "<div class='damier_artefact' id='".$position."-artefact'><img src='".$image_loc.$artefact['img'][0]."'></div>";
									$type_id=$artefact['id'][0];
									$type_info='artefact';
									}else{
										echo "<div class='damier_artefact' id='".$position."-artefact'></div>";
									} 
						if(isset($porte)){
									echo '<div class="damier_porte"><img src="'.$image_loc.$porte['img'][0].'"></div>';
									$type_id=$porte['id'][0];
									$type_info='porte';
									}
						if(isset($perso)){
									echo '<div class="damier_perso"><img src="'.$image_loc.$perso['img'][0].'"></div>';
									echo '<div class="damier_galon sprite_G'.$perso['grade'][0].'g'.$perso['galon'][0].'"></div>';
									$type_id=$perso['id'][0];
									$type_info='perso';
									} 
						if(isset($deplacement)){
									echo '<div class="fleche"><img src="'.$image_loc.'deplacement/'.$deplacement.'.png"></div>';
									}                                                                            
						if($type_info!='aucun'){
							infobulle($type_info, $type_id, $cout, $liste_perso, $liste_terrain, $liste_artefact, $liste_objet_simple, $liste_objet_complexe, $liste_porte, $liste_bouclier,$zindex);
							}
                                                if(isset($deplacement)){  
                                                    echo '</div></a></td>';
                                                } else {                                                      
                                                    echo '</div></td>';
                                                }
					}	
				}
			echo '</tr>';
			if($height==(2*$vision+2)) {
				echo '</thead>';
			}
			if($height==1) {
				echo '</tbody>';
			}	
			if($height==0) {
				echo '</tfoot>';
			}	
				$zindex--;
			}
	echo '</table>';
//--------- Fin Damier ---------//
}else{

// Respawn des persos. Pour le moment tout le monde respawn sur terre.
echo "Votre personnage n'est plus de ce monde.<br/><br/>";
$id = $_SESSION['persos']['id'][0];
$race_id = $_SESSION['persos']['race'][$id];
$camp = recup_camp($race_id);
if($camp==1){
	echo "Vous pouvez choisir une zone dans laquelle vous aimeriez que votre nouvelle enveloppe s'offre au monde : <br/><br/>";
	echo "<table>";
	echo "<tr><form method='post' action='./index.php?perso_id=$id'><td><select name='cible_spawn'>";
	//recherche des zone de respawn possible
	$sql = '
		SELECT
			b.id,b.nom, b.pos_x, b.pos_y,
			COUNT(p.perso_id) AS nb_p
		FROM
			`damier_bouclier` b
		INNER JOIN `cartes` c
			ON c.id = b.carte_id
		LEFT JOIN `damier_persos` p 
			ON
				p.carte_id = b.carte_id
				AND
				(
					p.pos_x >= b.pos_x - b.type_id*2.5
					OR
					SUBSTR(c.circ,0,1) = "1" AND p.pos_x >= c.x_max - (b.pos_x - b.type_id*2.5 + c.x_min)
				)
				AND
				(
					p.pos_x <= b.pos_x + b.type_id*2.5
					OR
					SUBSTR(c.circ,0,1) = "1" AND p.pos_x <= c.x_min + (b.pos_x + b.type_id*2.5 - c.x_max)
				)
				AND
				(
					p.pos_y >= b.pos_y - b.type_id*2.5
					OR
					SUBSTR(c.circ,1,1) = "1" AND p.pos_y >= c.x_max - (b.pos_y - b.type_id*2.5 + c.y_min)
				)
				AND
				(
					p.pos_y <= b.pos_y + b.type_id*2.5
					OR
					SUBSTR(c.circ,1,1) = "1" AND p.pos_y <= c.y_min + (b.pos_y + b.type_id*2.5 - c.y_max)
				)
		GROUP BY b.id, b.type_id
		HAVING 
			b.type_id = 4
			OR (b.type_id = 3 AND nb_p <= 162)
			OR (b.type_id = 2 AND nb_p <= 48)
			OR (b.type_id = 1 AND nb_p <= 9)
		ORDER BY b.nom ASC';
		
	$search = mysql_query ($sql) or die (mysql_error().' <br />
	'.$sql);
	while($data = mysql_fetch_row ($search)){
		echo '<option value="',$data[0],'">',$data[1],' : ',$data[4],'</option>';	
	}
	
	echo "</select></td>";
	echo "<td><input type='submit' name='respawn' value='Se r&eacute;incarner'></td>";
	echo "</form></tr>";
	echo "</table><br/><br/>Ne faites pas trop de folies !";
	echo "<br/><br/>Nota : Ce choix augmente simplement les probabilit&eacute;s.";
	}
	else {
		echo "Offrir &agrave; votre &acirc;me une nouvelle enveloppe :<br/><br/>";
		echo "<form method='post' action='./index.php?perso_id=$id'>";
		echo "<input type='submit' name='respawn' value='Respawn'>";
		echo "</form>";
	}
}
?>
<!-- fin conteneur -->
</div>
<!-- Fin contour -->	
