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
			$portes = "SELECT*FROM damier_spawn";																									
			$resultat = mysql_query ($portes) or die (mysql_error());
			$i=1;
			while ($porte = mysql_fetch_array ($resultat)){
			//$porte[''];
			$porte_nom = get_plan($porte['carte_id']);
			echo "<table>
				<tr>
					<td><span id='".$i."_nom_p' class='curspointer'><span id='".$i."_nom' onclick=\"edition_click(this.id,'nom','damier_spawn','".$porte['id']."');\">".$porte['nom']."</span></span></td>
				</tr>
				<tr>
					<td><span id='".$i."_desc_p' class='curspointer'><span id='".$i."_desc' onclick=\"edition_click(this.id,'description','damier_spawn','".$porte['id']."');\">".$porte['description']."</span></span></td>
				</tr>	
				<tr>
					<td>PosX: <span id='".$i."_posx_p' class='curspointer'><span id='".$i."_posx' onclick=\"edition_click(this.id,'pos_x','damier_spawn','".$porte['id']."');\">".$porte['pos_x']."</span></span>
					 PosY: <span id='".$i."_posy_p' class='curspointer'><span id='".$i."_posy' onclick=\"edition_click(this.id,'pos_y','damier_spawn','".$porte['id']."');\">".$porte['pos_y']."</span></span></td>
				</tr>		
				<tr>
					<td>Pos max X: <span id='".$i."_posmaxx_p' class='curspointer'><span id='".$i."_posmaxx' onclick=\"edition_click(this.id,'pos_max_x','damier_spawn','".$porte['id']."');\">".$porte['pos_max_x']."</span></span> 
					Pos max Y: <span id='".$i."_posmaxy_p' class='curspointer'><span id='".$i."_posmaxy' onclick=\"edition_click(this.id,'pos_max_y','damier_spawn','".$porte['id']."');\">".$porte['pos_max_y']."</span></span></td>
				</tr>						
				<tr>
					<td>CarteID: ".$porte['carte_id']." : ".$porte_nom."</td>
				</tr>		
				<tr>
					<td><br /></td>
				</tr>	
			</table>";
			$i++;
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
				<form name="spawn" action='bdd_spawn.php' method='post'>
					<table>
						<tr>
							<td>Nom : </td>
							<td><input type='text' value='' name='nom_spawn' size='30' /></td>
						</tr>	
						<tr>
							<td>Description :</td>
							<td><input type='text' value='' name='description_spawn' size='50' /></td>
						</tr>	
						<tr>
							<td>PosX : </td>
							<td><input type='text' value='' name='posX' size='2' /> Pos max X : <input type='text' value='' name='posmaxX' size='2' /> </td>
						</tr>							
						<tr>
							<td>PosY : </td>
							<td><input type='text' value='' name='posY' size='2' /> Pos max Y : <input type='text' value='' name='posmaxY' size='2' /></td>
						</tr>												
						<tr>
							<td>CarteID :</td>
							<td><?php liste_plan(); ?> * créer <a href='carte.php'>une carte</a></td>
						</tr>						
					</table>
					<input type='submit' value="Créer Spawn"/>
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
