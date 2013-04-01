<?php
//-- Header --
$root_url = "./../..";
include __DIR__ . '/../../conf/master.php';
include(SERVER_ROOT."/template/header_new.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/
?>
<div align='center' id='contact'>
<h2>Gestion des icones</i></h2>
<p>[ <a href=''>Retour</a> ]</p>

<form name='icones' enctype="multipart/form-data" action="upload_image.php" method="POST">
<table>
<tr>
	<td>
			<b>Race :</b><br />
				<select name="race">
					<?php
					$reponse = mysql_query("SELECT race_id,nom FROM races WHERE grade_id = -2");
					while ($race = mysql_fetch_array($reponse)){
							echo "<option value='".$race['race_id']."'>".$race['nom']."</option>";
					}
					?>
					<option value='0' selected>Perso</option>
				</select>
				
	</td>
	<td>
			<b>Grade :</b><br />
				<select name="grade">
					<?php
					for($inc=-1; $inc<=5; $inc++){
					if ($inc==0)
						{
							echo "<option value='".$inc."' selected='selected'>Grade ".$inc."</option>";
						}
					else echo "<option value='".$inc."'>Grade ".$inc."</option>";
					}
					?>
				</select>
				
	</td>
</tr>
<tr>
	<td  colspan="2"><br />* Pour ajouter une ic&ocirc;ne personnelle, utiliser la race : Perso, grade 0, XP MAX 999 999</td>
</tr>
<tr>
	<td  colspan="2">
			<b>Ecart d'xp pour afficher l'icone :</b><br />
		  min : <input name="px" type="text" value='0' size='6'/> max : <input name="px_max" type="text" value='0' size='6'/>
	</td>
</tr>

<tr>
	<td  colspan="2">
		<b>Images ic&ocirc;ne :</b><br />
    <input name="fichier" type="file" />
		<i>Image : png, jpg, gif; taille maxi 45*33</i>
	</td>
</tr>

<tr>
	<td  colspan="2">
		<input type="submit" value="Ajouter" class="bouton" />
	</td>
</tr>
</table>
</form>

<p><hr width='60%' /></p>
<form action="index.php" method="post" name="race">
	Choisissez une race :
		<select name="race_id">
		<?php
		// Affichage des différents choix pour les camps
		$liste_race = mysql_query("SELECT race_id,nom FROM races WHERE grade_id=0");
			
		// Récupération des camps déjà enregistrés dans la base de données.
			
		while ($rep_race = mysql_fetch_array($liste_race, MYSQL_NUM))
			{
					if (isset($_POST['race_id']) && $rep_race[0] == $_POST['race_id'])
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
			?>
	</select><input type="submit" value="Selectionner" name="Select_race">
</form>
Par d&eacute;faut les personnages ont l'ic&ocirc;ne du grade 0 (pondérée par l'xp bien sur), &agrave; moins qu'une ic&ocirc;ne pour leur grade n'existe.
<?php
if (isset($_POST['race_id']))
{
$race_id = $_POST['race_id'];
$races = "SELECT grade_id, camp_id,nom FROM races WHERE camp_id='$race_id' ORDER BY race_id ASC";							
$resultat = mysql_query ($races) or die (mysql_error());
while ($race = mysql_fetch_array ($resultat)){

	echo"<p><b>".$race['nom']." : Grade ".$race['grade_id']."</b></p>";
	
	echo"<ul>";
	
	$icones = "SELECT*FROM icone_persos WHERE camp_id = '".$race['camp_id']."' AND grade_id = '".$race['grade_id']."' ORDER BY xp_min ASC";							
	$result = mysql_query ($icones) or die (mysql_error());
	while ($icone = mysql_fetch_array ($result)){
		
		echo"<li><img src='".$root_url."/images/".$icone['icone_url']."' alt='persos'> ID : ".$icone['id']." | Xp : ".$icone['xp_min']." > ".$icone['xp_max']."";
	?>
	

			 <img onclick="$('#menu_<?php echo $icone['id']; ?>').toggle();" src="<?php echo $root_url; ?>/images/site/add.png" alt='Update'>
	
		<?php
		 
		echo"<a href='del_icone.php?id=".$icone['id']."'> <img src='".$root_url."/images/site/delete.png' alt='Supprimer'></a></li>";
		
		echo"<div id='menu_".$icone['id']."' style='display:none;'>		
		<form name='icones' enctype='multipart/form-data' action='maj_xp.php' method='POST'>
		<input name='icone_id' type='hidden' value='".$icone['id']."'/>
		xp min : <input name='px' type='text' value='".$icone['xp_min']."' size='6'/> 
		xp max : <input name='px_max' type='text' value='".$icone['xp_max']."' size='6'/>
		<input type='submit' value='Modifier' class='bouton' />
		</form></div>";
	}
	echo"</ul>";
}
}
?>

<p><b>icone personnelle</b></p>

<?php	echo"<ul>";
	
	$icones = "SELECT*FROM icone_persos WHERE camp_id = '0' ORDER BY id DESC";							
	$result = mysql_query ($icones) or die (mysql_error());
	while ($icone = mysql_fetch_array ($result)){
		
		echo"<li><img src='".$root_url."/images/".$icone['icone_url']."' alt='persos'> ID : ".$icone['id']."";
		echo"<a href='del_icone.php?id=".$icone['id']."'> <img src='".$root_url."/images/site/delete.png' alt='Supprimer'></a></li>";
	}
	echo"</ul>";

?>

</div>

<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
