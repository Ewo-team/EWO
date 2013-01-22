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
			$portes = "SELECT*FROM case_objet_simple";																									
			$resultat = mysql_query ($portes) or die (mysql_error());
			$p=0;
			while ($porte = mysql_fetch_array ($resultat)){
				//$porte[''];
							
				echo "<table id='".$porte['id']."-objet'>
				<tr>
					<td><b><span id='".$p."_nom_p' class='curspointer'><span id='".$p."_nom' onclick=\"edition_click(this.id,'nom','case_objet_simple','".$porte['id']."');\">".$porte['nom']."</span></span></b></td>
				</tr>
				<tr>
					<td><span id='".$p."_desc_p' class='curspointer'><span id='".$p."_desc' onclick=\"edition_click(this.id,'description','case_objet_simple','".$porte['id']."');\">".$porte['description']."</span></span></td>
				</tr>	
				<tr>
					<td>Nom de l'image : ".$porte['image']."</td>
				</tr>
				<tr>
					<td><img src='../images/".$porte['image']."' /></td>
				</tr>					
				<tr>
					<td>Pv Max: <span id='".$p."_pvmax_p' class='curspointer'><span id='".$p."_pvmax' onclick=\"edition_click(this.id,'pv_max','case_objet_simple','".$porte['id']."');\">".$porte['pv_max']."</span></span></td>
				</tr>
				<tr>
					<td>Poid : <span id='".$p."_poid_p' class='curspointer'><span id='".$p."_poid' onclick=\"edition_click(this.id,'poid','case_objet_simple','".$porte['id']."');\">".$porte['poid']."</span></span></td>
				</tr>					
				<tr>
					<td>Bloquant : ".$porte['bloquant']."</td>
				</tr>
				<tr>
					<td>Categorie : ";
				name_categorie('objet_simple',$porte['categorie_id']);
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
				<form name="porte" action='bdd_objet_simple.php' method='post'>
					<table>
						<tr>
							<td colspan="2"><?php select_icone('decors/objets/','simple'); ?></td>
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
							<td>Poid : </td>
							<td><input type='text' value='' name='poid' size='5' /> * en Kg</td>
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
							<td>Categorie :</td>
							<td>
								<?php liste_categorie('objet_simple'); ?>
							</td>
						</tr>
					</table>
					<input type='submit' value="Créer l'objet" />				
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
