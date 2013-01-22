<?php
//-- Header --
$root_url = "./../..";
include($root_url."/template/header_new.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/

// Si la variable  $_SESSION['temp']['erreurs'] est définie, alors stocke sa valeur dans la variable $msg_error .
if(isset($_SESSION['temp']['erreurs'])){
	$msg_error = $_SESSION['temp']['erreurs'];
	unset($_SESSION['temp']['erreurs']);
}
// Sinon le message derreur sera vide.
else{
	$msg_error="";
}

?>
<div align='center' id='contact'>
<table>
	<tr>
		<td>
			<table width="100%">
				<tr>
					<td colspan="4" align="center"><?php echo $msg_error; ?></td>
				</tr>
				<tr>
					<td colspan="4"><h2>Liste des races d&eacute;j&agrave; cr&eacute;&eacute;es</h2></td>
				</tr>
				<?php		
				$liste_race = mysql_query("SELECT * FROM races WHERE grade_id = -2");
				// Affichage des camps de la base de données
				while ($race = mysql_fetch_array($liste_race))
				{
				?>
				<tr>
					<td width="20%"><?php echo $race["nom"]; ?></td>
					<td width="60%"><?php echo $race["description"]; ?></td>
					<td width="10%" ><a href="gestion_race.php?edit_race=<?php echo $race['race_id']; ?>">Editer</a>
					<td width="10%" align="right"><a href='ControllerRaceEdition.php?suppr_race=<?php echo $race['race_id']; ?>' onClick="return confirm('&Ecirc;tes vous s&ucirc;r de vouloir supprimer la race <?php echo $race["nom"] ?> ?')">Supprimer</a></td>
				</tr>
				<?php
				// Affichage de la partie permettant l'édition au cas où c'est demandé.
				if(isset($_GET['edit_race']) && $_GET['edit_race'] == $race['race_id'])
				{
				?>
				<tr>
					<td colspan="4">
						<div style="border:solid thin">
						<form action="ControllerRaceEdition.php" method="post" name="editRace">
						<table width="100%">
							<tr>
								<td align="left">Nom de la race &agrave; editer :</td>
								<td align="right">
									<input type="text" name="race_a_creer" value="<?php echo $race['nom']; ?>" />
									<input type="hidden" name="id_race" value="<?php echo $race['race_id']; ?>" />
								</td>
							</tr>
							<tr>
								<td align="left">Couleur de la race : </td>
								<td align="right">
									<input type="text" name="couleur" value="<?php echo $race['color']; ?>" />
								</td>
							</tr>
							<tr>
								<td align="left">Type de jeu (3 gros perso, 7 petites créas ou 0 pour parias) : </td>
								<td align="right">
									<input type="text" name="type" value="<?php echo $race['type']; ?>" />
								</td>
							</tr>
							<tr>
								<td align="left">Camp auquel on rattache la race :</td>
								<td align="right">
									<select name="id_camp">
									<?php
									// Affichage des différents choix pour les camps
									$liste_camps = mysql_query("SELECT id,nom FROM camps");
									
									// Récupération des camps déjà enregistrés dans la base de données.
									
									while ($camp = mysql_fetch_array($liste_camps, MYSQL_NUM))
									{
										if ($camp[0] == $race["camp_id"])
										{
										?>
											<option value="<?php echo $camp[0]; ?>" selected><?php echo $camp[1]; ?></option>
										<?php
										}
										else
										{
										?>
											<option value="<?php echo $camp[0]; ?>"><?php echo $camp[1]; ?></option>
										<?php
										}
									}
									?>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="2">Description de la race :</td>
							</tr>
							<tr>
								<td colspan="2"><textarea name="description_de_la_race" rows="4" cols="49"><?php echo $race["description"]; ?></textarea></td>
							</tr>
							</tr>
								<td colspan="2" align="center">
									<input type="submit" value="Editer" name="editRace">
									<input type="button" value="Annuler" onClick="document.location='?page=gestion_race'">
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
			<form method="post" action="ControllerRaceEdition.php">
				<tr>
					<td colspan="2"><h2>Ajouter une race</h2></td>
				</tr>
				<tr>
					<td colspan="2">La race sera cr&eacute;&eacute;e avec 6 grades de base (0 &agrave; 5), plus le grade -1 des tricheurs, et -2 pour le nom visible</td>
				</tr>
				<tr>
				<td><br/></td>
				</tr>
				<tr>
					<td align="left">Nom de la race &agrave; cr&eacute;er :</td>
					<td align="right"><input type="text" name="race_a_creer" value="" /></td>
				</tr>
				<tr>
					<td align="left">Couleur de la race : </td>
					<td align="right">
						<input type="text" name="couleur" value="" />
					</td>
				</tr>
				<tr>
					<td align="left">Type de jeu (3 gros perso, 7 petites créas ou 0 pour parias) : </td>
					<td align="right">
						<input type="text" name="type" value="<?php echo $race['type']; ?>" />
					</td>
				</tr>
				<tr>
					<td align="left">Camp auquel on rattache la race :</td>
					<td align="right">
						<select name="id_camp">
							<?php
							$reponse = mysql_query("SELECT id,nom FROM camps");
						
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
					<td colspan="2">Description de la race :</td>
				</tr>
				<tr>
					<td colspan="2"><textarea name="description_de_la_race" rows="5" cols="50"></textarea></td>
				</tr>
				<tr>
					<td colspan="2" align="center"><input type="submit" value="Ajouter" name="creer_race"/></td>
				</tr>
			</form>
			</table>
		</td>
	</tr>
</table></div>
<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
