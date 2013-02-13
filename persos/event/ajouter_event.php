<?php
//-- Header --
$root_url = "..";
include($root_url."/template/header_new.php");
include_once($root_url.'/event/eventManager.php');
include_once($root_url.'/event/eventFormatter.php');

/*-- Connexion requise --*/
ControleAcces('anim;admin',1);
/*-----------------------*/
	

if(!isset($_POST['mat']) || (!isset($_POST['message']) && !isset($_POST['id']))) {
	$sql = 'SELECT id, texte FROM evenements_texte';
	$messages = mysql_query($sql) or die (mysql_error());
	
	?>
<h2>Créer un événement personnalisé</h2>

<!-- Debut du coin -->
<div class="upperleft" id='coin_75'>
	<div class="upperright">
		<div class="lowerleft">
			<div class="lowerright">
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
		</div>
	</div>
</div>	
	<?php
	
} else {
	$mat = $_POST['mat'];
	if(isset($_POST['id']) && $_POST['id'] != 0) {
		$id = (is_numeric($_POST['id'])) ? $_POST['id'] : 1;
	} else {
		$message = mysql_real_escape_string($_POST['message']);
		$sql = "INSERT INTO evenements_texte(id, texte) VALUES ('','$message')";
		mysql_query($sql);
		$id = mysql_insert_id();
	}
	
	$evman = new EventManager();
	
	$ev = $evman->createEvent('anima');
	$ev->setSource($mat,eventFormatter::convertType('perso'));
	$ev->infos->addPublicInfo('m',$id);	
	
	echo "l'événement à été ajouté !<br />
	<a href='liste_events.php?id=$mat'>Voir les événements</a>";
}

//-- Footer --
include($root_url."/template/footer_new.php");
//------------
