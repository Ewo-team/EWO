<?php
/**
 * Formulaire d'envoie d'une bal
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 2.1
 * @package messagerie
 */

include('balling-list.php'); 

$js->addScript('messagerie');
 
?><div id="scrolltop" style="text-align: right; padding-right: 50px;"><a href="#">[^]</a></div>
<div id='form-ok'></div>
<div id='form-load' style='display: none;'>Envoi des messages en cours</div>
<table class='bal_tab' width='900px' id='form-form'>
<tr>
	<td width='150px'>Destinataire(s) :</td><td><input id='mat' name="mat" class='bal_input_titre' size="40" type='text' value='<?php if (isset($_GET['dest'])){echo $_GET['dest'];}?>' />
	<?php
		if(count($balling) > 0) {
			echo '	<select id="balling-list" name="liste">
		<option value="null">--- Balling-list ---</option>';
			$style = '';
			foreach($balling as $ligne) {
				
				if(is_array($ligne)) {
					echo '	<option style="'.$style.'" value="'.$ligne[0].'">'.$ligne[1].'</option>';
				} else {
					if($ligne == 'admin') {
						$style = 'color: grey;';
					} else {
					
					}
				}
			}
			echo '	</select>';	
		}
	?>
	</td>
</tr>
<tr>
	<td>Titre :</td><td><input id='titre' name="titre" class='bal_input_titre' size="40" type='text' maxlength='60' /></td>
</tr><tr><td></td><td><?php
	
	
	$selecteur = '';
	$affiche = false;
	
	if($droits[1] == 1) {
		//admin
		$selecteur .= '<option value="admin">Message d\'administrateur</option>';
		$affiche = true;
	}
	
	if($droits[2] == 1) {
		//anim
		$selecteur .= '<option value="anim">Message d\'animateur</option>';
		$affiche = true;
	}
	
	if($droits[3] == 1) {
		//at
		$selecteur .= '<option value="at">Message de l\'Anti-Triche</option>';
		$affiche = true;
	}		
	
	if($affiche) {
		echo '<select id="type_message" name="type_message">
		<option value="joueur" selected>Message normal</option>',$selecteur,'</select>';
	} else {
		echo '<input type="hidden" name="type_message" value="joueur">';
	}

?></td></tr><tr>
	<td>Messages :</td><td><textarea id="text" name="text" class='bal_input_titre wysiwyg' cols="50" rows="7" name="commentaires"></textarea>

	</td>
</tr>
<tr>
	<td></td><td>
		<input type="hidden" id="mat_perso" name= "matperso" value="<?php echo $id_per; ?>">
		<input type="submit" id="form-submit" value="Envoyer">
</td>
</tr>
</table>