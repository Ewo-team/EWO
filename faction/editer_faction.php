<?php
/**
 * Faction - formulaire d'edition d'une faction
 *
 * @author Anarion <anarion@ewo.fr>
 * @version 1.0
 * @package faction
 */

//-- Header --
$root_url = "..";
include($root_url."/template/header_new.php");

/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/

include("./fonctions.php");

echo $_POST['nom_faction'];

$perso_id = 0;

if (isset($_GET['perso_id']) && is_numeric($_GET['perso_id'])){
$inc=1;
while($inc<=$_SESSION['persos']['inc'] && $_GET['perso_id']!=$_SESSION['persos']['id'][$inc])
{
$inc++;
}
if($inc<=$_SESSION['persos']['inc']){
	$perso_id = mysql_real_escape_string($_GET['perso_id']);
	}
}

if (isset($_POST['perso_id']) && is_numeric($_POST['perso_id'])){
$perso_id = mysql_real_escape_string($_POST['perso_id']);
}


if (isset($_POST['faction_id']) && is_numeric($_POST['faction_id'])){
$faction_id = mysql_real_escape_string($_POST['faction_id']);
$ok = 1;
}

if (isset($_POST['nom_faction'])){
$nom_faction=mysql_real_escape_string($_POST['nom_faction']);

$requete = "SELECT id AS faction_id FROM factions WHERE nom='$nom_faction' OR id='$nom_faction'";

$reponse = mysql_query ($requete) or die (mysql_error());
if($faction  = mysql_fetch_array ($reponse)){
	$faction_id = $faction['faction_id'];
	}
	else $faction_id = 0;

$ok = 1;
}


if (isset($_GET['id']) && is_numeric($_GET['id'])){
$faction_id = mysql_real_escape_string($_GET['id']);
$ok = 1;
}

$_SESSION['utilisateur']['perso_id']=$perso_id;

$requete = "SELECT id AS faction_id FROM factions WHERE nom='$faction_id' OR id='$faction_id'";
$reponse = mysql_query ($requete) or die (mysql_error());
if($faction  = mysql_fetch_array ($reponse)){
	include("../faction/controle_membre.php");
	$anim = ControleAcces('anim',0);
	$admin = ControleAcces('admin',0);
	$virer_chef = Controle_membre('0',$perso_id, $faction_id);
	$virer_bras = Controle_membre('1',$perso_id, $faction_id);
	$virer_mem = Controle_membre('2',$perso_id, $faction_id);
	$gestion_grade = Controle_membre('3',$perso_id, $faction_id); //sauf chef et bras droit sans le droit de virage correspondant
	$inviter_mem = Controle_membre('4',$perso_id, $faction_id);
	$gps = Controle_membre('5',$perso_id, $faction_id);
	$bal  = Controle_membre('6',$perso_id, $faction_id);
	$ess  = Controle_membre('7',$perso_id, $faction_id); // droit de tous les membres par défaut

	$utilisateur_id = $_SESSION['utilisateur']['id'];

	$faction = is_membre($utilisateur_id, $faction_id);
	$faction = $faction['faction_id'];
	
	/*-- Connexion anim ou appartenance à la faction requise --*/
	if (!ControleAcces('utilisateur',0)){
		echo "<script language='javascript' type='text/javascript' >document.location='../'</script>";exit;
	 }
	/*-----------------------------*/

	if ($ok == 1){
	
	$member_faction_grade = member_faction_grade($perso_id);

	//-- Info faction
	$info = "SELECT * FROM factions WHERE id = $faction_id";																							
	$resultat = mysql_query ($info) or die (mysql_error());
	$infos = mysql_fetch_array ($resultat);


	$race_id	= $infos['race'];

	if (!isset($race_id))
	{
		echo "<script language='javascript' type='text/javascript' >document.location='../'</script>";exit;
	}

	$race	= nom_race($race_id);

	if ($race_id==2){$race='Mauve';};

	//Info race du visiteur
	$utilisateur_id = $utilisateur_id;

	$req = "SELECT DISTINCT race_id FROM persos WHERE utilisateur_id = $utilisateur_id";																							
	$res = mysql_query ($req) or die (mysql_error());
	
	$acces = false;
	
	while($race_anim = mysql_fetch_array ($res)) {
		$race_anim_id = $race_anim['race_id'];
		$race_anim	= nom_race($race_anim_id);
	
		if($race=='Mauve' || $race==$race_anim)
		{
			$acces = true;
		}
	}
	
	if(!$acces)
	{
		echo "<script language='javascript' type='text/javascript' >document.location='../'</script>";exit;
	}

	//Acceptation ou non des demande d'entrée dans la faction

	if (isset($_POST['demande_factions']))
	{
	$sql_demande 	= "SELECT perso_id FROM wait_faction WHERE faction_id = $faction_id AND demandeur=1";
	$res_demande	= mysql_query (mysql_real_escape_string($sql_demande)) or die (mysql_error());
	while($demande = mysql_fetch_array ($res_demande))
	{
		if (isset($demande['perso_id']))
		{
		$demandeur_id = $demande['perso_id'];
		$oui = 'oui'.$demandeur_id;
		if (isset($_POST[$oui]) && $_POST[$oui]==1)
			{
				accepte($faction_id, $demandeur_id);
				$sql_demande 	= "SELECT faction_id FROM wait_faction WHERE perso_id = $demandeur_id";
				$res_demande_	= mysql_query (mysql_real_escape_string($sql_demande)) or die (mysql_error());
				while($demande_ = mysql_fetch_array ($res_demande_))
				{
				if (isset($demande_['faction_id']))
					{
					$fact_id = $demande_['faction_id'];
					refuse($fact_id, $demandeur_id);
					}
				}
			}
		elseif (isset($_POST[$oui]) && $_POST[$oui]==0)
			{
				refuse($faction_id, $demandeur_id);
			}
		}
	}
	}

	//-- Liste des membres de la faction
	if (!isset($_GET['alpha'])){
		$alpha = 'A';
	}else{
		$alpha = mysql_real_escape_string($_GET['alpha']);
	}

	$_SESSION['temps']['page'] = "../faction/editer_faction.php?id=$faction_id";
	?>

	<div align='center' id='contact'>
	<h2><?php if ($anim){echo 'Edition de ';} echo '<i>'.$infos['nom'].'</i>'; ?></h2>
		<form name='description' action="edition_faction.php" method="post">
	<h3><?php echo '<i>'.$infos['description'].'</i>';
	if($anim||$virer_chef)
		{
		?>
		<img onclick="css_toggle('menu_description');" src='../images/site/add.png' alt='Update'>
		<?php
		echo"<div id='menu_description' style='display:none;'>";
		?>
			<textarea name="description_faction" rows="5" cols="35"/><?php echo $infos['description']; ?></textarea><br/>
			<input name="faction_id" type="hidden" value='<?php echo $faction_id; ?>' />
			<input type='submit' name='description' value='Modifier' class='bouton' /></div>
	<?php	};
		?></h3></form>
	<form name='description' action="edition_faction.php" method="post">
	<p>Site : <a href='<?php echo $infos['site_url']; ?>'> <?php echo '<i>'.$infos['site_url'].'</i>'; ?> </a>
	<?php 
	if($anim||$virer_chef)
		{
		?>
		<img onclick="$('#menu_url').toggle();" src='../images/site/add.png' alt='Update'>
		<?php
		echo"<div id='menu_url' style='display:none;'>";
		?>
			<input name="url_faction" type="text" value="<?php echo $infos['site_url']; ?>" />
			<input name="faction_id" type="hidden" value='<?php echo $faction_id; ?>' />
			<input type='submit' name='url' value='Modifier' class='bouton' /></div>
	<?php	
		};

	if($anim){?>
	</p></form>
<br/>
	<p>[ <a href='liste_factions.php'>Retour</a> ]</p>
	<?php
	}else{?>
		</p></form>
<br/>
	<p>[ <a href='liste_persos.php'>Retour</a> ]</p>
	<?php
	}
	if ($anim||$virer_chef)
	{
	?>
	<p><form name='suppression' action="edition_faction.php" method="post">Supprimer la faction : (irreversible)
	<input name="faction_id" type="hidden" value='<?php echo $faction_id; ?>' />
	<input type="submit" name='supprimer' value="Supprimer" class="bouton" />
	</form></p>
	<p>
	<?php
	}
	if ($anim)
	{
	?>
	<form name='nom' action="edition_faction.php" method="post">Modifier le nom :
	<input name="nom_faction" type="text" value='<?php echo $infos['nom']; ?>' />
	<input name="faction_id" type="hidden" value='<?php echo $faction_id; ?>' />
	<input type="submit" name='nom' value="Modifier" class="bouton" />
	</form>
	</p>
	<?php
	}
	?>
	<table>
	<?php
	if ($anim)
	{
	?>
	<tr><tr><td><br/></td></tr>
	<tr>
		<td  colspan="2"><b>Id de la faction <?php echo $infos['nom'];?> : </b></td><td  colspan="2" align="center"><?php echo $faction_id;?></td> 
	</tr>
	<?php
	}
	?>
	<tr><td  colspan="2"><b>Type de faction : </b></td><td  colspan="2" align="center">
	<?php echo '<i>'.$infos['type_nom'].'</i>';
		?></td> 
	</tr>
	<tr>
		<td  colspan="2">
				<b>Blason :</b>
		</td>
		<td  colspan="2" align="center">
			<img src='../images/<?php echo $infos['logo_url']; ?>' alt='Blason'>
			<?php
	if ($anim||$virer_chef)
	{
	?>
	<p><form name='modifier' action="edition_faction.php" method="post">
	<input name="faction_id" type="hidden" value='<?php echo $faction_id; ?>' />
	<input type="submit" value="Modifier" class="bouton" />
	</form></p>
	<p>
	<?php
	}
	?>
		</td>
	</tr>

	<tr>
		<td  colspan="2">
			<b>Race :</b></td><td  colspan="2" align="center"><?php echo $race;?></td>
	</tr>
	<?php
	if ($anim)
	{
	?>
	<tr><td colspan="4" align="center">
	<form name='race' action="edition_faction.php" method="post">Modifier la race :
	<br/>Les factions de "Mauves" seront automatiquement de type "Traitre".<br/>
	<select name="race">
	<option name="race"value='<?php echo $race_anim_id; ?>' selected='selected'><?php echo $race_anim;?></option>
	<option name="race"value='2'>Mauve</option> 
	</select>
	<input name="faction_id" type="hidden" value='<?php echo $faction_id; ?>' />
	<input type="submit" name="mod_race" value="Modifier" class="bouton" />
	</form>
	</td></tr>
	<?php
	}
	?>
	</table>
	</div>
	<hr>
	<div align='center' id='contact'>
	<ul>
	<p><b>Outils divers :</b></p>
	<?php
	if($infos['type']==2)
		echo "<a href='./ennemis.php?perso_id=$perso_id&id=$faction_id'>Liste des ennemis dans le plan</a> | ";
	?>
	<?php
	if($infos['type']==1 && $member_faction_grade!=4)
		echo "<a href='./traitres.php?perso_id=$perso_id&id=$faction_id'>Liste des personnes pouvant &ecirc;tre pass&eacute;es tra&icirc;tre</a> | ";
	?>
	<br/>
	</ul>
	</div>
	<hr>
	<br/>
	<?php
	// Listing membres
	?>
	<div align='center' id='contact'>
	<p><b>Listing des membres par nom :</b></p>
	<?php
	// Liste alpha des lettres
	for ($i='A';$i!='AA';$i++){
		$count = "SELECT COUNT(nom) AS nombre FROM persos WHERE faction_id ='$faction_id' AND nom REGEXP '^".$i."'";
		$resultat = mysql_query ($count) or die (mysql_error());
		$counter = mysql_fetch_array ($resultat);
		
		if($counter['nombre'])
			echo "<a href='?alpha=$i&id=$faction_id&perso_id=".$perso_id."'>$i (".$counter['nombre'].")</a> | ";
	}

	$membres = "SELECT * FROM persos WHERE faction_id ='$faction_id' AND nom LIKE '".$alpha."%' ORDER BY nom ASC";							

	?>
	<br/>
	<ul>
	<br/>
	<?php
		$resultat = mysql_query ($membres) or die (mysql_error());
		while ($membres = mysql_fetch_array ($resultat)){
		?>
		<form action="action_mem.php" method="post">
		<?php
		$membre_id = $membres['id'];
		$reponsegrade = mysql_query("SELECT faction_grade_id AS id FROM faction_membres WHERE faction_id = '$faction_id' AND perso_id='$membre_id'") or die (mysql_error());
		$membre_grade_id=mysql_fetch_array($reponsegrade);
		$membre_grade_id = $membre_grade_id['id'];
		
		$reponse_pos = mysql_query("SELECT pos_x, pos_y, carte_id FROM damier_persos WHERE perso_id = '$membre_id'") or die (mysql_error());
		$membre_pos = mysql_fetch_array($reponse_pos);
		$posx = $membre_pos['pos_x'];
		$posy = $membre_pos['pos_y'];
		$carte = $membre_pos['carte_id'];
		
		$reponse_pos = mysql_query("SELECT nom FROM cartes WHERE id = '$carte'") or die (mysql_error());
		$membre_pos = mysql_fetch_array($reponse_pos);
		$nom_carte = $membre_pos['nom'];
		
		$reponsegrade = mysql_query("SELECT nom FROM faction_grades WHERE faction_id = '$faction_id' AND grade_id='$membre_grade_id'") or die (mysql_error());
		$membre_grade=mysql_fetch_array($reponsegrade);
		$membre_grade = $membre_grade['nom'];
		
			echo "<li>Id : ".$membres['id']." Nom : <a href='../messagerie/index.php?id=".$perso_id."&dest=".$membres['id']."'>".$membres['nom']."</a> | Grade : $membre_grade |";
			if ($anim||$gps||$virer_chef){echo "Position : $posx/$posy $nom_carte |";}
			echo "<input type=\"hidden\" name=\"perso_id\" value=\"".$membres['id']."\" />
				  <input type=\"hidden\" name=\"faction_id\" value=\"".$faction_id."\" />";
		if($anim||$gestion_grade||$virer_chef)
		{
		?>
		<img onclick="$('#menu_<?php echo $membres['nom']; ?>').toggle();" src='../images/site/add.png' alt='Update'>
		<?php
		};
		if($anim||$virer_mem||$virer_chef||$virer_bras){echo  "<a href='action_mem.php?perso_id=".$membres['id']."&faction_id=".$faction_id."&action=del'> <img src='../images/site/delete.png' alt='Exclure le membre' title='Exclure le membre'></a> |</li>";}
			else {echo "</li>";};
				
		if($anim||$gestion_grade||$virer_chef)
			{
			echo"<br/><div id='menu_".$membres['nom']."' style='display:none;'>";
			$reponsegrade = mysql_query("SELECT grade_id,nom FROM faction_grades WHERE faction_id = '$faction_id'") or die (mysql_error());
			echo "<select name='grade_id'>" ;
			while ($res_grade = mysql_fetch_array($reponsegrade))
			{
				if ($res_grade['grade_id'] == $membre_grade_id)
				{
					echo "<option value='".$res_grade['grade_id']."' selected='selected'>".$res_grade['nom']."</option>";
				}else
				{
					echo "<option value='".$res_grade['grade_id']."'>".$res_grade['nom']."</option>";
				}
			}
			
			
			echo "
			</select>
			<input type='submit' name='upgrade' value='Modifier' class='bouton' /></div>";
			}
		// else {
			// echo "</form></div>";
			// }
		}

	?>
	</form>
	</ul>
	</div>
	<hr>

	<?php
	// Grades
	?>

	<div align='center' id='contact'>
	<p><b>Listing des grades :</b></p>
	<?php

	$grades = "SELECT * FROM faction_grades WHERE faction_id ='$faction_id' ORDER BY nom ASC";							

	?>
	<ul>
	<?php
		$resultat = mysql_query ($grades) or die (mysql_error());
		while ($grade = mysql_fetch_array ($resultat)){
		
		$droits = $grade['droits'];
		if($anim||$bal||$virer_chef)
	{
	echo "<li>Nom : <a href='action_grade.php?faction_id=".$faction_id."&grade_id=".$grade['grade_id']."&action=bal'>".$grade['nom']."</a>";
	}
	else {
			echo "<li>Nom : ".$grade['nom'];
		 }
		?>
		<img onclick="$('#menu_<?php echo $grade['grade_id']; ?>').toggle();" src='../images/site/add.png' alt='Update'>
		<?php
			if(($anim||$gestion_grade||$virer_chef)&&$grade['grade_id']!=4){echo"<a href='action_grade.php?grade_id=".$grade['grade_id']."&faction_id=".$faction_id."&action=del'> <img src='../images/site/delete.png' alt='Supprimer le grade' title='Supprimer le grade'></a></li>";};
		
		echo"<div id='menu_".$grade['grade_id']."' style='display:none;'>";
		if($anim||$gestion_grade||$virer_chef)
			{echo "<form name='icones' enctype='multipart/form-data' action='action_grade.php' method='POST'>
			<input name='nom_grade' type='text' value=\"".$grade['nom']."\" />
			<input type=\"hidden\" name=\"faction_id\" value=\"".$faction_id."\" />
			<table>
				<tr>
					<tr><td>
						<table>
								<tr><td height='21'>
								Chef (donne tous les droits)
								</td></tr>
								<tr><td height='21'>
								Exclure les bras droits
								</td></tr>
								<tr><td height='21'>
								Exclure les autres membres
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
							<tr>";
					for ($inc=0; $inc<=7; $inc++)
						{
								echo "<tr><td height='21'>";
								echo "<input type='checkbox' name='droit".$inc."' value='1'";if($droits[$inc]==1){echo 'checked="checked"';};echo ">";
								echo "</td></tr>";
						};
					echo	"</tr>
						</table>
					</td>
				</tr>
			</table>
			<input type='hidden' name='grade_id' value='".$grade['grade_id']."'/>
			<input type='submit' name='modif_grade' value='Modifier' class='bouton' />
			</form>";
			}
		else {echo "Droits du grade :<br/>
			<table>
				<tr>
					<tr><td>
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
							<tr>";
					for ($inc=0; $inc<=7; $inc++)
						{
								echo "<tr><td height='21'>";
								echo "<input type='checkbox' name='droit".$inc."' value='1'";if($droits[$inc]==1){echo 'checked="checked"';};echo ">";
								echo "</td></tr>";
						};
					echo	"</tr>
						</table>
					</td>
				</tr>
			</table>";
			}
			
			$membres = "SELECT * FROM persos 
							INNER JOIN faction_membres ON faction_membres.perso_id=persos.id
							WHERE persos.faction_id ='$faction_id' AND faction_membres.faction_grade_id=".$grade['grade_id']." ORDER BY persos.nom ASC";							
				$res_mem = mysql_query ($membres) or die (mysql_error());
				while ($membres = mysql_fetch_array ($res_mem)){
				?>
				<form action="action_mem.php" method="post">
				<?php
				$membre_id = $membres['perso_id'];
				$reponsegrade = mysql_query("SELECT faction_grade_id AS id FROM faction_membres WHERE faction_id = '$faction_id' AND perso_id='$membre_id'") or die (mysql_error());
				$membre_grade_id=mysql_fetch_array($reponsegrade);
				$membre_grade_id = $membre_grade_id['id'];
				
				$reponse_pos = mysql_query("SELECT pos_x, pos_y, carte_id FROM damier_persos WHERE perso_id = '$membre_id'") or die (mysql_error());
				$membre_pos = mysql_fetch_array($reponse_pos);
				$posx = $membre_pos['pos_x'];
				$posy = $membre_pos['pos_y'];
				$carte = $membre_pos['carte_id'];
				
				$reponse_pos = mysql_query("SELECT nom FROM cartes WHERE id = '$carte'") or die (mysql_error());
				$membre_pos = mysql_fetch_array($reponse_pos);
				$nom_carte = $membre_pos['nom'];
				
				$reponsegrade = mysql_query("SELECT nom FROM faction_grades WHERE faction_id = '$faction_id' AND grade_id='$membre_grade_id'") or die (mysql_error());
				$membre_grade=mysql_fetch_array($reponsegrade);
				$membre_grade = $membre_grade['nom'];
				
					echo "<li>Id : ".$membres['perso_id']." Nom : <a href='../messagerie/index.phpid=".$perso_id."&dest=".$membres['perso_id']."'>".$membres['nom']."</a> | Grade : $membre_grade |";
					if ($anim||$gps||$virer_chef){echo "Position : $posx/$posy $nom_carte |";}
					echo "<input type=\"hidden\" name=\"perso_id\" value=\"".$membres['perso_id']."\" />
						  <input type=\"hidden\" name=\"faction_id\" value=\"".$faction_id."\" />";
				if($anim||$gestion_grade||$virer_chef)
				{
				?>
				<img onclick="$('#menu_<?php echo $membres['nom']; ?>_').toggle();" src='../images/site/add.png' alt='Update'>
				<?php
				};
				if($anim||$virer_mem||$virer_chef||$virer_bras){echo  "<a href='action_mem.php?perso_id=".$membres['id']."&faction_id=".$faction_id."&action=del'> <img src='../images/site/delete.png' alt='Exclure le membre' title='Exclure le membre'></a> |</li>";}
					else {echo "</li>";};
						
				if($anim||$gestion_grade||$virer_chef)
					{
					echo"<br/><div id='menu_".$membres['nom']."_' style='display:none;'>";
					$reponsegrade = mysql_query("SELECT grade_id,nom FROM faction_grades WHERE faction_id = '$faction_id'") or die (mysql_error());
					echo "<select name='grade_id'>" ;
					while ($res_grade = mysql_fetch_array($reponsegrade))
					{
						if ($res_grade['grade_id'] == $membre_grade_id)
						{
							echo "<option value='".$res_grade['grade_id']."' selected='selected'>".$res_grade['nom']."</option>";
						}else
						{
							echo "<option value='".$res_grade['grade_id']."'>".$res_grade['nom']."</option>";
						}
					}
					
					
					echo "
					</select>
					<input type='submit' name='upgrade' value='Modifier' class='bouton' /></form></div>";
					}
				else {
					
				
				
					}
				}
				echo "</div>";
		}

	echo "</ul><br/>";
	if($anim||$bal||$virer_chef)
	{
	echo "<a href='action_grade.php?faction_id=".$faction_id."&grade_id=0&action=bal'>Baler l'ensemble des membres</a>";
	}?>

	<?php
	if($anim||$gestion_grade||$virer_chef)
			{?>
	<hr>
	<div align='center' id='contact'>
	<p><b>Cr&eacute;er un grade :</b></p>
			<form name='Grade' enctype='multipart/form-data' action='action_grade.php' method='POST'>
			Nom : <input name='nom_grade' type='text' value='' />
			<input type='hidden' name='faction_id' value='<?php echo $faction_id; ?>' />
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

	// Invitation de membres
	if ($anim||$inviter_mem||$virer_chef)
	{
	?>
	<hr>
	<div align='center' id='contact'>
	<p><b>Inviter un nouveau membre :</b></p>
	<form name='add_mem' action="action_mem.php" method="post">
	<input type='hidden' name='faction_id' value='<?php echo $faction_id; ?>' />
	<ul>
	<li>Vous pouvez faire l'invitation grâce au nom ou l'id.</li>
	<li><table>
	<tr><td>Nom/Id :</td><td><input name="perso_id" type="text" value='' /></td></tr>
	</table></li>
	<li><input type="submit" name="ask_mem" value="Proposer" class="bouton" /></li>
	</ul>
	</form>
	<br/>
	<p>Liste des personnes r&eacute;clamant l'entr&eacute;e dans la faction :</p> 
	<form method="post">
	<table align='center'>
		<tr>
		<td><b>Nom du perso (Mat.) [XP]</b></td><td>Accepter</td><td width="55" >Refuser</td>
		</tr>
	<?php 
	$sql_demande 	= "SELECT perso_id FROM wait_faction WHERE faction_id = $faction_id AND demandeur=1";
	$res_demande	= mysql_query (mysql_real_escape_string($sql_demande)) or die (mysql_error());
	while($demande = mysql_fetch_array ($res_demande))
	{
		$demandeur_id = $demande['perso_id'];?>
			<tr>
	<?php if (isset($demandeur_id)) 
			{
			$sql_nom 	= "SELECT 	persos.nom AS nom,
									caracs.px AS xp
							FROM persos
								INNER JOIN caracs
									ON caracs.perso_id = persos.id
									WHERE id = '$demandeur_id'";
			$res_nom	= mysql_query ($sql_nom) or die (mysql_error());
			$demandeur 		= mysql_fetch_array ($res_nom);
			$demandeur_xp	= $demandeur['xp'];
			$demandeur_nom 	= $demandeur['nom'];
			?>
				<td align='center'><?php echo '<b>'.$demandeur_nom.'</b> ('.$demandeur_id.') ['.$demandeur_xp.']';?></td><td align='center'><input type="radio" name="oui<?php echo $demandeur_id;?>" value="1" /></td><td align='center'><input type="radio" name="oui<?php echo $demandeur_id;?>" value="0" /></td>
	<?php		}
			echo '</tr>';
			}?>
		<tr>
		<td></td><td align='center' colspan='2'><input type="submit" name="demande_factions" value="Valider" /></td>
		</tr>
	</table>
	</form>

	</div>
	<?php
	echo "</div>";
	}
	}else{
		echo "vous n'etes pas autorise a effectuer cette action";
	}
}else echo "<p>Cette faction n'existe pas.</p>";
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
