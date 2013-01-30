<?php
/**
 * Conact - Index du formulaire de contact a l'équipe de ewo
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package contact
 */
 
//-- Header --
$root_url = "..";
$header['title'] = "Contact";
$header['desc'] = "Pour contacter l'équipe de gestion du projet Ewo";
include($root_url."/template/header_new.php");
//------------
?>

<h2>Formulaire de contact</h2>

<!-- Debut du coin -->
<div class="upperleft" id='coin_75'>
	<div class="upperright">
		<div class="lowerleft">
			<div class="lowerright">
			<!-- conteneur -->
			
<div id='contact' align="center">
	<table>
		<tr>
			<td width='150'><img src='../images/site/mail.png' alt='mail'></td>
			<td width='300'>
					<form name='contact' action="send_contact.php" method="post">
					Nom :<br />
					<input name="nom" type="text" size="20" /><br />
					Mail :<br />
					<input name="mail" type="text" size="20" /><br />
					Sujet :<br />
					<input name="sujet" type="text" size="30" /><br />
					Message :<br />
					<textarea rows="5" name="text" cols="60"></textarea><br />
					<p><input type="submit" value="Envoyer" class='bouton'></p>
				</form>
			</td>
		</tr>
	</table>
</div>

			<!-- fin conteneur -->
			</div>
		</div>
	</div>
</div>
<!-- Fin du coin -->

<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
