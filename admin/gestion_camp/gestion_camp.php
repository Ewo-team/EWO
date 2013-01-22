<?php
//-- Header --
$root_url = "./../..";
include($root_url."/template/header_new.php");
/*-- Connexion basic requise --*/
ControleAcces('admin',1);
/*-----------------------------*/

// Si la variable  $_SESSION['temp']['erreurs'] est définie, alors stocke sa valeur dans la variable $msg_error .
if(isset($_SESSION['temp']['erreurs'])){
	$msg_error = $_SESSION['temp']['erreurs'];
	unset($_SESSION['temp']['erreurs']);
}else{
	// Sinon le message d'erreur sera vide.
	$msg_error="";
}
?>

<div align='center' id='contact'>

<table width="60%">
	<tr>
		<td>
			<table>
				<tr>
					<td colspan="4" align="center"><?php echo $msg_error; ?></td>
				</tr>
				<tr>
					<td colspan="4"><h2>Liste des camps d&eacute;j&agrave; cr&eacute;&eacute;s</h2></td>
				</tr>
				<?php				
				$liste_camp = mysql_query("SELECT * FROM camps");
				
				// Affichage des cartes de la base de données
				while ($camp = mysql_fetch_array($liste_camp))
				{
				?>
				<tr>
					<td width="20%"><?php echo $camp["nom"]; ?></td>
					<td width="60%"><?php echo $camp["description"]; ?></td>
					<td width="10%" ><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=gestion_camp&edit_camp=<?php echo $camp["id"]; ?>">Editer</a>
					<td width="10%" align="right"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=ControllerCampEdition&suppr_camp=<?php echo $camp["id"]; ?>" onClick="return confirm('&ecirc;tes vous s&ucirc;r de vouloir supprimer le camp <?php echo $camp["nom"] ?>?')">Supprimer</a></td>
				</tr>
				<?php
				// Affichage de la partie permettant l'édition au cas où c'est demandé.
				if(isset($_GET['edit_camp']) && $_GET['edit_camp'] == $camp["id"])
				{
				?>
				<tr>
					<td colspan="4">
						<div style="border:solid thin">
						<form action="ControllerCampEdition.php" method="post" name="edition">
						<table width="100%">
							<tr>
								<td align="left">Nom du camp &agrave; cr&eacute;er :</td>
								<td align="right">
									<input type="text" name="camp_a_creer" value="<?php echo $camp["nom"]; ?>" />
									<input type="hidden" name="id_camp" value="<?php echo $camp["id"]; ?>" />
								</td>
							</tr>
							<tr>
								<td align="left">Carte &agrave; laquelle on rattache le camp :</td>
								<td align="right">
									<select name="id_carte">
									<?php
									// Affichage des différents choix pour les cartes
									$liste_cartes = mysql_query("SELECT id,nom FROM cartes");
									
									// Récupération des cartes déjà enregistrées dans la base de données.
									
									while ($carte = mysql_fetch_array($liste_cartes, MYSQL_NUM))
									{
										if ($carte[0] == $camp["carte_id"])
										{
										?>
											<option value="<?php echo $carte[0]; ?>" selected><?php echo $carte[1]; ?></option>
										<?php
										}
										else
										{
										?>
											<option value="<?php echo $carte[0]; ?>"><?php echo $carte[1]; ?></option>
										<?php
										}
									}
									?>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="2">Description du camp :</td>
							</tr>
							<tr>
								<td colspan="2"><textarea name="description_du_camp" rows="4" cols="49"><?php echo $camp["description"]; ?></textarea></td>
							</tr>
							</tr>
								<td colspan="2" align="center">
									<input type="submit" value="Editer" name="editCamp">
									<input type="button" value="Annuler" onClick="document.location='<?php echo $_SERVER['PHP_SELF']; ?>?page=gestion_camp'">
								</td>
							</tr>
						</table>
						</form>
						</div>
					</td>
				</tr>
				<?php
				}
				}
				?>
			</table>
		</td>
	</tr>
	<tr>
		<td><hr size="1"></td>
	</tr>
	<tr>
		<td>
			<table>
			<form method="post" action="ControllerCampEdition.php">
				<tr>
					<td colspan="2"><h2>Ajouter un camp</h2></td>
				</tr>
				<tr>
					<td align="left">Nom du camp &agrave; cr&eacute;er :</td>
					<td align="right"><input type="text" name="camp_a_creer" value="" /></td>
				</tr>
				<tr>
					<td align="left">Carte &agrave; laquelle on rattache le camp :</td>
					<td align="right">
						<select name="id_carte">
							<?php
							$reponse = mysql_query("SELECT id,nom FROM cartes");
						
							while ($camp = mysql_fetch_array($reponse, MYSQL_NUM))
							{
							?>
								<option value="<?php echo $camp[0]; ?>"><?php echo $camp[1]; ?></option>
							<?php
							}
							?>							
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">Description du camp :</td>
				</tr>
				<tr>
					<td colspan="2"><textarea name="description_du_camp" rows="5" cols="50"></textarea></td>
				</tr>
				<tr>
					<td colspan="2" align="center"><input type="submit" value="Ajouter" name="creer_camp"/></td>
				</tr>
			</form>
			</table>
		</td>
	</tr>
</table>
</div>
<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
