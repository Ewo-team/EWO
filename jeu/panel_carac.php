<!-- Debut contour -->
<div class="block conteneur" id="block-4">
<div class='conteneur_titre'>Caractéristiques </div>
<!-- conteneur --><input type="hidden" id="perso_id" value="<?php echo $_SESSION['persos']['id'][0]; ?>">
			<table width='100%'>		
				<tr>
					<td id="carac_xp"><a href="../persos/upgrades/">
					<?php	echo "Px ".$caracs['px']." | Pi ".$caracs['pi'].""; ?>
                                        </a></td>
                                        <td><span>(Rang <?php echo $perso_rang; ?>)</span></td>
				</tr>
				<tr>
					<td>
					<?php
						$pv = $caracs['pv']/$caracs_max['pv']*100;
						if ($pv > 100){
							$pv = 100;
							$classcolor = "color_blue";
						}else{
							$classcolor = "color_green";
						}
						echo "Pv ";
					?>
					</td>
					<td>
					<?php
						echo "<div id='carac_pv' class='tab_carac_td'><div class='caracs_sup'>".$caracs['pv']."/".$caracs_max['pv']."</div>
									<span class='color_red'><span class='".$classcolor."' style='width:".$pv."%;'></span></span></div>";
					?>
					</td>					
				</tr>
				<tr>
					<td>
					<?php
						if ($caracs['malus_def'] == 0){
							$malus = 100;
							$classcolor = "color_green";
						}else{
							$malus = 0;
						}
						echo "Malus ";
					?>
					</td>
					<td>
					<?php
						echo "<div id='carac_malus' class='tab_carac_td'><div class='caracs_sup'>".$caracs['malus_def']."</div>
									<span class='color_red'><span class='".$classcolor."' style='width:".$malus."%;'></span></span></div>";
					?>
					</td>					
				</tr>							
				<tr>
					<td width='70%'>
					<?php 
						$pa = ($caracs['pa']+$caracs['pa_dec']/10)/($caracs_max['pa']+$caracs_max['pa_dec']/10)*100;

						if ($pa > 100){
							$pa = 100;
							$classcolor = "color_blue";
						}else{
							$classcolor = "color_green";
						}
						echo "Pa ";
				  ?>
					</td>
					<td>
					<?php
						echo "<div id='carac_pa' class='tab_carac_td'><div class='caracs_sup'>".($caracs['pa']+$caracs['pa_dec']/10)."/".($caracs_max['pa']+$caracs_max['pa_dec']/10)."</div>
						<span class='color_red'><span class='".$classcolor."' style='width:".$pa."%;'></span></span></div>";
					?>
					</td>					
				</tr>	
				<tr>
					<td>
					<?php
						$mouv = $caracs['mouv']/$caracs_max['mouv']*100;
						if ($mouv > 100){
							$mouv = 100;
							$classcolor = "color_blue";
						}else{
							$classcolor = "color_green";
						}						
						echo "Mouv ";
					?>
					</td>
					<td>
					<?php
						echo "<div id='carac_mouv' class='tab_carac_td'><div class='caracs_sup'>".$caracs['mouv']."/".$caracs_max['mouv']."</div>
									<span class='color_red'><span class='".$classcolor."' style='width:".$mouv."%;'></span></span></div>";										
					?>
					</td>					
				</tr>	
				<tr>
					<td>
					<?php
						if ($caracs['res_mag'] > $caracs_max['res_mag']){
							$malus = 100;
							$classcolor = "color_blue";
						}else{
							$malus = 100+$caracs['res_mag']-$caracs_max['res_mag'];
							$classcolor = "color_green";
						}
						echo "Res Magique";
					?>
					</td>
					<td>
					<?php
						echo "<div id='carac_res_mag' class='tab_carac_td'><div class='caracs_sup'>".$caracs['res_mag']."%</div>
									<span class='color_red'><span class='".$classcolor."' style='width:".$malus."%;'></span></span></div>";
					?>
					</td>					
				</tr>				
				<tr>
					<td>
					<?php
					if(floor($caracs_max['recup_pv']*$caracs_max['pv']/100)){
						$recuppv = floor($caracs['recup_pv']*$caracs_max['pv']/100)/floor($caracs_max['recup_pv']*$caracs_max['pv']/100)*100;
					}
					else $recuppv=0;
						if ($recuppv > 100){
							$recuppv = 100;
							$classcolor = "color_blue";
						}else{
							$classcolor = "color_green";
						}
						echo "R&eacute;cup&eacute;ration Pv ";
					?>
					</td>
					<td>
					<?php
						echo "<div id='carac_recup_pv' class='tab_carac_td'><div class='caracs_sup'>".floor($caracs['recup_pv']*$caracs_max['pv']/100)."/".floor($caracs_max['recup_pv']*$caracs_max['pv']/100)."</div>
									<span class='color_red'><span class='".$classcolor."' style='width:".$recuppv."%;'></span></span></div>";					
					?>
					</td>					
				</tr>
				<tr>
					<td>
					<?php
						$tab_recup_malus_actu = recup_malus($caracs['recup_pv'], $caracs_max['pv']);
						$tab_recup_malus = recup_malus($caracs_max['recup_pv'], $caracs_max['pv']);
						$recup_malus= ($tab_recup_malus_actu["recup_fixe"]/$tab_recup_malus["recup_fixe"])*100;
						if ($recup_malus > 100){
							$recup_malus = 100;
							$classcolor = "color_blue";
						}else{
							$classcolor = "color_green";
						}
						echo "R&eacute;cup'Malus ";
					?>
					</td>
					<td>
					<?php
						echo "<div id='carac_recup_malus' class='tab_carac_td'><div class='caracs_sup'>".$tab_recup_malus_actu["recup_fixe"]."/".$tab_recup_malus["recup_fixe"]."</div>
									<span class='color_red'><span class='".$classcolor."' style='width:".$recup_malus."%;'></span></span></div>";										
					?>
					</td>					
				</tr>		
				<tr>
					<td>
					<?php
						$force = $caracs['force']/$caracs_max['force']*100;
						if ($force > 100){
							$force = 100;
							$classcolor = "color_blue";
						}else{
							$classcolor = "color_green";
						}
						echo "Force ";
					?>
					</td>
					<td>
					<?php
						echo "<div id='carac_force' class='tab_carac_td'><div class='caracs_sup'>".$caracs['force']."/".$caracs_max['force']."</div>
									<span class='color_red'><span class='".$classcolor."' style='width:".$force."%;'></span></span></div>";
					?>
					</td>					
				</tr>	
				<tr>
					<td>
					<?php
						$perception = $caracs['perception']/$caracs_max['perception']*100;
						if ($perception > 100){
							$perception = 100;
							$classcolor = "color_blue";
						}else{
							$classcolor = "color_green";
						}
						echo "Perception ";
					?>
					</td>
					<td>
					<?php
						echo "<div id='carac_percept' class='tab_carac_td'><div class='caracs_sup'>".$caracs['perception']."/".$caracs_max['perception']."</div>
									<span class='color_red'><span class='".$classcolor."' style='width:".$perception."%;'></span></span></div>";															
					?>
					</td>					
				</tr>
				<tr>
					<td>
					<?php
						if ($caracs_max['magie'] == 0 AND $caracs['magie'] > 0){
							$magie = 100;
							$classcolor = "color_blue";
						}elseif ($caracs_max['magie'] == 0 AND $caracs['magie'] == 0){
							$magie = 100;
							$classcolor = "color_green";
						}else{
							$magie = $caracs['magie']/$caracs_max['magie']*100;
							if ($magie > 100){
								$magie = 100;
								$classcolor = "color_blue";
							}else{
								$classcolor = "color_green";
							}		
						}	
						echo "Niveau de magie ";
					?>
					</td>
					<td>
					<?php
						echo "<div id='carac_magie' class='tab_carac_td'><div class='caracs_sup'>".$caracs['magie']."/".$caracs_max['magie']."</div>
									<span class='color_red'><span class='".$classcolor."' style='width:".$magie."%;'></span></span></div>";
					?>					
					</td>					
				</tr>							
			</table>
<!-- fin conteneur -->
</div>
<!-- Fin contour -->	
<div class='separation'></div>
