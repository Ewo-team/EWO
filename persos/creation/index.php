<?php
/**
 * Inscription - Page du formulaire d'inscription
 *
 * Formulaire d'inscription html pour ewo
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package inscription
 */

//-- Header --
$root_url = "..";
$header['title'] = "Inscription";
$header['desc'] = "Pour vous inscrire sur Ewo, cette page est la page obligatoire !";
include($root_url."/template/header_new.php");
//------------

if(isset($_SESSION['temp']['error'])){
	$msg_error = $_SESSION['temp']['error'];
}

?>

<div id='inscription' align="center">
<h2>Formulaire d'inscription</h2>

<!-- Debut du coin -->
<div class="upperleft" id='coin_75'>
	<div class="upperright">
		<div class="lowerleft">
			<div class="lowerright">
			<!-- conteneur -->		
	
			
<table border="0">
	<tr>
	<td width='450' align='center'>
		<form name='err' action="ControllerInscription.php" method="post">
		<table border="0" width="100%">
			<tr>
				<td></td>
				<td <?php if(isset($msg_error)){ echo "style='background-color:#FFB89F;'>$msg_error"; } ?>></td>
				<td></td>
			</tr>	
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<th scope="row" align="right">Nom du compte : </th>
				<td align="center"><input name="nom" type="text" maxlength="64" value="<?php if(isset($_SESSION['temp']['nom'])){ echo $_SESSION['temp']['nom']; }else{ echo '';} ?>"/></td>
			<td></td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<th scope="row" align="right">E-Mail : </th>
				<td align="center"><input name="email" type="text" maxlength="64" value="<?php if(isset($_SESSION['temp']['mail'])){ echo $_SESSION['temp']['mail']; }else{ echo '';} ?>"/></td>
			<td></td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<th scope="row" align="right">Mot de passe : </th>
				<td align="center"><input name="pass_inscription" type="password" maxlength="64" value="<?php if(isset($_SESSION['temp']['pass'])){ echo $_SESSION['temp']['pass']; }else{ echo '';} ?>"/></td>
			<td></td>
			</tr>
			<tr>
				<th scope="row" align="right">Confirmer mot de passe : </th>
				<td align="center"><input name="confirm_pass" type="password" maxlength="64" value="<?php if(isset($_SESSION['temp']['pass'])){ echo $_SESSION['temp']['pass']; }else{ echo '';} ?>"/></td>
			<td></td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
		<?php
			if($_TICKET == 1){
		?>
			<tr>
				<th scope="row" align="right">Ticket d'invitation : </th>
				<td align="center"><input name="numero" type="text" maxlength="250" value="<?php if(isset($_SESSION['temp']['numero'])){ echo $_SESSION['temp']['numero']; }else{ echo '';} ?>"/></td>
			<td></td>
			</tr>			
		<?php 
			}
		?>
			<tr>
				<td colspan="3" align="center"><p>[<a href="http://wiki.ewo-le-monde.com/doku.php?id=jeu:presentation_d_ewo">Charte d'inscription</a>]</p></td>
			</tr>
			<tr>
				<td colspan="3" align="center"><input type="submit" value="Valider" class="bouton" /></td>
			</tr>
		</table>
		</form>
	</td>
	<td><img src='../images/site/inscription.png' alt='inscription' /></td>
	</tr>
</table>


			<!-- fin conteneur -->
			</div>
		</div>
	</div>
</div>
<!-- Fin du coin -->

<p>En vous inscrivant &agrave; ce jeu vous acceptez les conditions pr&eacute;c&eacute;demment &eacute;nonc&eacute;es dans la charte.</p>

<p><i>(Attention ce jeu est chronophage, et risquerait de nuire &agrave; votre vie sociale ou de couple. Les responsables se d&eacute;gagent de toute responsabilit&eacute; sur ces sujets. ];D)</i></p>
</div>

<?php
session_destroy();
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
