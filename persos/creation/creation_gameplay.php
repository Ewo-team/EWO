<?php

if(!defined('CREATION')) {
    //exit;
}

include(SERVER_ROOT. "/persos/fonctions.php");

include(SERVER_ROOT."/template/header_new.php");
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);

/*-----------------------------*/

include(SERVER_ROOT."/persos/creation/controle_persos.php");


$js->addLib('ckeditor/ckeditor');
$js->addLib('ckeditor/sample');

?>

<link href="<?php echo SERVER_URL; ?>/js/ckeditor/sample.css" rel="stylesheet" type="text/css" />

<div id="page_persos">  
	<form action="ajout_perso.php" method="post" name="personnage">
	<h2>Informations n&eacute;cessaires &agrave; la cr&eacute;ation de vos personnages. </h2>
<?php

echo '<h3>'.$texte.'</h3>';


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
			</td>
		</tr>
		<tr>
			<td td colspan="3" align="right">
			<label for="type">Type de gameplay :
				<select name='type' title='Choisissez le gameplay de votre personnage'>
					<?php
					if ($creationT3) echo "<option value='3'>3 cases indépendantes</option>";
					if ($creationT4) echo "<option value='4'>4 cases solidaires & 2 cases indépendantes</option>";
					?>
				</select>
			</label>
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
				<input type="submit" name="Submit2" value="Passez à l'étape suivante" />
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

</div>

<?php
} // FIN PEUTCREER
if(isset($_SESSION['erreur']['perso'])){$_SESSION['erreur']['perso'] = '';}
//-- Footer --
include(SERVER_ROOT."/template/footer_new.php");
//------------
?>
