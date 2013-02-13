<?php
//-- Header --
require_once __DIR__ . '/../conf/master.php';
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
include(SERVER_ROOT . "/template/header_new.php");
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/

if (isset($_GET['id'])){

$id = mysql_real_escape_string($_GET['id']);

$id_utilisateur = $_SESSION['utilisateur']['id'];
$info = "SELECT * FROM persos WHERE utilisateur_id = '$id_utilisateur' AND id = '$id'";
																								
$resultat = mysql_query ($info) or die (mysql_error());
$infos = mysql_fetch_array ($resultat);

if(!isset($infos['id'])){
	$titre = "Erreur dans la matrice";
	$text = "Cette utilisateur ne possèdent pas ce personnage.";
	$root = "..";
	$lien = "..";
	gestion_erreur($titre, $text, $root, $lien, 1);	
}

$signature = isset($infos['options'][0]) ? $infos['options'][0] : 0;
$bal_reception = isset($infos['options'][3]) ? $infos['options'][3] : 0;
$bal_htmltxt = isset($infos['options'][4]) ? $infos['options'][4] : 0;

$_SESSION['temps']['page'] = "../persos/editer_perso.php?id=$id";

?>

<link href="../js/lib/ckeditor/sample.css" rel="stylesheet" type="text/css" />

<div align='center' id='contact'>
<h2>Edition de votre personnage</h2>

<!-- Debut du coin -->
<div class="upperleft" id='coin_75'>
	<div class="upperright">
		<div class="lowerleft">
			<div class="lowerright">
			<!-- conteneur -->
				<b>Pseudo : </b><?php echo $infos['nom']." (".$infos['id'].")"; ?>

				<p><b>Avatar :</b>
				<?php
					if($infos['avatar_url'] == ''){
					echo "<p><img src='".SERVER_URL."/images/avatar/no_avatar.png' alt='gravatar'></p>";	
					}else{
					echo "<p><img src='".SERVER_URL."/images/avatar/".$infos['avatar_url']."' alt='avatar'></p>";

					}
				?></p>

				<form enctype="multipart/form-data" action="upload_image.php" method="POST">
				<input name="id_perso" type="hidden" value='<?php echo $id; ?>' /><br />
				<input name="fichier" type="file" />
				<input type="submit" value="Uploader" class="bouton"/>
				</form> 
				<i>Image : png, jpg, gif; taille maxi 140*140</i>
				<p><i>Veuillez ne pas utiliser d'avatar provocant ou choquant.</i></p>
				<!-- fin conteneur -->
			</div>
		</div>
	</div>
</div>
<!-- Fin du coin -->
<div class="separation"></div>
<!-- Debut du coin -->
<div class="upperleft" id='coin_75'>
	<div class="upperright">
		<div class="lowerleft">
			<div class="lowerright">
			<!-- conteneur -->
				<b>Message du jour :</b><br />
				<form name='mdj' action="edition_mdj.php" method="post">
					<TEXTAREA cols="60" rows="4" name="mdj"><?php echo $infos['mdj']; ?></TEXTAREA>
					<input name="id_perso" type="hidden" value='<?php echo $id; ?>' /><br />
					<input type="submit" value="Modifier" class="bouton" />
				</form>
				<!-- fin conteneur -->
			</div>
		</div>
	</div>
</div>
<!-- Fin du coin -->
<div class="separation"></div>
<!-- Debut du coin -->
<div class="upperleft" id='coin_75'>
	<div class="upperright">
		<div class="lowerleft">
			<div class="lowerright">
			<!-- conteneur -->
				<b>Signature de la bal :</b><br />
				<form name='signature' action="edition_signature.php" method="post">
					<TEXTAREA cols="60" rows="4" name="signature" id="signature" class="wysiwyg"><?php echo $infos['signature']; ?></TEXTAREA>
					<input name="id_perso" type="hidden" value='<?php echo $id; ?>' /><br />
					<input type="submit" value="Modifier" class="bouton" />
				</form>		
				<br />

				<form name='signature_defaut' action="edition_signature_defaut.php" method="post">
					<input name="id_perso" type="hidden" value='<?php echo $id; ?>' />
					Envoyer votre signature en meme temps que votre bal :
					<input type="checkbox" name="signature_defaut" value='ok' <?php if($signature == "1"){echo "checked";} ?> /><br />
					<input type="submit" value="Modifier" class="bouton" />			
				</form>

				<form name='bal_mail' action="edition_bal.php" method="post">
					<input name="id_perso" type="hidden" value='<?php echo $id; ?>' />
					Recevoir un email lorsque je reçois un bal : 
					<input type="checkbox" name="bal_mail" value='ok' <?php if($bal_reception == "1"){echo "checked";} ?> /><br />
					Recevoir votre email dans la version suivante :<br />
					Html : <input type=radio name="bal_type" value="html" <?php if($bal_htmltxt == "0"){echo "checked";} ?>><br />
					Texte : <input type=radio name="bal_type" value="texte" <?php if($bal_htmltxt == "1"){echo "checked";} ?>><br />
					<input type="submit" value="Modifier" class="bouton" />
				</form>
				<!-- fin conteneur -->
			</div>
		</div>
	</div>
</div>
<!-- Fin du coin -->
<div class="separation"></div>
<!-- Debut du coin -->
<div class="upperleft" id='coin_75'>
	<div class="upperright">
		<div class="lowerleft">
			<div class="lowerright">
			<!-- conteneur -->
				<b>Background :</b><br />
				<form name='background' action="edition_background.php" method="post">
					<TEXTAREA cols="60" rows="8" name="background" id="background" class="wysiwyg"><?php echo $infos['background']; ?></TEXTAREA>					
					<input name="id_perso" type="hidden" value='<?php echo $id; ?>' /><br />
					<input type="submit" value="Modifier" class="bouton" />
				</form>
				<!-- fin conteneur -->
			</div>
		</div>
	</div>
</div>
<!-- Fin du coin -->
</div>

<?php

    $js->addLib('ckeditor/ckeditor');
    $js->addLib('ckeditor/sample');
    $js->addScript('wysiwyg');

}

//-- Footer --
include(SERVER_ROOT."/template/footer_new.php");
//------------
?>
