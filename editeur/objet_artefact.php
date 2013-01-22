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
			$portes = "SELECT*FROM case_artefact";																									
			$resultat = mysql_query ($portes) or die (mysql_error());
			$p = 0;
			while ($porte = mysql_fetch_array ($resultat)){
				//$porte[''];
							
				echo "<table id='".$porte['id']."-porte'>
				<tr>
					<td><b><span id='".$p."_nom_p' class='curspointer'><span id='".$p."_nom' onclick=\"edition_click(this.id,'nom','case_artefact','".$porte['id']."');\">".$porte['nom']."</span></span></b></td>
				</tr>
				<tr>
					<td><span id='".$p."_desc_p' class='curspointer'><span id='".$p."_desc' onclick=\"edition_click(this.id,'description','case_artefact','".$porte['id']."');\">".$porte['description']."</span></span></td>
				</tr>	
				<tr>
					<td>Nom de l'image : ".$porte['image']."</td>
				</tr>
				<tr>
					<td>				
						<img src='../images/".$porte['image']."' />
					</td>
				</tr>					
				<tr>
					<td>Pv Max: <span id='".$p."_pvmax_p' class='curspointer'><span id='".$p."_pvmax' onclick=\"edition_click(this.id,'pv_max','case_artefact','".$porte['id']."');\">".$porte['pv_max']."</span></span></td>
				</tr>					
				<tr>
					<td>Rareté : <span id='".$p."_rare_p' class='curspointer'><span id='".$p."_rare' onclick=\"edition_click(this.id,'rarete','case_artefact','".$porte['id']."');\">".$porte['rarete']."</span></span></td>
				</tr>				
				<tr>
					<td>Cout : <span id='".$p."_cout_p' class='curspointer'><span id='".$p."_cout' onclick=\"edition_click(this.id,'cout','case_artefact','".$porte['id']."');\">".$porte['cout']."</span></span></td>
				</tr>								
				<tr>
					<td>Poid : <span id='".$p."_poid_p' class='curspointer'><span id='".$p."_poid' onclick=\"edition_click(this.id,'poid','case_artefact','".$porte['id']."');\">".$porte['poid']."</span></span> Kg</td>
				</tr>	
				<tr>
					<td>Statut : <span id='".$p."_statut_p' class='curspointer'><span id='".$p."_statut' onclick=\"edition_click(this.id,'consom','case_artefact','".$porte['id']."');\">".$porte['consom']."</span></span></td>
				</tr>							
				<tr>
					<td>Categorie : ";
				name_categorie('artefact',$porte['categorie_id']);
				echo "</td>
				</tr>
				<tr>
					<td>[ <span class='curspointer' onClick=\"Effect.toggle('voir_alter".$porte['id']."','blind',{ duration: 0.1 }); return false;\">Alteration de caracteristique de l'artefact</span> ]</td>
				</tr>
				<tr>
					<td>
					<span id='voir_alter".$porte['id']."'' style='display:none;'>";
						$alters = "SELECT*FROM caracs_alter_artefact WHERE case_artefact_id = '".$porte['id']."'";																									
						$resultats = mysql_query ($alters) or die (mysql_error());
						$alter = mysql_fetch_array ($resultats);
			echo "<table id='".$alter['case_artefact_id']."-alter' style='background-color:white;'>
				<tr>
					<td>alter_pa: <span id='".$p."_alter_pa_p' class='curspointer'><span id='".$p."_alter_pa' onclick=\"edition_click(this.id,'alter_pa','caracs_alter_artefact','".$alter['id']."');\">".$alter['alter_pa']."</span></span></td>
				</tr>
				<tr>
					<td>alter_mouv: <span id='".$p."_alter_mouv_p' class='curspointer'><span id='".$p."_alter_mouv' onclick=\"edition_click(this.id,'alter_mouv','caracs_alter_artefact','".$alter['id']."');\">".$alter['alter_mouv']."</span></span></td>
				</tr>	
					<td>alter_def: <span id='".$p."_alter_def_p' class='curspointer'><span id='".$p."_alter_def' onclick=\"edition_click(this.id,'alter_def','caracs_alter_artefact','".$alter['id']."');\">".$alter['alter_def']."</span></span></td>
				</tr>	
				<tr>
					<td>alter_att: <span id='".$p."_alter_att_p' class='curspointer'><span id='".$p."_alter_att' onclick=\"edition_click(this.id,'alter_att','caracs_alter_artefact','".$alter['id']."');\">".$alter['alter_att']."</span></span></td>
				</tr>									
				<tr>
					<td>alter_recup_pv: <span id='".$p."_alter_recup_pv_p' class='curspointer'><span id='".$p."_alter_recup_pv' onclick=\"edition_click(this.id,'alter_recup_pv','caracs_alter_artefact','".$alter['id']."');\">".$alter['alter_recup_pv']."</span></span></td>
				</tr>					
				<tr>
					<td>alter_force : <span id='".$p."_alter_force_p' class='curspointer'><span id='".$p."_alter_force' onclick=\"edition_click(this.id,'alter_force','caracs_alter_artefact','".$alter['id']."');\">".$alter['alter_force']."</span></span></td>
				</tr>				
				<tr>
					<td>alter_perception : <span id='".$p."_alter_perception_p' class='curspointer'><span id='".$p."_alter_perception' onclick=\"edition_click(this.id,'alter_perception','caracs_alter_artefact','".$alter['id']."');\">".$alter['alter_perception']."</span></span></td>
				</tr>				
					</table>";
		echo "</span>
					</td>
				</tr>										
				</table>
				<br />";
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
				<form name="porte" action='bdd_objet_artefact.php' method='post'>
					<table>
						<tr>
							<td colspan="2"><?php select_icone('decors/artefacts/','simple'); ?></td>
						</tr>
						<tr>
							<td>Nom : </td>
							<td><input type='text' value='' name='nom_objet' size='30' /></td>
						</tr>	
						<tr>
							<td>Description :</td>
							<td><input type='text' value='' name='description_objet' size='50' /></td>
						</tr>							
						<tr>
							<td>Pv max : </td>
							<td><input type='text' value='' name='pv_max' size='5' /></td>
						</tr>							
						<tr>
							<td>Rareté :</td>
							<td>
								<input type='text' value='' name='rarete' size='5' /> * en %, 1 rare, 100 courant
							</td>
						</tr>												
						<tr>
							<td>Cout :</td>
							<td>
								<input type='text' value='' name='cout' size='5' />
							</td>
						</tr>	
						<tr>
							<td>Poid :</td>
							<td><input type='text' value='' name='poid' size='5' /> * en Kilogramme</td>
						</tr>						
						<tr>
							<td>Categorie :</td>
							<td>
								<?php liste_categorie('artefact'); ?>
							</td>
						</tr>
						<tr>
							<td>Statut :</td>
							<td>
								<select name='consom'>
									<option value='0'>0: Ni consommable ni activable</option>
									<option value='1' selected>1: Activable</option>
									<option value='2'>2: Activé en permanence</option>
									<option value='3'>3: Consommable</option>
									<option value='4'>4: En cours de consommation</option>
								</select>
							</td>
						</tr>							
					</table>
					<input type='submit' value="Créer l'artefact" />				
				</form>
			<!-- fin conteneur -->
			</div>
		</div>
	</div>
</div>
<!-- Fin du coin -->
<!-- Debut du coin -->
<div class="upperleft" id='coin_100'>
	<div class="upperright">
		<div class="lowerleft">
			<div class="lowerright">
			<!-- conteneur -->
				<form name='up_image' enctype="multipart/form-data" action="upload_image.php" method="POST">
					<table>
					
					<tr>
						<td  colspan="2">
							<b>Image &agrave; upper :</b><br />
						<input name="fichier" type="file" />
						<input type="hidden" name="dest" value="decors/artefacts" />
							<i>Image : png, jpg, gif; taille maxi 45*33</i>
						</td>
					</tr>
					</table>
					<input type='submit' value="Uploader l'image" />				
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
