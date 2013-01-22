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
			<table width='100%'>
				<tr>
					<th>Categorie Objet simple</th>
					<th>Categorie Objet complexe</th>
				</tr>
				<tr>
					<td width='50%' valign='top'>
					<?php	
					$portes = "SELECT*FROM categorie_objet_simple";
					$resultat = mysql_query ($portes) or die (mysql_error());
					$q = 0;
					while ($porte = mysql_fetch_array ($resultat)){
				
						echo "<table id='".$porte['id']."-porte'>
						<tr>
							<td><b><span id='".$q."_nomobjs_p' class='curspointer'><span id='".$q."_nomobjs' onclick=\"edition_click(this.id,'nom','categorie_objet_simple','".$porte['id']."');\">".$porte['nom']."</span></span></b></td>
						</tr>	
						<tr>
							<td><span id='".$q."_descobjs_p' class='curspointer'><span id='".$q."_descobjs' onclick=\"edition_click(this.id,'description','categorie_objet_simple','".$porte['id']."');\">".$porte['description']."</span></span></td>
						</tr>	
						<tr>
							<td><br /></td>
						</tr>				
						</table>";
						$q++;
					}			
					?>					
					</td>
					<td width='50%' valign='top'>
					<?php	
					$portes = "SELECT*FROM categorie_objet_complexe";
					$resultat = mysql_query ($portes) or die (mysql_error());
					$r=0;
					while ($porte = mysql_fetch_array ($resultat)){
				
						echo "<table id='".$porte['id']."-porte'>
						<tr>
							<td><b><span id='".$r."_nomobjc_p' class='curspointer'><span id='".$r."_nomobjc' onclick=\"edition_click(this.id,'nom','categorie_objet_complexe','".$porte['id']."');\">".$porte['nom']."</span></span></b></td>
						</tr>	
						<tr>
							<td><span id='".$r."_descobjc_p' class='curspointer'><span id='".$r."_descobjc' onclick=\"edition_click(this.id,'description','categorie_objet_complexe','".$porte['id']."');\">".$porte['description']."</span></span></td>
						</tr>	
						<tr>
							<td><br /></td>
						</tr>				
						</table>";
						$r++;
					}			
					?>					
					</td>									
				</tr>
			</table>
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
				<form name="porte" action='bdd_categorie.php' method='post'>
					<table>
						<tr>
							<td>Nom : </td>
							<td><input type='text' value='' name='nom' size='30' /></td>
						</tr>	
						<tr>
							<td>Description :</td>
							<td><input type='text' value='' name='description' size='50' /></td>
						</tr>	
						<tr>
							<td>Categories :</td>
							<td>
								<select name="statut">
									<option value='categorie_terrain'>Categorie Terrain</option>
									<option value='categorie_objet_simple'>Categorie Objet simple</option>
									<option value='categorie_objet_complexe'>Categorie Objet complexe</option>
									<option value='categorie_artefact'>Categorie Artefact</option>
								</select>
							</td>
						</tr>
					</table>
					<input type='submit' value="Créer la catégorie" />				
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
