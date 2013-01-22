<?php
/**
 * Affiche le repertoire des contactes pour la messagerie
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 2.1
 * @package messagerie
 */
if (isset($template_on)){
?>
<h2>Mon répertoire</h2>

<!-- Debut du coin -->
<div class="upperleft" id='coin_50'>
	<div class="upperright">
		<div class="lowerleft">
			<div class="lowerright">
			<!-- conteneur -->
<div id='repertoire_ok' class="action_ok" style='display:none'></div>
<div id='voir_repertoire'>[<a href='#' onclick="$('#repertoire').toggle();"><img src='../images/site/add.png' alt='voir'>Voir mon répertoire</a>]</div>

<div id='repertoire' style='display:none'>
	<div id='repertoire_contact'>
		<?php

		$repertoire = "SELECT repertoire.id AS id, repertoire.contact_id AS contact_id, persos.nom AS nom FROM repertoire INNER JOIN persos ON repertoire.contact_id = persos.id WHERE repertoire.perso_id =  '$id_perso'";

		$resultat = mysql_query ($repertoire) or die (mysql_error());
		$i = 0;
		while ($contact = mysql_fetch_array ($resultat)){

		echo "<div id='".$i."'><p><span onclick=\"contacter(".$contact['contact_id'].",'".$contact['nom']."')\">".$contact['nom']." (".$contact['contact_id'].")</span><span onclick=bal_rep_del('".$contact['id']."','".$id_perso."','".$i."')> <img src='../images/site/delete.png' alt='Supprimer'></span></p></div>";
		$i++;
		}

		$_SESSION['temps']['lien'] = "../bal/index.php?id=$id_perso";
		?>
	</div>
	<form name='contact'>
		<br />
		<input type="text" name="contact" size="7" value="" />
		<input type="hidden" name="personnage" size="13" value="<?php echo $id_perso; ?>" />
		<input class="bouton" type="button" value="Ajouter contact" onclick="javascript:bal_rep_ajout(document.contact.contact.value, document.contact.personnage.value);" />
	</form>
</div>

			<!-- fin conteneur -->
			</div>
		</div>
	</div>
</div>
<!-- Fin du coin -->
<?php
}
?>
