<?php
//-- Header --
$root_url = "./../..";
include($root_url."/template/header_new.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/
?>
<div align='center' id='contact'>
<h2>Gestion des medailles</i></h2>
<p>[ <a href=''>Retour</a> ]</p>

<form name='medailles' enctype="multipart/form-data" action="upload_image.php" method="POST">
<table>

<tr>
	<td  colspan="2">
		<b>Images :</b><br />
    <input name="fichier" type="file" />
		<i>Image : png; taille maxi 22*22</i>
	</td>
</tr>

<tr>
	<td  colspan="2">
		<input type="submit" value="Ajouter" class="bouton" />
	</td>
</tr>
</table>
</form>
<a href="genere_tableau.php">Générer tableau des constantes</a>
<ul>
<?php
$dirname = '../../images/medaille/';
$dir = opendir($dirname); 

while($file = readdir($dir)) {
	if($file != '.' && $file != '..' && !is_dir($dirname.$file))
	{
		echo '<li>'.$file.' <img src="'.$dirname.$file.'"></li>';
	}
}

closedir($dir);
?>
</ul></div>
<?php

//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
