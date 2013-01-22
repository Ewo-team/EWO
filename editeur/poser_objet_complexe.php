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
			$portes = "SELECT*FROM damier_objet_complexe";																									
			$resultat = mysql_query ($portes) or die (mysql_error());
			while ($porte = mysql_fetch_array ($resultat)){
				//$porte[''];
							
				echo "<table id='".$porte['id']."-porte'>
				<tr>
					<td><b>";
					echo name_objet_complexe ($porte['case_objet_complexe_id']);
					echo "</b></td>
					<td>Pos X: ".$porte['pos_x']."</td>
					<td>Pos Y: ".$porte['pos_y']."</td>					
					<td>Pv : ".$porte['pv']."</td>
					<td>Plan : ";
					echo get_plan ($porte['carte_id']);
					echo "</td>
				</tr>
				<tr>
					<td></td>
				</tr>				
				</table>";
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
				<form name="porte" action='bdd_poser_objet_complexe.php' method='post'>
					<table>
						<tr>
							<td>Nom : </td>
							<td><?php liste_objet_complexe() ?></td>
						</tr>							
						<tr>
							<td>Pos X : </td>
							<td><input type='text' value='' name='pos_x' size='3' /> Pos Y : <input type='text' value='' name='pos_y' size='3' /></td>
						</tr>												
						<tr>
							<td>Plan :</td>
							<td>
								<?php liste_plan(); ?> * créer <a href='carte.php'>une carte</a>
							</td>
						</tr>
					</table>
					<input type='submit' value="Poser un objet complexe" />				
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
