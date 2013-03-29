<?php
//-- Header --
$root_url = "./../..";

include __DIR__ . '/../../conf/master.php';
include(SERVER_ROOT."/template/header_new.php");
include ("Actions.class.php");
include ("Effet.class.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/

// Si la variable  $_SESSION['temp']['erreurs'] est définie, alors stocke sa valeur dans la variable $msg_error .
if(isset($_SESSION['temp']['erreurs'])){
	$msg_error = $_SESSION['temp']['erreurs'];
	unset($_SESSION['temp']['erreurs']);
}
// Sinon le message d'erreur sera vide.
else{
	$msg_error="";
}

if(isset($_REQUEST['id'])) {
	if(is_numeric($_REQUEST['id'])) {
		$action = Actions::selectionActions($_REQUEST['id']);
	}
} else {
	$action = new Actions();
}

if(count($action->nom) > 1) {
	$nomMultiple = true;
	$liste = $action->nom;
	$nom[0] = (isset($liste[0])) ? $liste[0] : '';
	$nom[1] = (isset($liste[1])) ? $liste[1] : '';
	$nom[2] = (isset($liste[2])) ? $liste[2] : '';
	$nom[3] = (isset($liste[3])) ? $liste[3] : '';
} else {
	$nom[0] = $nom[1] = $nom[2] = $nom[3] = $action->nom[0];
}

$index = 0;

function afficheListeEffets($type = 'nouveau', $ciblage = null) {

	global $index;

	static $listeEffets = array(
		"nouveau" => "-----Nouveau-----",
		"pv" => "Changement de PV",
		"alter_att" => "Altération d'attaque",
		"alter_def" => "Altération de défense",
		"alter_effet" => "Altération d'effets",
		"alter_esq_mag" => "Altération d'esquive magique",
		"alter_force" => "Altération de force",
		"alter_niv_mag" => "Altération de niveau de magie",
		"alter_mouv" => "Altération de mouvement",
		"alter_pa" => "Altération de PA",
		"alter_perception" => "Altération de perception",
		"alter_pv" => "Altération de PV",
		"alter_recup_pv" => "Altération de récupération de PV",
		"alter_res_mag" => "Altération de résistance magique",
		"alter_res_phy" => "Altération de résistance physique",
		"xp" => "Gain d'xp",
		"aspire_att" => "Aspiration d'attaque",
		"aspire_def" => "Aspiration de défense",
		"aspire_force" => "Aspiration de force",
		"aspire_mouv" => "Aspiration de mouvement",
		"aspire_pa" => "Aspiration de pa",
		"aspire_perception" => "Aspiration de perception",
		"aspire_pv" => "Aspiration de pv",
		"aspire_recup_pv" => "Aspiration de récupération de pv",
		"aspire_res_mag" => "Aspiration de résistance magique",
		"trans_att" => "Transfert d'attaque",
		"trans_def" => "Transfert de défense",
		"trans_force" => "Transfert de force",
		"trans_mouv" => "Transfert de mouvements",
		"trans_pa" => "Transfert de pa",
		"trans_perception" => "Transfert de perception",
		"trans_pv" => "Transfert de pv",
		"trans_recup_pv" => "Transfert de récupération de pv",
		"trans_res_mag" => "Transfert de résistance magique",
		"trans_res_phy" => "Transfert de résistance physique",
		"brulessence" => "Brulessence",
		"dla" => "DLA",
		"event_mouv" => "Evenement 'Mouvement'",
		"home" => "Home",
		"immunite" => "Immunité",
		"invocation" => "Invocation",
		"permutation" => "Permutation",
		"reincarnum" => "Reincarnum",
		"retour" => "Retour",
		"sprint" => "Sprint",
		"suicide" => "Suicide",
		"teleportation" => "Téléportation",
                "dissipation" => "Dissipation"
	);

	if($type == 'nouveaucible' || $type == 'nouveaulanceur') {
		$form = '<select id="'.$type.'" disabled>';
	} else {	
		$form = '<select name="effet_type_'.$ciblage.'['.$index.']">';
	}
	foreach($listeEffets as $nom => $desc) {
		$form .= '<option value="'.$nom.'"';
		if($nom == $type) {
			$form .= ' selected';
		}
		$form .= '>'.$desc.'</option>';
	}
	$form .= '</select>';
	return $form;
}

?>	
<div align="center" id="contact">
	<form id="formulaire" action="validation.php" method="post">
	<input type="hidden" name="flag" value="true">
	<table>
		<tr>
			<td style="text-align: right;">ID:</td>
			<td><input type="hidden" name="id" value="<?php echo $action->id; ?>"><?php echo $action->id; ?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Nom simple <input type="radio" name="nomMultiple" value="simple" class="changeNom"<?php if(!isset($nomMultiple)) { echo ' checked'; } ?>> :</td>
			<td><input type="radio" name="nomMultiple" value="multiple" class="changeNom"<?php if(isset($nomMultiple)) { echo ' checked'; } ?>> Nom multiple</td>
		</tr>		
		<tr>
			<td style="text-align: right;" id="libelleNom">Nom de l'action:</td>
			<td><input type="text" name="nom1" value="<?php echo $nom[0]; ?>"></td>
		</tr>
		<tr class="nommultiple">
			<td style="text-align: right;">Nom pour les Parias:</td>
			<td><input type="text" name="nom2" value="<?php echo $nom[1]; ?>"></td>
		</tr>			
		<tr class="nommultiple">
			<td style="text-align: right;">Nom pour les Anges:</td>
			<td><input type="text" name="nom3" value="<?php echo $nom[2]; ?>"></td>
		</tr>	
		<tr class="nommultiple">
			<td style="text-align: right;">Nom pour les Démons:</td>
			<td><input type="text" name="nom4" value="<?php echo $nom[3]; ?>"></td>
		</tr>			
		<tr>
			<td colspan="2">Description<br>
			<textarea name="description" style="width: 400px; height: 100px; text-align: left;"><?php echo $action->description; ?></textarea></td>
		</tr>
		<tr>
			<td style="text-align: right;">Cout (en PA):</td>
			<td><input type="number" min="0" name="cout" value="<?php echo $action->cout; ?>"></td>
		</tr>
		<tr>
			<td style="text-align: right;">Cercle:</td>
			<td><select name="cercle">
			<option value="0"<?php if($action->cercle == 0) { echo ' selected'; } ?>>Cercle des Novices</option>
			<option value="1"<?php if($action->cercle == 1) { echo ' selected'; } ?>>Cercle du Feu</option>
			<option value="2"<?php if($action->cercle == 2) { echo ' selected'; } ?>>Cercle de la Glace</option>
			<option value="3"<?php if($action->cercle == 3) { echo ' selected'; } ?>>Cercle de l'Espace-Temps</option>
			<option value="4"<?php if($action->cercle == 4) { echo ' selected'; } ?>>Cercle de la Quiètude</option>
			<option value="5"<?php if($action->cercle == 5) { echo ' selected'; } ?>>Cercle de l'Effroi</option>
			<option value="6"<?php if($action->cercle == 6) { echo ' selected'; } ?>>Cercle du Désespoir</option>
			<option value="7"<?php if($action->cercle == 7) { echo ' selected'; } ?>>Technologie</option>
			</select></td>
		</tr>
		<tr>
			<td style="text-align: right;">Niveau:</td>
			<td><input type="number" min="0" max="5" name="niveau" value="<?php echo $action->niveau; ?>"></td>
		</tr>		
		<tr>
			<td style="text-align: right;">Races:</td>
			<td><select multiple="multiple" name="races[]">
			<option value="3"<?php if(array_key_exists('ange', $action->races)) { echo ' selected'; } ?>>Anges</option>
			<option value="4"<?php if(array_key_exists('demon', $action->races)) { echo ' selected'; } ?>>Démons</option>
			<option value="1"<?php if(array_key_exists('humain', $action->races)) { echo ' selected'; } ?>>Humains</option>
			<option value="2"<?php if(array_key_exists('paria', $action->races)) { echo ' selected'; } ?>>Parias</option>
			</select></td>
		</tr>			
		<tr>
			<td style="text-align: right;">Grade:</td>
			<td><input type="number" min="-2" max="5" name="grade" value="<?php echo $action->grade; ?>"></td>
		</tr>
		<tr>
			<td style="text-align: right;">Galon:</td>
			<td><input type="number" min="0" max="4" name="galon" value="<?php echo $action->galon; ?>"></td>
		</tr>		
		<tr>
			<td style="text-align: right;">Zone:</td>
			<td><input type="number" name="zone" value="<?php echo $action->zone; ?>"></td>
		</tr>
		<tr>
			<td style="text-align: right;">Prendre en compte la cible:</td>
			<td><input type="checkbox" name="cible" value="1"<?php if($action->cible==1) echo ' checked'; ?> /></td>
		</tr>
		<tr>
			<td style="text-align: right;">Prendre en compte le lanceur:</td>
			<td><input type="checkbox" name="lanceur" value="1"<?php if($action->lanceur==1) echo ' checked'; ?> /></td>
		</tr>
		<tr>
			<td style="text-align: right;">Type de ciblage:</td>
			<td><select name="type_cible">
			<option value="allie"<?php if($action->type_cible == 'allie') { echo ' selected'; } ?>>Allié</option>
			<option value="ennemi"<?php if($action->type_cible == 'ennemi') { echo ' selected'; } ?>>Ennemis</option>
			<option value="both"<?php if($action->type_cible == 'both') { echo ' selected'; } ?>>Tous</option>
			<option value="choix"<?php if($action->type_cible == 'choix') { echo ' selected'; } ?>>Choix</option>
			<option value="none"<?php if($action->type_cible == 'none') { echo ' selected'; } ?>>Aucun</option>
			</select></td>
		</tr>
		<tr>
			<td style="text-align: right;">Type d'action:</td>
			<td><select name="type_action">
			<option value="attaque"<?php if($action->type_action == 'attaque') { echo ' selected'; } ?>>Attaque</option>
			<option value="aura"<?php if($action->type_action == 'aura') { echo ' selected'; } ?>>Aura</option>
			<option value="entrainement"<?php if($action->type_action == 'entrainement') { echo ' selected'; } ?>>Entrainement</option>
			<option value="sort"<?php if($action->type_action == 'sort') { echo ' selected'; } ?>>Sort</option>
			<option value="suicide"<?php if($action->type_action == 'suicide') { echo ' selected'; } ?>>Suicide</option>
			<option value="sprint"<?php if($action->type_action == 'sprint') { echo ' selected'; } ?>>Sprint</option>
			<option value="reparation"<?php if($action->type_action == 'reparation') { echo ' selected'; } ?>>Réparation</option>
			</select></td>
		</tr>
		<tr>
			<td colspan="2">Effets du lanceur:<br />
			<table id="e_lanceur">
			<?php
				if(isset($action->effetsLanceur)) {
					
					foreach($action->effetsLanceur as $effet) {
						echo '<tr>';
						echo '<td>'.afficheListeEffets($effet->type, 'lanceur').'</td>';
						echo '<td><input type="text" name="effet_valeur_lanceur['.$index.']" value="'.$effet->valeur.'"></td>';
						echo '<td class="deleffect"><img src="'.$root_url.'/images/site/delete.png"></td>';
						echo '</tr>';	
						$index++;
					}	
									
				}
				echo '<tr><td><input type="hidden" id="index_lanceur" value="'.$index.'">';
				echo afficheListeEffets('nouveaulanceur');		
				echo '</td><td><input type="text" id="new_valeur_lanceur" value="0"></td><td id="new_effets_lanceur" class="addeffect"><img src="'.$root_url.'/images/site/add.png"></td></tr>';
			?>
			</table>
			</td>
		</tr>
		<tr>
			<td colspan="2">Effets de la cible:<br />
			<table id="e_cibles">
			<?php
				$index = 0;
				if(isset($action->effetsCible)) {
					
					foreach($action->effetsCible as $effet) {
						echo '<tr>';
						echo '<td>'.afficheListeEffets($effet->type, 'cible').'</td>';
						echo '<td><input type="text" name="effet_valeur_cible['.$index.']" value="'.$effet->valeur.'"></td>';
						echo '<td class="deleffect"><img src="'.$root_url.'/images/site/delete.png"></td>';
						echo '</tr>';	
						$index++;
					}	
									
				}
				echo '<tr><td><input type="hidden" id="index_cible" value="'.$index.'">';
				echo afficheListeEffets('nouveaucible');		
				echo '</td><td><input type="text" id="new_valeur_cible" value="0"></td><td id="new_effets_cible" class="addeffect"><img src="'.$root_url.'/images/site/add.png"></td></tr>';
			?>
			</table>
			</td>
		</tr>	
		<tr>
			<td colspan="2"><input type="submit" value="Enregistrer"></td>
		</tr>		
	</table>
	</form>
</div>
<?php

$js->addScript('admin/action');

//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
