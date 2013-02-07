<?php

if(!defined('CREATION')) {
    exit;
}

include(SERVER_ROOT. "/persos/fonctions.php");

include(SERVER_ROOT."/template/header_new.php");
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
if(isset($admin_mode)) {
	ControleAcces('admin',1);
}
/*-----------------------------*/
if(!isset($admin_mode)) {
	include(SERVER_ROOT."/persos/creation/controle_persos.php");
	} else {
	$ange = '';
	$demon = '';
	$humain = '';
	$user_id = '';
	$texte = '';
	$peutCreer = true;
	if(isset($_GET['user_id'])) {
		$user_id=$_GET['user_id'];
	}
}

$js->addLib('ckeditor/ckeditor');
$js->addLib('ckeditor/sample');

?>

<link href="<?php echo SERVER_URL; ?>/js/ckeditor/sample.css" rel="stylesheet" type="text/css" />

<div id="page_persos">  
	<form action="ajout_perso.php" method="post" name="personnage">
	<h2>Informations n&eacute;cessaires &agrave; la cr&eacute;ation de votre personnage. </h2>
<?php
if (isset($camp)) {
	if($camp != NULL) echo '<h3>'.$texte.'</h3>';
}

if ($peutCreer) { //DEBUT PEUTCREER ?>
<!-- Debut du coin -->
<div class="upperleft" id='coin_75'>
	<div class="upperright">
		<div class="lowerleft">
			<div class="lowerright">
			<!-- conteneur -->

	<table width="600px" border="0">
		<tr>
			<td colspan="3" align="right">
					<?php if(isset($_SESSION['erreur']['perso'])){echo $_SESSION['erreur']['perso'];} ?> <br />
				<?php
				if(isset($admin_mode)) {
				?>
				<label for="user_id">Id de l'utilisateur auquel associer le personnage :
				<input name="utilisateur_id" type="text" value="<?php echo $user_id; ?>" maxlengh='32' />
				</label>
			</td>
		</tr>
		<tr>
			<td td colspan="3" align="right">
			<label for="user_id">Matricule du personnage :
			<input name="matricule" type="text" value="Pas encore disponible" disabled  maxlengh='32' />
			</label>
			</td>
		</tr>
		<tr>
			<td td colspan="3" align="right">
			<?php 
			}
			?>
			<label for="nom_perso">Nom du personnage :
				<input name="nom" type="text" value="" maxlengh='64' />
			</label>
			</td>
		</tr>
		<tr>
			<td td colspan="3" align="right">
			<label for="type">Type de gameplay :
				<select name='type' title='Choisissez le gameplay de votre personnage'>
					<?php
					if ($t1 >= 1 || isset($admin_mode)) echo "<option value='3'>3 cases indépendantes</option>";
					if ($t4 < 1 || isset($admin_mode)) echo "<option value='4'>4 cases solidaires</option>";
					?>
				</select>
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
			<td><img src="<?php echo SERVER_URL; ?>/images/races/race_a.gif" alt="Race Ange" width="100" height="100"></td>
			<td><img src="<?php echo SERVER_URL; ?>/images/races/race_h.gif" alt="Race Humain" width="100" height="100"></td>
			<td><img src="<?php echo SERVER_URL; ?>/images/races/race_d.gif" alt="Race Demon" width="100" height="100"></td>
		</tr>
		<tr align="center">
			<td><input type="radio" name="race" value="3" title="Vous allez choisir de devenir un Ange" <?php echo $ange; ?> /></td>
			<td><input type="radio" name="race" value="1" title="Vous allez choisir de devenir un Humain" <?php echo $humain; ?> /></td>
			<td><input type="radio" name="race" value="4" title="Vous allez choisir de devenir un Démon" <?php echo $demon; ?> /></td>
		</tr>
		<tr align="center">
			<td class="anges">Ange</td>
			<td class="humains">Humain</td>
			<td class="demons">D&eacute;mon</td>
		</tr>
		<tr align="center">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
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
} // FIN PEUTCREER
if(isset($_SESSION['erreur']['perso'])){$_SESSION['erreur']['perso'] = '';}
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
