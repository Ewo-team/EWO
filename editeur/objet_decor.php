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
//$js->addScript('jscolor');

?>
	<?php	include ("menu.php"); ?>
	
<!-- Debut du coin -->
<div class="upperleft" id='coin_100'>
	<div class="upperright">
		<div class="lowerleft">
			<div class="lowerright">
			<!-- conteneur -->
			<?php	
			$portes = "SELECT*FROM case_terrain";																									
			$resultat = mysql_query ($portes) or die (mysql_error());
			$p=1;
			while ($porte = mysql_fetch_array ($resultat)){
				//$porte[''];
							
				echo "<table id='".$porte['id']."-porte'>
				<tr>
					<td><b><span id='".$p."_nom_p' class='curspointer'><span id='".$p."_nom' onclick=\"edition_click(this.id,'nom','case_terrain','".$porte['id']."');\">".$porte['nom']."</span></span></b></td>
				</tr>
				<tr>
					<td>Nom de l'image : <span id='".$p."_image_p' class='curspointer'><span id='".$p."_image' onclick=\"edition_click(this.id,'image','case_terrain','".$porte['id']."');\">".$porte['image']."</span></span></td>
				</tr>
				<tr>
					<td>				
						<img src='../images/".$porte['image']."' />
					</td>
				</tr>					
				<tr>
					<td>Couleur Hexa: <span id='".$p."_coul_p' class='curspointer'><span id='".$p."_coul' onclick=\"edition_click(this.id,'couleur','case_terrain','".$porte['id']."');\">".$porte['couleur']."</span></span></td>
				</tr>		
				<tr>
					<td>Cout mouvement: <span id='".$p."_mouv_p' class='curspointer'><span id='".$p."_mouv' onclick=\"edition_click(this.id,'mouv','case_terrain','".$porte['id']."');\">".$porte['mouv']."</span></span></td>
				</tr>					
				<tr>
					<td>Categorie : ";
				name_categorie('terrain',$porte['categorie_id']);
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
				<form name="porte" action='bdd_terrain.php' method='post'>
					<table>
						<tr>
							<td colspan="2"><?php select_icone('decors/motifs/','simple'); ?></td>
						</tr>
						<tr>
							<td>Nom : </td>
							<td><input type='text' value='' name='nom_objet' size='30' /></td>
						</tr>						
						<tr>
							<td>Couleur Hexa : </td>
							<td><input type='text' value='' name='couleur' size='5' class="color" /></td>
						</tr>							
						<tr>
							<td>Cout mouvement :</td>
							<td>
								<input type='text' value='' name='mouv' size='5' />
							</td>
						</tr>																	
						<tr>
							<td>Categorie :</td>
							<td>
								<?php liste_categorie('terrain'); ?>
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
<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
