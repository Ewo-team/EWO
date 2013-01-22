<hr>
<div align='center' id='contact'>
<p><b>Cr&eacute;er un grade :</b></p>
<?php
//if($anim||$admin||$gestion_grade||$virer_chef)
		{?>
		<form name='Grade' enctype='multipart/form-data' action='action_grade.php' method='POST'>
		Nom : <input name='nom_grade' type='text' value='' />
		<input type='hidden' name='id_faction' value='<?php echo 5; ?>' />
		<table>
			<tr>
				<td>
					<table>
							<tr><td height='21'>
							Chef (donne tous les droits)
							</td></tr>
							<tr><td height='21'>
							Virer les bras droits
							</td></tr>
							<tr><td height='21'>
							Virer les autres membres
							</td></tr>
							<tr><td height='21'>
							Gerer les grades
							</td></tr>
							<tr><td height='21'>
							Inviter de nouveaux membres
							</td></tr>
							<tr><td height='21'>
							Accès au GPS
							</td></tr>
							<tr><td height='21'>
							Baler un ou tous les grades
							</td></tr>
							<tr><td height='21'>
							Accès à la liste des membres (accès par défaut)
							</td></tr>
					</table>
				</td>
				<td>
					<table>
					<?php
				for ($inc=0; $inc<=7; $inc++)
					{$name='droit'.$inc;
							echo "<tr><td height='21'>";
							echo "<input type='checkbox' name='$name' value='1'>";
							echo "</td></tr>";
					};?>
					</table>
				</td>
			</tr>
		</table>
		<input type='submit' name='creer_grade' value='Cr&eacute;er' class='bouton' />
		</form></div>
<?php		}
?>
<hr>