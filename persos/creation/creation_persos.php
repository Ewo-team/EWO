<?php

if(!defined('CREATION')) {
    exit;
}

include(SERVER_ROOT. "/persos/fonctions.php");

include(SERVER_ROOT."/template/header_new.php");
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);

$js->addLib('ckeditor/ckeditor');
$js->addLib('ckeditor/sample');
$js->addScript('wysiwyg');

$sql = "SELECT `classes`.`id`, `classes`.`titre`, `classes`.`sub`, `classes`.`description` FROM  `classes`, `camps` WHERE `classes`.`camps` = `camps`.`id` AND `camps`.`nom` = '".$_SESSION['CreationPerso']['Race']."' ORDER BY  `classes`.`position` ASC ";

$dao = \persos\PersosDAO::getInstance();

$dao->query($sql);

$tableau = $dao->fetchAll_assoc();

?>

<link href="<?php echo SERVER_URL; ?>/js/lib/ckeditor/sample.css" rel="stylesheet" type="text/css" />

<div id="page_persos">  
	<form action="ajout_perso.php" method="post" name="personnage">
	<h2>Informations n&eacute;cessaires &agrave; la cr&eacute;ation de votre/vos personnage(s). </h2>

<!-- Debut du coin -->
<div class="upperleft" id='coin_75'>
	<div class="upperright">
		<div class="lowerleft">
			<div class="lowerright creation_<?php echo $_SESSION['CreationPerso']['Race'];?>">
			<!-- conteneur -->
<?php if($_SESSION['CreationPerso']['Gameplay'] == 'T3') {    
    $texte = "";
 ?>
	<table width="100%" border="0">
		<tr>
			<td align="left">
				<?php if(isset($_SESSION['erreur']['perso'])){echo $_SESSION['erreur']['perso'];} ?> <br />
				<a class="button" href="annuler.php">Annuler</a>
			</td>
		</tr>

		<tr>
			<td>
			<label for="nom_perso">Nom du personnage :
				<input name="nom" type="text" value="" maxlengh='64' />
			</label>
			</td>
		</tr>		
		<tr>
			<td>
				<select name='sexe' title='Choisissez le sexe de votre personnage'>
					<option value='1'>Homme</option>
					<option value='2'>Femme</option>
					<option value='3'>Autre</option>
				</select>
			</td>
		</tr>

		<tr align="center">
			<td>
				<?php
                                
                                    $liste = array();
                                
                                    echo '<table><tr>';
                                    for($i = 0; $i <= 8; $i++) {
                                        echo '<th width="10%">'.$tableau[$i]['titre'].'</th>';
                                        $key = $tableau[$i]['titre'] . ', ' . $tableau[$i]['sub'];
                                        $value = $tableau[$i]['id'];
                                        $liste[$key] = $value;
                                    }
                                    echo '</tr>';
                                    
                                    echo '<tr>';
                                    for($i = 0; $i <= 8; $i++) {
                                        echo '<td>'.$tableau[$i]['sub'].'</td>';
                                    }
                                    echo '</tr>';   
                                    
                                    echo '<tr>';
                                    for($i = 0; $i <= 8; $i++) {
                                        echo '<td>'.$tableau[$i]['description'].'</td>';
                                    }
                                    echo '</tr></table>';   
                                    
                                    echo \conf\Helpers::getSelectOption($liste, "choixclasse", null, "Sélectionnez votre classe");

                                ?>

			</td>
		</tr>
		<tr>
			<td align="right">
				<input type="submit" name="Submit2" value="Donnez vie à votre personnage" />
				<input type="reset" name="Submit" value="Effacer" />
			</td>
		</tr>
	</table>
<?php } else {	?>   
	<table width="100%" border="0">
		<tr>
			<td align="left">
				<?php if(isset($_SESSION['erreur']['perso'])){echo $_SESSION['erreur']['perso'];} ?> <br />
				<a class="button" href="annuler.php">Annuler</a>
			</td>
		</tr>

		<tr>
                        <td>
                                <label for="nom_perso">Nom du 1er personnage :
                                        <input name="nom1" type="text" value="" maxlengh='64' />
                                </label>
                        </td>
			<td>
				<select name='sexe1' title='Choisissez le sexe du personnage'>
					<option value='1'>Homme</option>
					<option value='2'>Femme</option>
					<option value='3'>Autre</option>
				</select>
			</td>
                        <td>
                            <?php echo \conf\Helpers::getSelectOption($liste, "choixclasse1", null, "Sélectionnez votre classe"); ?>
                        </td>
                         
		</tr>	
		<tr>
                        <td>
                                <label for="nom_perso">Nom du 2ème personnage :
                                        <input name="nom1" type="text" value="" maxlengh='64' />
                                </label>
                        </td>
			<td>
				<select name='sexe1' title='Choisissez le sexe du personnage'>
					<option value='1'>Homme</option>
					<option value='2'>Femme</option>
					<option value='3'>Autre</option>
				</select>
			</td>
                        <td>
                            <?php echo \conf\Helpers::getSelectOption($liste, "choixclasse1", null, "Sélectionnez votre classe"); ?>
                        </td>
                         
		</tr>	
		<tr>
                        <td>
                                <label for="nom_perso">Nom du 3ème personnage :
                                        <input name="nom1" type="text" value="" maxlengh='64' />
                                </label>
                        </td>
			<td>
				<select name='sexe1' title='Choisissez le sexe du personnage'>
					<option value='1'>Homme</option>
					<option value='2'>Femme</option>
					<option value='3'>Autre</option>
				</select>
			</td>
                        <td>
                            <?php echo \conf\Helpers::getSelectOption($liste, "choixclasse1", null, "Sélectionnez votre classe"); ?>
                        </td>
                         
		</tr>	
		<tr>
                        <td>
                                <label for="nom_perso">Nom du 4ème personnage :
                                        <input name="nom1" type="text" value="" maxlengh='64' />
                                </label>
                        </td>
			<td>
				<select name='sexe1' title='Choisissez le sexe du personnage'>
					<option value='1'>Homme</option>
					<option value='2'>Femme</option>
					<option value='3'>Autre</option>
				</select>
			</td>
                        <td>
                            <?php echo \conf\Helpers::getSelectOption($liste, "choixclasse1", null, "Sélectionnez votre classe"); ?>
                        </td>
                         
		</tr>	                
		<tr>

		</tr>

		<tr align="center">
			<td>
				<?php
                                
                                    $liste = array();
                                
                                    echo '<table><tr>';
                                    for($i = 0; $i <= 8; $i++) {
                                        echo '<th width="10%">'.$tableau[$i]['titre'].'</th>';
                                        $key = $tableau[$i]['titre'] . ', ' . $tableau[$i]['sub'];
                                        $value = $tableau[$i]['id'];
                                        $liste[$key] = $value;
                                    }
                                    echo '</tr>';
                                    
                                    echo '<tr>';
                                    for($i = 0; $i <= 8; $i++) {
                                        echo '<td>'.$tableau[$i]['sub'].'</td>';
                                    }
                                    echo '</tr>';   
                                    
                                    echo '<tr>';
                                    for($i = 0; $i <= 8; $i++) {
                                        echo '<td>'.$tableau[$i]['description'].'</td>';
                                    }
                                    echo '</tr></table>';   

                                ?>

			</td>
		</tr>
		<tr>
			<td align="right">
				<input type="submit" name="Submit2" value="Donnez vie à vos personnage" />
				<input type="reset" name="Submit" value="Effacer" />
			</td>
		</tr>
	</table>                        
<?php }	?>                         
	</form>
				<!-- fin conteneur -->
			</div>
		</div>
	</div>
</div>
<!-- Fin du coin -->

	<p class='centrage'><i>(Utilisez des pseudos lisibles et pronon&ccedil;ables pour vos personnages, leur survie en d&eacute;pend !)</i></p>
</div>

<?php

if(isset($_SESSION['erreur']['perso'])){$_SESSION['erreur']['perso'] = '';}
//-- Footer --
include(SERVER_ROOT."/template/footer_new.php");
//------------
?>
