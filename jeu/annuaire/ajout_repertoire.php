<?php
/**
 * Menu d'ajout dans le repertoire
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 
 * @package annuaire
 */
//-- Header --
$root_url = "..";
include($root_url."/template/header_new.php");
include ("AnnuaireDAO.php");
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/

$pseudo =	$_SESSION['rechercher']['pseudo'];
$contact_id = $_SESSION['rechercher']['matricule'];

$_SESSION['temps']['lien'] = "/annuaire/";

?>
<h2>Choix de votre personnage</h2>

<div id='contact' align='center'>
<p>Ajouter ce contact pour : </p>

<form method="post" action="ajouter_contact.php">
	<select name="personnage">
	<?php
		$utilisateur_id = $_SESSION['utilisateur']['id'];

		$conn = AnnuaireDAO::getInstance();
		
		$persos = $conn->SelectListPersoFromUser($utilisateur_id);		
		
		foreach($persos as $perso) {
			echo "<option value='".$perso['id_perso']."'>".$perso['nom_perso']."</option>";
		}
	?>
	</select>
	<input type="hidden" name="contact" size="13" value="<?php echo $contact_id; ?>" />
	<input type="submit" value="Ajouter <?php echo $pseudo ?> &agrave; votre liste de contacts" class='bouton'>
</form>
</div>

<?php		
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
