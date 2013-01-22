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
// Sinon le message d'erreur sera vide.
else{
	$msg_error="";
}
				
?>
<div align='center' id='contact'>
<table>
	<tr>
		<td><form action="gestion_grade.php" method="post" name="race">
			<table width="100%">
				<tr>
					<td colspan="4" align="center"><?php echo $msg_error; ?></td>
				</tr>
				<tr>
					<td colspan="4"><h2>Liste des grades d&eacute;j&agrave; cr&eacute;&eacute;es</h2></td>
				</tr>
				<tr>
					<td align "center">
					<select name="race_id">
					<?php
					// Affichage des différents choix pour les camps
					$liste_race = mysql_query("SELECT race_id,nom FROM races WHERE grade_id=-2");
									
					// Récupération des camps déjà enregistrés dans la base de données.
									
					while ($rep_race = mysql_fetch_array($liste_race, MYSQL_NUM))
					{
						if (isset($_POST['race_id'])||isset($_GET['race_id']))
							{
								if ((isset($_POST['race_id']) && $rep_race[0] == $_POST['race_id']) || (isset($_GET['race_id']) && $rep_race[0] == $_GET['race_id']))
								{
								?>
									<option value="<?php echo $rep_race[0]; ?>" selected><?php echo $rep_race[1]; ?></option>
								<?php
								}
								else
								{
								?>
									<option value="<?php echo $rep_race[0]; ?>"><?php echo $rep_race[1]; ?></option>
								<?php
								}
							}
						else
						{
						?>
							<option value="<?php echo $rep_race[0]; ?>"><?php echo $rep_race[1]; ?></option>
						<?php
						}
					}
						?>
				</select><input type="submit" value="Selectionner" name="Select_race"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			</form><table  width="100%">
				<?php
				if (isset($_POST['race_id'])||isset($_GET['edit_grade']))
				{
				if (isset($_POST['race_id']))
					$race_id = $_POST['race_id'];
				if (isset($_GET['race_id']))
					$race_id = $_GET['race_id'];
				$liste_grade = mysql_query("SELECT * FROM races WHERE race_id=$race_id");
							
				// Affichage des camps de la base de données
				while ($grade = mysql_fetch_array($liste_grade))
				{
				?>
				<tr>
					<td width="20%"><?php echo $grade['grade_id'].' : '.$grade["nom"]; ?></td>
					<td width="60%"><?php echo $grade["description"]; ?></td>
					<td width="10%" ><a href="<?php echo $_SERVER['PHP_SELF']; ?>?edit_grade=<?php echo $grade["grade_id"]; ?>&race_id=<?php echo $grade["race_id"]; ?>">Editer</a></td>
					<td width="10%" ><a href="ControllerGradeEdition.php?suppr_grade=<?php echo $grade["grade_id"]; ?>&race_id=<?php echo $grade["race_id"]; ?>" onClick="return confirm('&Ecirc;tes vous s&ucirc;r de vouloir supprimer le grade <?php echo $grade["nom"] ?>?')">Supprimer</a></td>
				</tr>
				<?php
				// Affichage de la partie permettant l'édition au cas où c'est demandé.
				if((isset($_GET['edit_grade']) && $_GET['edit_grade'] == $grade["grade_id"]) && (isset($_GET['race_id']) && $_GET['race_id'] == $grade["race_id"]))
				{
				?>
				<tr>
					<td colspan="4">
						<div style="border:solid thin">
						<form action="ControllerGradeEdition.php" method="post" name="edition">
						<table width="100%">
							<tr>
								<td align="left">Nom du grade &agrave; editer :</td>
								<td align="right">
									<input type="text" name="grade_a_creer" value="<?php echo $grade["nom"]; ?>" />
									<input type="hidden" name="id_grade" value="<?php echo $grade["grade_id"]; ?>" />
									<input type="hidden" name="id_race" value="<?php echo $grade["race_id"]; ?>" />
								</td>
							</tr>
							<tr>
								<td align="left">Race &agrave; laquelle on rattache le grade :</td>
								<td align="right">
									<select name="new_race_id">
									<?php
									// Affichage des différents choix pour les camps
									$liste_camps = mysql_query("SELECT race_id,nom FROM races WHERE grade_id=0");
									
									// Récupération des camps déjà enregistrés dans la base de données.
									
									while ($camp = mysql_fetch_array($liste_camps, MYSQL_NUM))
									{
										if ($camp[0] == $grade["race_id"])
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
								<td align="left">Camp auquel on rattache le grade :</td>
								<td align="right">
									<select name="id_camp">
									<?php
									// Affichage des différents choix pour les camps
									$liste_camps = mysql_query("SELECT id,nom FROM camps");
									
									// Récupération des camps déjà enregistrés dans la base de données.
									
									while ($camp = mysql_fetch_array($liste_camps, MYSQL_NUM))
									{
										if ($camp[0] == $grade["camp_id"])
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
								<td align="left">Valeur num&eacute;rique du grade :</td>
								<td align="right"> <input type="text" name="new_grade_id" value="<?php echo $grade["grade_id"]; ?>" /></td>
							</tr>
							<tr>
								<td align="left">Couleur du grade :</td>
								<td align="right">
									<input type="text" name="couleur" value="<?php echo $grade["color"]; ?>" />
								</td>
							</tr>
							<tr>
								<td colspan="2">Description du grade :</td>
							</tr>
							<tr>
								<td colspan="2"><textarea name="description_du_grade" rows="4" cols="49"><?php echo $grade["description"]; ?></textarea></td>
							</tr>
							<tr>
								<td colspan="2" align="center">
									<input type="submit" value="Editer" name="editGrade">
									<input type="button" value="Annuler" onClick="document.location='<?php echo $_SERVER['PHP_SELF']; ?>?page=gestion_grade'">
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
				}?>
			</table>
		</td>
	</tr>
	<tr>
		<td><hr size="1"></td>
	</tr>
	<tr>
		<td>
			<table>
			<form method="post" action="ControllerGradeEdition.php">
				<tr>
					<td colspan="2"><h2>Ajouter un grade</h2></td>
				</tr>
				<tr>
					<td align="left">Nom du grade &agrave; cr&eacute;er :</td>
					<td align="right"><input type="text" name="grade_a_creer" value="" /></td>
				</tr>
				<tr>
					<td align="left">Race &agrave; laquelle on rattache le grade :</td>
					<td align="right">
						<select name="new_race_id">
							<?php
							$reponse = mysql_query("SELECT race_id,nom FROM races WHERE grade_id = 0");
						
							while ($rep_race = mysql_fetch_array($reponse, MYSQL_NUM))
							{
							?>
								<option value="<?php echo $rep_race[0]; ?>"><?php echo $rep_race[1]; ?></option>
							<?php
							}
							?>							
						</select>
					</td>
				</tr>
				<tr>
					<td align="left">Camp auquel on rattache le grade :</td>
					<td align="right">
						<select name="id_camp">
						<?php
						// Affichage des différents choix pour les camps
						$liste_camps = mysql_query("SELECT id,nom FROM camps");
						
						// Récupération des camps déjà enregistrés dans la base de données.
						
						while ($camp = mysql_fetch_array($liste_camps, MYSQL_NUM))
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
					<td align="left">Valeur num&eacute;rique du grade :</td>
					<td align="right"> <input type="text" name="new_grade_id" value="" /></td>
				</tr>
				<tr>
					<td align="left">Couleur du grade :</td>
						<td align="right">
							<input type="text" name="couleur" value="" />
						</td>
					</tr>
				<tr>
					<td colspan="2">Description du grade :</td>
				</tr>
				<tr>
					<td colspan="2"><textarea name="description_du_grade" rows="5" cols="50"></textarea></td>
				</tr>
				<tr>
					<td colspan="2" align="center"><input type="submit" value="Ajouter" name="creer_grade"/></td>
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
