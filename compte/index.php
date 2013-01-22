<?php
/**
 * Compte, Index
 *
 *	Affiche la page principal du compte
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package compte
 */
//-- Header --
$root_url = "..";
include($root_url."/template/header_new.php");
//------------

$id_utilisateur = $_SESSION['utilisateur']['id'];
$info = "SELECT email, jabberid, telephone FROM utilisateurs WHERE id = $id_utilisateur";									
																								
$resultat = mysql_query ($info) or die (mysql_error());
$infos = mysql_fetch_array ($resultat);

?>

<div id='inscription' align="center">
<h2>Modification de vos options d'utilisateur</h2>

<!-- Debut du coin -->
<div class="upperleft" id='coin_75'>
	<div class="upperright">
		<div class="lowerleft">
			<div class="lowerright">
			<!-- conteneur -->

<table border="0">
	<td width='450' align='center'>
		<table border="0" width="100%">
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<th scope="row" align="right">E-Mail : </th>
				<td align="center"><form name='mail' action="edition_email.php" method="post">
				<input name="email" type="text" maxlength="64" value='<?php echo $infos['email']; ?>' /> </td>
			<td><input type="submit" value="Modifier" class="bouton" /></form></td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<th scope="row" align="right" width='150'>Mot de passe : </th>
				<td align="center"><form name='pass' action="edition_pass.php" method="post">
				<input name="pass_modif" type="password" maxlength="64" /></td>
				<td><input type="submit" value="Modifier" class="bouton" /></form></td>
			</tr>
			<tr>
				<td></td>
				<td colspan="2"><i>Utilisez un mot de passe d'un minimum de 9 caractères pour plus de sécurité.</i></td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3" align='center'>[ <a href='options.php'>Options avancées</a> ]</td>
			</tr>		
			<tr>
				<td colspan="3" align="center"></td>
			</tr>
		</table>
		</form>
	</td>
	<td width='150' align='center'>
		<img src='../images/site/inscription.png'>
	</td>
</table>

				<!-- fin conteneur -->
			</div>
		</div>
	</div>
</div>
<!-- Fin du coin -->

</div>

<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
