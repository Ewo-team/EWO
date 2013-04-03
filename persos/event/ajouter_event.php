<?php
require_once __DIR__ . '/../../conf/master.php';

include_once SERVER_ROOT . '/template/header_new.php';

use \persos\eventManager\eventManager as eventManager;
use \persos\eventManager\eventFormatter as eventFormatter;


/*-- Connexion requise --*/
ControleAcces('anim;admin',1);
/*-----------------------*/
	

if(!isset($_POST['mat']) || (!isset($_POST['message']) && !isset($_POST['id']))) {
	$sql = 'SELECT id, texte FROM evenements_texte';
	$messages = mysql_query($sql) or die (mysql_error());
	
	?>
<h2>Créer un événement personnalisé</h2>

<!-- Debut du coin -->
<div>
			<!-- conteneur -->
			
<div class='news' align='center'>

<form action="eventperso.php" method="post">
<table>	
	<tr>
		<td>Matricule : </td>
		<td><input type="number" name="mat"></td>
	</tr>
	<tr>
		<td>Message existant : </td>
		<td><select name="id">
			<option value="0">Aucun</option>
		<?php
			while($ligne = mysql_fetch_assoc($messages)) {
				echo '<option value="'.$ligne['id'].'">'.$ligne['texte'].'</option>';
			}			
		?>
		</select></td>
	</tr>	
	<tr>
		<td>Nouveau message : </td>
		<td><input type="text" name="message"></td>
	</tr>	
	<tr>
		<td colspan="2"><input type="submit" value="Envoyer !"></td>
	</tr>	
</table>
</div>
</form>

			<!-- fin conteneur -->
</div>	
	<?php
	
} else {
	$mat = $_POST['mat'];
	if(isset($_POST['id']) && $_POST['id'] != 0) {
		$id = (is_numeric($_POST['id'])) ? $_POST['id'] : 1;
	} else {
		$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
		$sql = "INSERT INTO evenements_texte(id, texte) VALUES ('','$message')";
		mysql_query($sql);
		$id = mysql_insert_id();
	}
	
	$evman = new EventManager();
	
	$ev = $evman->createEvent('anima');
	$ev->setSource($mat,eventFormatter::convertType('perso'));
	$ev->infos->addPublicInfo('m',$id);	
	
	echo "l'événement à été ajouté !<br />
	<a href='.?id=$mat'>Voir les événements</a>";
}

//-- Footer --
include(SERVER_ROOT."/template/footer_new.php");
//------------
