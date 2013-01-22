<?php
//-- Header --
$root_url = "..";
include($root_url."/template/header_new.php");
/*-- Connexion basic requise --*/
ControleAcces('admin',1);
/*-----------------------------*/
include ("fonctions.php");
//------------

$js->addScript('editeur');
?>

	<?php	include ("menu.php"); ?>

<!-- Debut du coin -->
<div class="upperleft" id='coin_100'>
	<div class="upperright">
		<div class="lowerleft">
			<div class="lowerright">
			<!-- conteneur -->
	
			<?php	
			$portes = "SELECT*FROM cartes";																									
			$resultat = mysql_query ($portes) or die (mysql_error());
			$p=1;
			while ($porte = mysql_fetch_array ($resultat)){
				//$porte[''];
				echo "<table>
				<tr>
					<td><b><span id='".$p."_nom_p' class='curspointer'><span id='".$p."_nom' onclick=\"edition_click(this.id,'nom','cartes','".$porte['id']."');\">".$porte['nom']."</span></span></b></td>
				</tr>
				<tr> 
					<td><span id='".$p."_desc_p' class='curspointer'><span id='".$p."_desc' onclick=\"edition_click(this.id,'description','cartes','".$porte['id']."');\">".$porte['description']."</span></span></td>
				</tr>
					<tr>
					<td>Circulaire: <span id='".$p."_circ_p' class='curspointer'><span id='".$p."_circ' onclick=\"edition_click(this.id,'circ','cartes','".$porte['id']."');\">".$porte['circ']."</span></span></td>
					<td>Infinie: <span id='".$p."_infini_p' class='curspointer'><span id='".$p."_infini' onclick=\"edition_click(this.id,'infini','cartes','".$porte['id']."');\">".$porte['infini']."</span></span></td>
				</tr>				
				<tr>
					<td>xmin: <span id='".$p."_xmin_p' class='curspointer'><span id='".$p."_xmin' onclick=\"edition_click(this.id,'x_min','cartes','".$porte['id']."');\">".$porte['x_min']."</span></span></td>
					<td>xmax: <span id='".$p."_xmax_p' class='curspointer'><span id='".$p."_xmax' onclick=\"edition_click(this.id,'x_max','cartes','".$porte['id']."');\">".$porte['x_max']."</span></span></td>
				</tr>									
				<tr>
					<td>ymin: <span id='".$p."_ymin_p' class='curspointer'><span id='".$p."_ymin' onclick=\"edition_click(this.id,'y_min','cartes','".$porte['id']."');\">".$porte['y_min']."</span></span></td>
					<td>ymax: <span id='".$p."_ymax_p' class='curspointer'><span id='".$p."_ymax' onclick=\"edition_click(this.id,'y_max','cartes','".$porte['id']."');\">".$porte['y_max']."</span></span></td>
				</tr>									
				<tr>
					<td>visible_x_min: <span id='".$p."_xminv_p' class='curspointer'><span id='".$p."_xminv' onclick=\"edition_click(this.id,'visible_x_min','cartes','".$porte['id']."');\">".$porte['visible_x_min']."</span></span></td>
					<td>visible_x_max : <span id='".$p."_xmaxv_p' class='curspointer'><span id='".$p."_xmaxv' onclick=\"edition_click(this.id,'visible_x_max','cartes','".$porte['id']."');\">".$porte['visible_x_max']."</span></span></td>
				</tr>
				<tr>
					<td>visible_y_min: <span id='".$p."_yminv_p' class='curspointer'><span id='".$p."_yminv' onclick=\"edition_click(this.id,'visible_y_min','cartes','".$porte['id']."');\">".$porte['visible_y_min']."</span></span></td>
					<td>visible_y_max <span id='".$p."_ymaxv_p' class='curspointer'><span id='".$p."_ymaxv' onclick=\"edition_click(this.id,'visible_y_max','cartes','".$porte['id']."');\">: ".$porte['visible_y_max']."</span></span></td>
				</tr>					
				<tr>
					<td><br /></td>
				</tr>	
			</table>";
			$p++;
			}
			?>
			<!-- fin conteneur -->
			</div>
		</div>
	</div>
</div>
<!-- Fin du coin -->
<br />

<!-- Debut du coin -->
<div class="upperleft" id='coin_100'>
	<div class="upperright">
		<div class="lowerleft">
			<div class="lowerright">
			<!-- conteneur -->
				<form name="carte" action='bdd_carte.php' method='post'>
					<table>
						<tr>
							<td>Nom : </td>
							<td><input type='text' value='' name='nom_carte' size='30' /></td>
						</tr>	
						<tr>
							<td>Description :</td>
							<td><input type='text' value='' name='description_carte' size='50' /></td>
						</tr>	
						<tr>
							<td>Circulaire : </td>
							<td><input type='text' value='' name='circ' size='4' /> * 00</td>
						</tr>							
						<tr>
							<td>Infinie : </td>
							<td><input type='text' value='' name='infini' size='4' /> * 0000</td>
						</tr>												
						<tr>
							<td>x min :</td>
							<td><input type='text' value='' name='x_min' size='4' /> x max : <input type='text' value='' name='x_max' size='4' /> * Taille de la carte</td>
						</tr>		
						<tr>
							<td>y min : </td>
							<td><input type='text' value='' name='y_min' size='4' /> y max : <input type='text' value='' name='y_max' size='4' /></td>
						</tr>
						<tr>
							<td>visible x min :</td>
							<td><input type='text' value='' name='visible_x_min' size='4' /> visible x max : <input type='text' value='' name='visible_x_max' size='4' /> * Partie visible de la carte</td>
						</tr>		
						<tr>
							<td>visible y min :</td>
							<td><input type='text' value='' name='visible_y_min' size='4' /> visible y max : <input type='text' value='' name='visible_y_max' size='4' /></td>
						</tr>						
					</table>
					<input type='submit' value="Création de la carte"/>
				</form>
			<!-- fin conteneur -->
			</div>
		</div>
	</div>
</div>
<!-- Fin du coin -->
<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
