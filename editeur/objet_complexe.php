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
			$portes = "SELECT*FROM case_objet_complexe WHERE nom!='Abysses'";																									
			$resultat = mysql_query ($portes) or die (mysql_error());
			$p = 0;
			while ($porte = mysql_fetch_array ($resultat)){
				//$porte[''];
							
				echo "<table id='".$porte['id']."-porte'>
				<tr>
					<td><b><span id='".$p."_nom_p' class='curspointer'><span id='".$p."_nom' onclick=\"edition_click(this.id,'nom','case_objet_complexe','".$porte['id']."');\">".$porte['nom']."</span></span></b></td>
				</tr>
				<tr>
					<td><span id='".$p."_desc_p' class='curspointer'><span id='".$p."_desc' onclick=\"edition_click(this.id,'description','case_objet_complexe','".$porte['id']."');\">".$porte['description']."</span></span></td>
				</tr>	
				<tr>
					<td>Nom de l'image : ".$porte['images']."</td>
				</tr>
				<tr>
					<td>				
						<table border='0' class='damier_corps' CELLPADDING='0' CELLSPACING='0'>";
						$i = 1;
						for($y=1;$y<=$porte['taille_y'];$y++){
							echo "<tr  height='33'>";
							for($x=1;$x<=$porte['taille_x'];$x++){
								echo "<td>";
									echo "<img src='../images/".$porte['images']."_".$i.".png' />";
									$i++;
								echo "</td>";
							}
							echo "</tr>";
						}
						echo "</table>
					</td>
				</tr>					
				<tr>
					<td>Pv Max: <span id='".$p."_pvmax_p' class='curspointer'><span id='".$p."_pvmax' onclick=\"edition_click(this.id,'pv_max','case_objet_complexe','".$porte['id']."');\">".$porte['pv_max']."</span></span></td>
				</tr>					
				<tr>
					<td>Bloquant : ".$porte['bloquant']."</td>
				</tr>				
				<tr>
					<td>Réparable: ".$porte['reparable']."</td>
				</tr>								
				<tr>
					<td>Taille X : ".$porte['taille_x']." / Taille Y : ".$porte['taille_y']."</td>
				</tr>			
				<tr>
					<td>Categorie : ";
				name_categorie('objet_complexe',$porte['categorie_id']);
				echo "</td>
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
				<form name="porte" action='bdd_objet_complexe.php' method='post'>
					<table>
						<tr>
							<td colspan="2"><?php select_icone('decors/objets_complexe/','complexe'); ?></td>
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
							<td>Bloquant :</td>
							<td>
								<select name="bloquant">
									<option value='1'>Oui</option>
									<option value='0'>Non</option>
								</select>
							</td>
						</tr>												
						<tr>
							<td>Réparable :</td>
							<td>
								<select name="reparable">
									<option value='1'>Oui</option>
									<option value='0'>Non</option>
								</select>
							</td>
						</tr>	
						<tr>
							<td>Taille X :</td>
							<td><input type='text' value='' name='taille_x' size='5' />	Taille Y : <input type='text' value='' name='taille_y' size='5' /></td>
						</tr>						
						<tr>
							<td>Categorie :</td>
							<td>
								<?php liste_categorie('objet_complexe'); ?>
							</td>
						</tr>
					</table>
					<input type='submit' value="Créer l'objet complexe" />				
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
