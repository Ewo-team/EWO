<p>Damier à éditer : </p>

<table>
	<tr>
		<td>
			<form name="plan_gauche" action='index.php' method='post'>
				<input type='hidden' value='-5' name='ModcoordX'/>
				<input type='hidden' value='0' name='ModcoordY'/>
				<input type="image" src="../images/editeur/28.png" />	
			</form>
		</td>
		<td>
			<form name="plan_haut" action='index.php' method='post'>
				<input type='hidden' value='0' name='ModcoordX'/>
				<input type='hidden' value='5' name='ModcoordY'/>
				<input type="image" src="../images/editeur/19.png" />	
			</form>
		</td>
		<td>
			<form name="plan_bas" action='index.php' method='post'>
				<input type='hidden' value='0' name='ModcoordX'/>
				<input type='hidden' value='-5' name='ModcoordY'/>
				<input type="image" src="../images/editeur/24.png" />	
			</form>
		</td>
		<td>
			<form name="plan_droite" action='index.php' method='post'>
				<input type='hidden' value='5' name='ModcoordX'/>
				<input type='hidden' value='0' name='ModcoordY'/>
				<input type="image" src="../images/editeur/23.png" />	
				Déplacement 5 par 5.
			</form>		
		</td>
	</tr>
</table>

 
<?php
//-- Récupération des informations
if (isset($_SESSION['coordX']) OR isset($_SESSION['coordY'])){
	$pos_x_perso = $_SESSION['coordX'];
	$pos_y_perso = $_SESSION['coordY'];
}else{
	$pos_x_perso = $pos_x_perso_post;
	$pos_y_perso = $pos_y_perso_post;
}

if (isset($_SESSION['plan'])){
	$carte_pos = $_SESSION['plan'];
}else{
	$carte_pos = $carte_pos_post;
}

if (isset($_SESSION['Vision'])){
	$vision = $_SESSION['Vision'];
}else{
	$vision = $vision_post;
}

$is_spawn = true;

$image_loc = "./../images/";
//---------------------------


$sql="SELECT * FROM cartes WHERE id='$carte_pos'";
$resultat = mysql_query ($sql) or die (mysql_error());
$carte = mysql_fetch_array ($resultat);

$x_min_carte = $carte['x_min'];
$x_max_carte = $carte['x_max'];

$y_min_carte = $carte['y_min'];
$y_max_carte = $carte['y_max'];

if(($pos_y_perso-$vision)>($y_min_carte) || !$carte['circ'][1]){
	$y_min = $pos_y_perso-$vision;
}else{
	$y_min = $y_max_carte + ($pos_y_perso-$vision-$y_min_carte);
}
		
if(($pos_y_perso+$vision)<($y_max_carte) || !$carte['circ'][1]){
	$y_max     = $pos_y_perso+$vision;
}else{
	$y_max     = $y_min_carte + ($pos_y_perso+$vision-$y_max_carte);
}
		
if(($pos_x_perso-$vision)>($x_min_carte) || !$carte['circ'][0]){
	$x_min    = $pos_x_perso-$vision;
}else{
	$x_min    = $x_max_carte + ($pos_x_perso-$vision-$x_min_carte);
}
		
if(($pos_x_perso+$vision)<($x_max_carte) || !$carte['circ'][0]){
	$x_max     = $pos_x_perso+$vision;
}else{
	$x_max     = $x_min_carte + ($pos_x_perso+$vision-$x_max_carte);
}

include("infos_damier_editeur.php");
//include("../jeu/fonctions.php");

//--------- Damier ---------//
echo '<table class="damier_corps" CELLPADDING="0" CELLSPACING="0" border="0">';
	for($height=(2*$vision+2);$height>=0;$height--){
		echo '<tr height="33">';
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
					$terrain = rchch_case($pos_x_case,$pos_y_case,$plan,$liste_terrain);
					$artefact = rchch_case($pos_x_case,$pos_y_case,$plan,$liste_artefact);
					$objet_simple = rchch_case($pos_x_case,$pos_y_case,$plan,$liste_objet_simple);
					$objet_complexe = rchch_case($pos_x_case,$pos_y_case,$plan,$liste_objet_complexe);
					$porte = rchch_case($pos_x_case,$pos_y_case,$plan,$liste_porte);
					$bouclier = rchch_case($pos_x_case,$pos_y_case,$plan,$liste_bouclier);
					//$perso = rchch_case($pos_x_case,$pos_y_case,$plan,$liste_perso);
					//$cout = rchch_cout($pos_x_case,$pos_y_case,$plan, $liste_perso, $liste_terrain, $liste_objet_simple, $liste_objet_complexe, $liste_bouclier);
					
					$position = $pos_x_case.":".$pos_y_case;

					if($plan == 1){
						$get_plan = 'terre';
					}elseif($plan == 2){
						$get_plan = 'paradis';
					}elseif($plan == 3){
						$get_plan = 'enfer';
					}
					
				if($pos_y_case == $y_max+1){
						if($pos_x_case == $x_max+1){
							echo '<td width="45" height="33" class="damier_hd" align="center">';
							echo "<span><img src='../images/damier_vide.png' /></span>";
							echo '</td>';
						}elseif($pos_x_case != $x_min-1){
							echo '<td width="45" height="33" class="damier_case" align="center">';
							echo "<span>$pos_x_case</span>";
							echo '</td>';
						}else{
							echo '<td width="45" height="33" class="damier_hg" align="center">';
							echo "<span><img src='../images/damier_vide.png' /></span>";
							echo '</td>';
							}
				}elseif($pos_x_case == $x_max+1 && $pos_y_case != $y_min-1){
						echo '<td width="45" height="33" class="damier_case" align="center">';
						echo "<span>$pos_y_case</span>";
						echo '</td>';
				}elseif($pos_y_case == $y_min-1){
						if($pos_x_case == $x_min-1){
							echo '<td width="45" height="33" class="damier_bg" align="center">';
							echo "<span><img src='../images/damier_vide.png' /></span>";
							echo '</td>';
						}elseif($pos_x_case != $x_max+1){
							echo '<td width="45" height="33" class="damier_case" align="center">';
							echo "<span>$pos_x_case</span>";
							echo '</td>';
						}else{
							echo '<td width="45" height="33" class="damier_bd" align="center">';
							echo "<span><img src='../images/damier_vide.png' /></span>";
							echo '</td>';
							}
				}elseif($pos_x_case == $x_min-1){
						echo '<td width="45" height="33" class="damier_case" align="center">';
						echo "<span>$pos_y_case</span>";
						echo '</td>';
				}else{
				
						echo '<td width="45" height="33" class="damier_'.$get_plan.'">';

						echo "<div id='".$position."-case' class='case'>";
						$type_info='aucun';
						if(isset($terrain)){
						
							echo "<div class='damier_terrain' id='".$position."-decor' onclick=\"damier('".$position."')\"><img id='decor' src='".$image_loc.$terrain['img']."'></div>";
							$type_id=$terrain['id'];
							$type_info='terrain';
							}
							else {
									echo "<div class='damier_terrain' id='".$position."-decor' onclick=\"damier('".$position."')\"></div>";
									}
						if(isset($porte)){
									echo '<div class="damier_porte"><img src="'.$image_loc.$porte['img'].'"></div>';
									$type_id=$porte['id'];
									$type_info='porte';
									}
						if(isset($objet_complexe)){
									echo '<div class="damier_objet_c"><img src="'.$image_loc.$objet_complexe['img'].'"></div>';
									$type_id=$objet_complexe['id'];
									$type_info='objet_complexe';
									}
						if(isset($bouclier)){
									echo '<div class="damier_bouclier"><img src="'.$image_loc.$bouclier['img'].'"></div>';
									$type_id=$bouclier['id'];
									$type_info='bouclier';
									}
						if(isset($objet_simple)){
									echo "<div class='damier_objet' id='".$position."-objet' onclick=\"damier('".$position."')\"><img id='objet' src='".$image_loc.$objet_simple['img']."'></div>";
									$type_id=$objet_simple['id'];
									$type_info='objet_simple';
									}else{
										echo "<div class='damier_objet' id='".$position."-objet' onclick=\"damier('".$position."')\"></div>";
									}
 						if(isset($artefact)){
									echo "<div class='damier_artefact' id='".$position."-artefact' onclick=\"damier('".$position."')\"><img src='".$image_loc.$artefact['img']."'></div>";
									$type_id=$artefact['id'];
									$type_info='artefact';
									}else{
										echo "<div class='damier_artefact' id='".$position."-artefact' onclick=\"damier('".$position."')\"></div>";
									} 
						echo '</div></td>';
					}	
				}
			echo '</tr>';
		}
	echo '</table>';
//--------- Fin Damier ---------//
?>
