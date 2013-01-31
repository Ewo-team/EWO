<?php
/**
 * Index de l'Annuaire
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 
 * @package annuaire
 */
//-- Header --

$header['title'] = "Annuaire des personnages";
require_once __DIR__ . '/../../conf/master.php';
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
include(SERVER_ROOT . '/template/header_new.php');
//------------
?>

<div align='center'>
<h2>Annuaire des personnages EwOiens</h2>

<table>
	<tr>
		<td colspan='2'>Rechercher un personnage avec son matricule :</td>
	</tr>
	<tr>
		<td colspan='2'></td>
	</tr>
	<tr>
		<td>
			<form method="post" action="rechercher_personnage.php">
			<input type="text" name="matricule" size="13" />
		</td>
		<td>
			<input class="bouton" type="submit" value="Rechercher" />
			</form>
		</td>
	</tr>
	<tr>
		<td colspan='2'>&nbsp;</td>
	</tr>
	<tr>
		<td colspan='2'>Rechercher un matricule avec le nom d'un personnage :</td>
	</tr>
	<tr>
		<td colspan='2'></td>
	</tr>
	<tr>
		<td>
			<form method="post" action="rechercher_matricule.php">
			<input type="text" name="personnage" size="13" />
		</td>
		<td>
			<input class="bouton" type="submit" value="Rechercher" />
			</form>
		</td>
	</tr>
</table>

</div>
<?php
//-- Footer --
include( SERVER_ROOT . '/template/footer_new.php');
//------------
?>
