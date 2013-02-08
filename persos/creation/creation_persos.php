<?php

if(!defined('CREATION')) {
    exit;
}

include(SERVER_ROOT. "/persos/fonctions.php");

include(SERVER_ROOT."/template/header_new.php");
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);

$js->addLib('ckeditor/ckeditor');
$js->addLib('ckeditor/sample');

?>

<link href="<?php echo SERVER_URL; ?>/js/ckeditor/sample.css" rel="stylesheet" type="text/css" />

<div id="page_persos">  
	<form action="ajout_perso.php" method="post" name="personnage">
	<h2>Informations n&eacute;cessaires &agrave; la cr&eacute;ation de votre/vos personnage(s). </h2>

<!-- Debut du coin -->
<div class="upperleft" id='coin_75'>
	<div class="upperright">
		<div class="lowerleft">
			<div class="lowerright creation_<?php echo $_SESSION['CreationPerso']['Race'];?>">
			<!-- conteneur -->
	<?php if($_SESSION['CreationPerso']['Gameplay'] == 'T3') { ?>
	<table width="100%" border="0">
		<tr>
			<td colspan="3" align="left">
				<?php if(isset($_SESSION['erreur']['perso'])){echo $_SESSION['erreur']['perso'];} ?> <br />
				<a class="button" href="annuler.php">Annuler</a>
			</td>
		</tr>
		<tr>
			<td colspan="3">
			<label for="nom_perso">Nom du personnage :
				<input name="nom" type="text" value="" maxlengh='64' />
			</label>
			</td>
		</tr>		
		<tr>
			<td></td>
			<td></td>
			<td align='right'>
				<select name='sexe' title='Choisissez le sexe de votre personnage'>
					<option value='1'>Homme</option>
					<option value='2'>Femme</option>
					<option value='3'>Autre</option>
				</select>
			</td>
		</tr>
		<tr align="center">
			<td colspan="3">
				<table>
					<tr>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="3" align="right">
				<label for="bg_perso">Br&egrave;ve histoire de votre personnage <span class='italic'>(il vous sera possible de l'éditer par la suite)</span> :<br /> 
				<textarea name="bg_perso" class="wysiwyg" id="bg_perso"></textarea>
				</label>
			</td>
		</tr>
		<tr>
			<td colspan="3" align="right">
				<input type="submit" name="Submit2" value="Donnez vie à votre personnage" />
				<input type="reset" name="Submit" value="Effacer" />
			</td>
		</tr>
	</table>
	<?php } ?>
	</form>
				<!-- fin conteneur -->
			</div>
		</div>
	</div>
</div>
<!-- Fin du coin -->

	<p class='centrage'><i>(Utilisez des pseudos lisibles et pronon&ccedil;ables pour vos personnages, leur survie en d&eacute;pend !)</i></p>
</div>

<?php

if(isset($_SESSION['erreur']['perso'])){$_SESSION['erreur']['perso'] = '';}
//-- Footer --
include(SERVER_ROOT."/template/footer_new.php");
//------------
?>
