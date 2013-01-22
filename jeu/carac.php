<!-- Debut contour -->
<div class="conteneur_st">
<!-- conteneur -->
	<?php
		echo "<table width='100%'>
						<tr>
							<td width='30%' align='center'>";
								if(isset($_SESSION['persos']['carte'][$id])){
									$carte_pos =	$_SESSION['persos']['carte'][$id];
									$plan = get_plan ($carte_pos);
									echo "Sur <a class='lien_carte' href='$root_url/carte/'>".$plan."</a>";
								}else echo "<b>Nullepart</b>";
								$format= 'd-m-Y';
								$format2=' H:i:s';
								$date = date($format);
								$date2= date($format2);
								echo " en X = <span id='PersoPosX'>".$_SESSION['persos']['pos_x'][$id] = $pos["pos_x"]."</span>"; 
								echo " <b>|</b> Y = <span id='PersoPosY'>".$_SESSION['persos']['pos_y'][$id] = $pos["pos_y"]."</span>";
								echo "<br />";
								echo "Le ".$date." 	&agrave; ".$date2."";						
// Affichage du rang d'xp du personnage, en tenant compte du grade (si G4 +1, si G5 +2)
								$perso_carac = recup_carac($perso_id, array('px', 'pi'));
									$perso_rang = calcul_rang($perso_carac['px']);
									$perso_grade= $race_grade['grade_id'];
									$perso_rang += ajuste_rang($perso_grade);
							//		echo $perso_rang ;
				
			echo "	</td>
							<td align='center'>";
								echo "<b>".nom_perso($perso_id)."</b> (".$perso_id.") | Grade ".$_SESSION['persos']['grade'][$id]." galon ".$_SESSION['persos']['galon'][$id]."<br />";
								echo "Prochain tour le ".date_fr($_SESSION['persos']['date_tour'][$id]);
			echo "	</td>
						</tr>
					</table>";
	?>
	<div class="action_ok" style="display:none" id="infos_action"></div>
<!-- fin conteneur -->
</div>
<!-- Fin contour -->	
<div class='separation'></div>

