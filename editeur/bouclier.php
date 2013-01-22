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
			$portes = "SELECT*FROM damier_bouclier";																									
			$resultat = mysql_query ($portes) or die (mysql_error());
			$p = 0;
			while ($porte = mysql_fetch_array ($resultat)){
				//$porte[''];

				$porte_nom = get_plan($porte['carte_id']);
				
				if ($porte['statut'] == 0){
					$lock = 'close';
				}else{
					$lock = 'open';
				}
				
				echo "<table id='".$porte['id']."-porte'>
				<tr>
					<td><a onClick=\"maj_objet('".$porte['id']."','supprimer','bouclier');\"><img src='../images/site/delete.png' /></a> <b><span id='".$p."_nom_p' class='curspointer'><span id='".$p."_nom' onclick=\"edition_click(this.id,'nom','damier_bouclier','".$porte['id']."');\">".$porte['nom']."</span></span></b></td>
				</tr>
				<tr>
					<td><span id='".$p."_desc_p' class='curspointer'><span id='".$p."_desc' onclick=\"edition_click(this.id,'description','damier_bouclier','".$porte['id']."');\">".$porte['description']."</span></span></td>
				</tr>	
				<tr>
					<td>Nom de l'image : ".$porte['nom_image']."</td>
				</tr>
				<tr>
					<td>
				<table border='0' class='damier_corps' CELLPADDING='0' CELLSPACING='0'>";
				$i = 1;
				for($y=1;$y<=$porte['type_id'];$y++){
					echo "<tr  height='33'>";
					for($x=1;$x<=$porte['type_id'];$x++){
						echo "<td>";
							echo "<img src='../images/decors/boucliers/".$porte['nom_image']."_".$i.".png' />";
							$i++;
						echo "</td>";
					}
					echo "</tr>";
				}
				echo "</table>
				</td>
				</tr>
				<tr>
					<td>PosX: <span id='".$p."_posx_p' class='curspointer'><span id='".$p."_posx' onclick=\"edition_click(this.id,'pos_x','damier_bouclier','".$porte['id']."');\">".$porte['pos_x']."</span></span> 
					    PosY: <span id='".$p."_posy_p' class='curspointer'><span id='".$p."_posy' onclick=\"edition_click(this.id,'pos_y','damier_bouclier','".$porte['id']."');\">".$porte['pos_y']."</span></span></td>
				</tr>					
				<tr>
					<td>Niveau : <span id='".$p."_type_p' class='curspointer'><span id='".$p."_type' onclick=\"edition_click(this.id,'type_id','damier_bouclier','".$porte['id']."');\">".$porte['type_id']."</td>
				</tr>				
				<tr>
					<td>CarteID: ".$porte['carte_id']." : ".$porte_nom."</td>
				</tr>								
				<tr>
					<td>Pv: ".$porte['pv']." / ".$porte['pv_max']."</td>
				</tr>
				<tr>
					<td>Déplaceable : <span id='".$p."_deplace_p' class='curspointer'><span id='".$p."_deplace' onclick=\"edition_click(this.id,'deplacer','damier_bouclier','".$porte['id']."');\">".$porte['deplacer']."</td>
				</tr>					
				<tr>
					<td>Statut : <span class='curspointer' onClick=\"maj_objet('".$porte['id']."','lock','bouclier');\"><img id='".$porte['id']."-lock' src='../images/transparent.png' class='lock ".$lock."'></span></td>
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
				<form name="porte" action='bdd_bouclier.php' method='post'>
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
							<td>PosX : </td>
							<td><input type='text' value='' name='posX' size='2' /> PosY : <input type='text' value='' name='posY' size='2' /></td>
						</tr>							
						<tr>
							<td>Niveau :</td>
							<td>
								<select name="type_id">
									<option value='1'>1</option>
									<option value='2'>2</option>
									<option value='3'>3</option>
									<option value='4'>4</option>
								</select>	 * 1 à 4					
							</td>
						</tr>												
						<tr>
							<td>CarteID :</td>
							<td><?php liste_plan(); ?> * créer <a href='carte.php'>une carte</a></td>
						</tr>	
						<tr>
							<td>Pv Max :</td>
							<td><input type='text' value='' name='pv_max' size='5' />	pv</td>
						</tr>	
						<tr>
							<td>Déplaceable :</td>
							<td>
								<select name="deplacer">
									<option value='1'>Oui</option>
									<option value='0' selected>Non</option>
								</select>
							</td>
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
					<input type='submit' value="Créer un bouclier" />				
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
