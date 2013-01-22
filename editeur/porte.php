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
			$portes = "SELECT*FROM damier_porte";
			$resultat = mysql_query ($portes) or die (mysql_error());
			$p=1;
			while ($porte = mysql_fetch_array ($resultat)){
				//$porte[''];
				$spawn_nom = get_spawn($porte['spawn_id']);
				$porte_nom = get_plan($porte['carte_id']);
				$liste_porte['id'][$p]=$porte['id'];
				$liste_porte['nom'][$p]=$porte['nom'];
				
				if ($porte['statut'] == 0){
					$lock = 'close';
				}else{
					$lock = 'open';
				}
				
				echo "<table id='".$porte['id']."-porte'>
				<tr>
					<td><span onClick=\"maj_objet('".$porte['id']."','supprimer','porte');\"><img src='../images/site/delete.png' /></span> <b><span id='".$p."_nom_p' class='curspointer'><span id='".$p."_nom' onclick=\"edition_click(this.id,'nom','damier_porte','".$porte['id']."');\">".$porte['nom']."</span></span></b></td>
				</tr>
				<tr>
					<td><span id='".$p."_desc_p' class='curspointer'><span id='".$p."_desc' onclick=\"edition_click(this.id,'description','damier_porte','".$porte['id']."');\">".$porte['description']."</span></span></td>
				</tr>	
				<tr>
					<td>Nom de l'image : ".$porte['nom_image']."</td>
				</tr>
				<tr>
					<td>
				<table border='0' class='damier_corps' CELLPADDING='0' CELLSPACING='0'>";
				$i = 1;
				for($y=1;$y<=4;$y++){
					echo "<tr  height='33'>";
					for($x=1;$x<=4;$x++){
						echo "<td>";
							echo "<img src='../images/decors/portes/".$porte['nom_image']."_".$i.".png' />";
							$i++;
						echo "</td>";
					}
					echo "</tr>";
				}
				echo "</table>
				</td>
				</tr>
				<tr>
					<td>PosX: <span id='".$p."_posx_p' class='curspointer'><span id='".$p."_posx' onclick=\"edition_click(this.id,'pos_x','damier_porte','".$porte['id']."');\">".$porte['pos_x']."</span></span>
					    PosY: <span id='".$p."_posy_p' class='curspointer'><span id='".$p."_posy' onclick=\"edition_click(this.id,'pos_y','damier_porte','".$porte['id']."');\">".$porte['pos_y']."</span></span></td>
				</tr>					
				<tr>
					<td>SpawnID : ".$porte['spawn_id']." - ".$spawn_nom."</td>
				</tr>				
				<tr>
					<td>CarteID: ".$porte['carte_id']." : ".$porte_nom."</td>
				</tr>								
				<tr>
					<td>Pv: ".$porte['pv']." / ".$porte['pv_max']."</td>
				</tr>			
				<tr>
					<td>Statut : <span class='curspointer' onClick=\"maj_objet('".$porte['id']."','lock','porte');\"><img id='".$porte['id']."-lock' src='../images/transparent.png' class='lock ".$lock."'></span></td>
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
				<form name="porte" action='bdd_porte.php' method='post'>
					<table>
						<tr>
							<td>Nom : </td>
							<td><input type='text' value='' name='nom_porte' size='30' /></td>
						</tr>	
						<tr>
							<td>Description :</td>
							<td><input type='text' value='' name='description_porte' size='50' /></td>
						</tr>	
						<tr>
							<td>Nom de l'image :</td>
							<td><input type='text' value='' name='image_porte' size='20' /> * Pour ajouter les icones d'<a href='icone_porte.php'>une porte</a></td>
						</tr>							
						<tr>
							<td>PosX : </td>
							<td><input type='text' value='' name='posX' size='2' /> PosY : <input type='text' value='' name='posY' size='2' /></td>
						</tr>							
						<tr>
							<td>SpanwID :</td>
							<td><?php liste_spawn(); ?> * créer <a href='spawn.php'>un spawn</a></td>
						</tr>												
						<tr>
							<td>CarteID :</td>
							<td><?php liste_plan(); ?> * créer <a href='carte.php'>une carte</a></td>
						</tr>	
																		
						<tr>
							<td>Lier à une autre porte :</td>
							<td><?php 
								echo "<select name='porte_liee'>";
								echo "<option value='0'>Aucune</option>";
								for($inci=1; $inci<$p;$inci++){
									echo "<option value='".$liste_porte['id'][$inci]."'>".$liste_porte['nom'][$inci]."</option>";
									}
								echo "</select>";
								?></td>
						</tr>	
						<tr>
							<td>Pv Max :</td>
							<td><input type='text' value='' name='pv_max' size='5' />	pv</td>
						</tr>						
						<tr>
							<td>Statut :</td>
							<td>
								<select name="statut">
									<option value='0'>Fermé</option>
									<option value='1' selected>Ouvert</option>
								</select>
							</td>
						</tr>
					</table>
					<input type='submit' value="Créer la porte" />				
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
