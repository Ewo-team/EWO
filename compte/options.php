<?php
/**
 * Compte, Options avancées
 *
 *	Affiche la page des options avancées
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package compte
 */
 
//-- Header --
$root_url = "..";
require_once($root_url."/template/header_new.php");
require_once('fonctions.php');
//------------

$id_utilisateur = $_SESSION['utilisateur']['id'];
$info = "SELECT email, jabberid, telephone FROM utilisateurs WHERE id = $id_utilisateur";									
																								
$resultat = mysql_query ($info) or die (mysql_error());
$infos = mysql_fetch_array ($resultat);

$options_utilisateur = getUserOptions($id_utilisateur);

?>

<div align="center">
<h2>Modification de vos options avancées</h2>
<table border="0">
	<tr>
		<form name='template' action="edition_template.php" method="post">
			<td>Apparence site et page de jeu : </td>
			<td>
				<?php echo getSelectOption('template',$options_utilisateur['template']); ?>
			</td>
			<td><input type="submit" value="Modifier" class="bouton" /></td>
		</form>
	</tr>
	<tr>
		<form name='infos' action="edition_bals.php" method="post">
			<td>Vitesse d'affichage des bals :</td>
			<td>
				<?php echo getSelectOption('time',$options_utilisateur['bals_speed']); ?>
			</td>
			<td><input type="submit" value="Modifier" class="bouton" /></td>
		</form>
	</tr>	
	<tr>
		<form name='grille' action="edition_grille.php" method="post">
			<td>Afficher la grille sur le damier :</td>
			<td>
				<?php
					$grille = grille_damier($id_utilisateur);
				?>
				<input type="checkbox" name="grille" value='ok' <?php if($grille == true){echo " checked";} ?> />
			</td>
			<td><input type="submit" value="Modifier" class="bouton" /></td>
		</form>
	</tr>
	<tr>
		<form name='grille' action="edition_rose.php" method="post">
			<td>Type de déplacement :</td>
			<td>
                            <?php echo getSelectOption('rose',$options_utilisateur['rose']); ?>
			</td>
			<td><input type="submit" value="Modifier" class="bouton" /></td>
		</form>
	</tr>        
	<tr>
		<form name='redirec' action="edition_redirec.php" method="post">
			<td>Redirection après connexion :</td>
			<td>
				<?php echo getSelectOption('redirection',$options_utilisateur['redirection']); ?>
			</td>
			<td><input type="submit" value="Modifier" class="bouton" /></td>
		</form>
	</tr>
	<tr>
		<form name='vacances' action="edition_vacances.php" method="post">
			<td>Vacances (effectif 48 heures après, à heure pile xxh00)</td>
			<?php  echo getVacancesButton($id_utilisateur); ?>
		</form>
	</tr>
	<!--<tr>
		<form name='' action="" method="post">
			<td></td>
			<td></td>
			<td></td>
		</form>
	</tr>-->	
</table>

</div>

<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
