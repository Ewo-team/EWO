<?php
//-- Header --
$root_url = "./../..";
include __DIR__ . '/../../conf/master.php';
include(SERVER_ROOT."/template/header_new.php");
//------------

/*-- Connexion basic requise --*/
ControleAcces('admin',1);
/*-----------------------------*/
?>
<div align='center' id='contact'>
<h2>Gestion des Galons</i></h2>
<p>[ <a href=''>Retour</a> ]</p>

<form name='icones' enctype="multipart/form-data" action="upload_image.php" method="POST">
<table>
<tr>
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
	<td  colspan="2">
		<b>Images galon :</b><br />
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

<p><b>Galons</b></p>

<?php	echo"<ul>";
	
	$icones = "SELECT*FROM icone_galons ORDER BY grade_id DESC";							
	$result = mysql_query ($icones) or die (mysql_error());
	while ($icone = mysql_fetch_array ($result)){
		
		echo "<li><img src='".$root_url."/images/".$icone['icone_url']."' alt='persos'> ID : ".$icone['id']." ";
		echo "Grade".$icone['grade_id'];
		echo "<a href='del_icone.php?id=".$icone['id']."'> <img src='".$root_url."/images/site/delete.png' alt='Supprimer'></a></li>";
	}
	echo"</ul>";

?>

</div>

<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
