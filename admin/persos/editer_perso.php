<?php
require_once __DIR__ . '/../../conf/master.php';

include(SERVER_ROOT."/template/header_new.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/

if (isset($_POST['id_perso'])){
$id = mysql_real_escape_string($_POST['id_perso']);
$ok = 1;
}
if (isset($_GET['id'])){
$id = mysql_real_escape_string($_GET['id']);
$ok = 1;
}
if (isset($_POST['pseudo_perso'])){
$pseudo = mysql_real_escape_string($_POST['pseudo_perso']);
$ok = 1;
}

if ($ok == 1){

if(isset($pseudo)) {
    $info = "SELECT*FROM persos WHERE nom = '$pseudo'";																							
    $resultat = mysql_query ($info) or die (mysql_error());
    $infos = mysql_fetch_array ($resultat); 
    $id = $infos['id'];
} else {  
    
    //-- Info personnages
    $info = "SELECT*FROM persos WHERE id = '$id'";																							
    $resultat = mysql_query ($info) or die (mysql_error());
    $infos = mysql_fetch_array ($resultat);
}
$grade = $infos['grade_id'];
$race = $infos['race_id'];

//-- Caracs personnages
include(SERVER_ROOT."/persos/fonctions.php");

$carac_brute = calcul_caracs_no_alter($id);

$caracs = calcul_caracs($id);

$carac_alter_mag = select_caracs_alter_mag($id);

$sql="SELECT * FROM caracs_alter_mag WHERE perso_id='$id'";
$carac_alter_mag_detail = mysql_query ($sql) or die (mysql_error());

$sql="SELECT * FROM caracs_alter_plan WHERE perso_id='$id'";
$resultat = mysql_query ($sql) or die (mysql_error());
$carac_alter_plan = mysql_fetch_array ($resultat);

$sql="SELECT * FROM caracs_alter WHERE perso_id='$id'";
$resultat = mysql_query ($sql) or die (mysql_error());
$carac_alter = mysql_fetch_array ($resultat);
	
//-- Nom utilisateur propriétaire du personnage
$noms = "SELECT nom FROM utilisateurs WHERE id = '".$infos['utilisateur_id']."'";
$result = mysql_query ($noms) or die (mysql_error());
$utilisateur = mysql_fetch_array ($result);

//-- Position et plan du personnage
$sql = "SELECT damier_persos.pos_x AS pos_x, damier_persos.pos_y AS pos_y, damier_persos.carte_id AS carte_id FROM damier_persos WHERE perso_id = '$id'";
$resultat1 = mysql_query ($sql) or die (mysql_error());
$position = mysql_fetch_array ($resultat1);

$_SESSION['temps']['page'] = "./../../admin/persos/editer_perso.php?id=$id";

$pnj = ($infos['pnj'] == 1) ? 'checked' : '';
$mortel = ($infos['mortel'] == 1) ? 'checked' : '';

?>

<div align='center' id='contact'>
<h2>Edition de <i><?php echo $infos['nom']; ?> (ID : <?php echo $id; ?>)</i></h2>
<p>[ <a href='liste_persos.php'>Retour</a> ]</p>
<table>
<tr>
	<td></td><td>
		<form name='pseudo' action="edition_perso.php" method="post">
			<b>Pseudos personnages :</b><br />
		  	<input name="nom_perso" type="text" value="<?php echo htmlentities($infos['nom'], ENT_COMPAT, 'UTF-8'); ?>" />
			<input name="id_perso" type="hidden" value='<?php echo $id; ?>' />
			<input type="submit" value="Modifier" class="bouton" />
		</form>
	</td><td></td>
</tr>

<tr>
	<td></td><td>
		<form name='utilisateur' action="edition_perso.php" method="post">
			<b>Id de l'utilisateur de <?php echo $infos['nom']; ?> (<?php echo $utilisateur['nom'] ;?>) :</b><br />
		  	<input name="utilisateur_id" type="text" value='<?php echo $infos['utilisateur_id']; ?>' />
			<input name="id_perso" type="hidden" value='<?php echo $id; ?>' />
			<input type="submit" value="Modifier" class="bouton" />
		</form>
	</td><td></td>
</tr>

<tr>
	<td></td><td>
		<form name='utilisateur' action="edition_perso.php" method="post">
			<b>Attributs</b><br />
                        <input name="id_perso" type="hidden" value='<?php echo $id; ?>' />
                        <input name="change_attribute" type="hidden" value='1' />
                        PNJ : <input name="pnj" type="checkbox" value="pnj" <?php echo $pnj; ?>/><br />
                        Mortel : <input name="mortel" type="checkbox" value="mortel" <?php echo $mortel; ?>/><br />
			<input type="submit" value="Modifier" class="bouton" />
		</form>
	</td><td></td>
</tr>

<tr>
	<td></td><td>
		<table>
		<tr>
			<td>
		<form name='utilisateur' action="edition_perso.php" method="post">
			<b>Id ic&ocirc;ne personnelle :</b><br />
			<input name="icone_id" type="text" value='<?php echo $infos['icone_id']; ?>' />
			<input name="id_perso" type="hidden" value='<?php echo $id; ?>' />
			<input type="submit" value="Modifier" class="bouton" />
			<br />
		  <i>Laisser 0 si pas d'ic&ocirc;ne personnelle.</i>
		</form>
			</td>
			<td>
				<img src='./../../images/<?php echo icone_persos($id); ?>' alt='icone_perso'>
			</td>
		</tr>
		</table>
	</td><td></td>
</tr>

<tr>
	<td></td><td>
		<table>
		<tr>
			<td>
		<form name='galon' action="edition_perso.php" method="post">
			<b>Id galon personnel :</b><br />
			<input name="galon_id" type="text" value='<?php echo $infos['galon_id']; ?>' />
			<input name="id_perso" type="hidden" value='<?php echo $id; ?>' />
			<input type="submit" value="Modifier" class="bouton" />
			<br />
		  <i>Laisser 1 si pas de galon.</i>
		</form>
			</td>
			<td>
				<img src='./../../images/<?php echo galon_persos($id); ?>' alt='galon_perso'>
			</td>
		</tr>
		</table>
	</td><td></td>
</tr>

<tr>
	<td></td><td>
		<form name='race' action="edition_perso.php" method="post">
			<b>Race :</b><br />
				<select name="race">
					<?php
					$reponse = mysql_query("SELECT race_id,nom FROM races WHERE grade_id = -2");

					while ($rep_race = mysql_fetch_array($reponse)){
						if ($rep_race['race_id'] == $infos['race_id']){
							echo "<option value='".$rep_race['race_id']."' selected>".$rep_race['nom']."</option>";
						}else{
							echo "<option value='".$rep_race['race_id']."'>".$rep_race['nom']."</option>";
						}
					}
					?>
				</select>
                  <input name="nom_race" type="text" value='<?php echo $infos['nom_race']; ?>' />
		  <input name="id_perso" type="hidden" value='<?php echo $id; ?>' />
		  <input name="grade" type="hidden" value='<?php echo $infos['grade_id']; ?>' />
			<input type="submit" value="Modifier" class="bouton" />
		</form>
	</td><td></td>
</tr>

<tr>
	<td></td><td>
		<form name='grade' action="edition_perso.php" method="post">
			<b>Grade :</b><br />
				<select name="grade">
					<?php
					$reponsegrade = mysql_query("SELECT grade_id,nom FROM races WHERE race_id='$race' AND grade_id!=-2");
			
					while ($rep_grade = mysql_fetch_array($reponsegrade)){
						if ($rep_grade['grade_id'] == $infos['grade_id']){
							echo "<option value='".$rep_grade['grade_id']."' selected>".$rep_grade['nom']."(".$rep_grade['grade_id'].")</option>";
						}else{
							echo "<option value='".$rep_grade['grade_id']."'>".$rep_grade['nom']."(".$rep_grade['grade_id'].")</option>";
						}
					}
					?>
				</select>	
		  <input name="id_perso" type="hidden" value='<?php echo $id; ?>' />
		  <input name="race" type="hidden" value='<?php echo $infos['race_id']; ?>' />
			<input type="submit" value="Modifier" class="bouton" />
		</form>
	</td><td></td>
</tr>

<tr>
	<td></td><td>
		<form name='sup' action="edition_perso.php" method="post">
			<b>Supérieur d'affiliation :</b><br />
				<select name="sup_id">
					<?php
					$reponsesup = mysql_query("SELECT id,nom FROM persos WHERE grade_id=5");
					echo "<option value='0' selected>Aucun (0)</option>";
					while ($rep_sup = mysql_fetch_array($reponsesup)){
						if ($rep_sup['id'] == $infos['superieur_id']){
							echo "<option value='".$rep_sup['id']."' selected>".$rep_sup['nom']." (".$rep_sup['id'].")</option>";
						}else{
							echo "<option value='".$rep_sup['id']."'>".$rep_sup['nom']." (".$rep_sup['id'].")</option>";
						}
					}
					?>
				</select>	
		  <input name="id_perso" type="hidden" value='<?php echo $id; ?>' />
			<input type="submit" value="Modifier" class="bouton" />
		</form>
	</td><td></td>
</tr>

<tr>
	<td></td><td>
		<form name='sup' action="edition_perso.php" method="post">
			<b>Mortalité :</b><br />
				<select name="mortel">
					<?php
					$mortalite  = array('-1' => 'R.I.P', '0' => 'Immortel', '1' => 'Mortel incarné', '2' => 'Mortel pas encore incarné');
					foreach($mortalite as $val => $mortel){
						if ($val == $infos['mortel']){
							echo "<option value='".$val."' selected>".$mortel."</option>";
						}else{
							echo "<option value='".$val."'>".$mortel."</option>";
						}
					}
					?>
				</select>	
		  <input name="id_perso" type="hidden" value='<?php echo $id; ?>' />
			<input type="submit" value="Modifier" class="bouton" />
		</form>
	</td><td></td>
</tr>

<tr>
	<td colspan=3>&nbsp;</td>
</tr>

<tr>
	<td></td><td>
		<form name='date' action="edition_perso.php" method="post">
			<b>Date du tour :</b><br />
		  <input name="date" type="text" value='<?php echo $infos['date_tour']; ?>' />
		  <input name="id_perso" type="hidden" value='<?php echo $id; ?>' />
			<input type="submit" value="Modifier" class="bouton" />
		</form>
	</td><td></td>
</tr>

<tr>
	<td></td><td>
		<form name='option' action="edition_perso.php" method="post">
			<b>Options personnages :</b><br />
		  <input name="options" type="text" value='<?php echo $infos['options']; ?>' />
		  <input name="id_perso" type="hidden" value='<?php echo $id; ?>' />
			<input type="submit" value="Modifier" class="bouton" />
		</form>
	</td><td></td>
</tr>

<tr>
	<td colspan=3><hr/></td>
</tr>

<tr>
	<td></td><td>
		<form name='option' action="edition_perso.php" method="post">
			<b>Position du personnage :</b><br />
			<table>
			<tr>
		  	<td>
		  		Pos X : <input name="pos_x" type="text" value='<?php echo $position['pos_x']; ?>' />
		  	</td>
		  </tr>
		  <tr>
		  	<td>	
		  		Pos Y : <input name="pos_y" type="text" value='<?php echo $position['pos_y']; ?>' />
		  	</td>
		  </tr>
		  <tr>
		  	<td>
		  		<hr />
		  	</td>
		  </tr>
		  <tr>
		  	<td>
					<select name="carte">
						<?php
						$carte = mysql_query("SELECT id,nom FROM cartes");

						while ($plan = mysql_fetch_array($carte)){
							if ($plan['id'] == $position['carte_id']){
								echo "<option value='".$plan['id']."' selected>".$plan['nom']."</option>";
							}else{
								echo "<option value='".$plan['id']."'>".$plan['nom']."</option>";
							}
						}
						?>
					</select>
				</td>
			</tr>
		  <tr>
		  	<td>
		  		<input name="id_perso" type="hidden" value='<?php echo $id; ?>' />
					<input type="submit" value="Modifier" class="bouton" />
					<input type="submit" name="desincarne" value="Désincarner" class="bouton" />
				</td>
			</tr>
			</table>
		</form>
	</td>
					<td></td>
</tr>

<tr>
	<td colspan=3><hr/></td>
</tr>

<tr>
	<td colspan=3>
		<form name='caracs' action="edition_perso.php" method="post">
		  <input name="id_perso" type="hidden" value='<?php echo $id; ?>' />
			<table>
				<tr>
					<th align=center>Carac : </th>
					<th align=center>Valeur brute</th>
					<th align=center>Correspondance</th>
					<th align=center>Magie</th>
					<th align=center>Bene et malé</th>
					<th align=center>Plan</th>
					<th align=center>Valeur globale</th>
				</tr>
				<tr>
					<td>Px : </td>
					<td><input size="15" name="px" type="text" value='<?php echo $carac_brute['px']; ?>' /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>Pi : </td>
					<td><input size="15" name="pi" type="text" value='<?php echo $carac_brute['pi']; ?>' /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><hr/></td>
				</tr>
				<tr>
					<td>Pv : </td>
					<td><input size="15" name="pv" type="text" value='<?php echo $carac_brute['pv']; ?>' /></td>
					<td></td>
					<td><input size="15" name="pv_mag" type="text" value='<?php echo $carac_alter_mag['alter_pv']; ?>' readonly /></td>
					<td><input size="15" name="pv_alter" type="text" value='<?php echo $carac_alter['alter_pv']; ?>' /></td>
					<td><input size="15" name="pv_plan" type="text" value='<?php echo $carac_alter_plan['alter_pv']; ?>' /></td>
					<td><input size="15" type="text" value='<?php echo $caracs['pv']; ?>' readonly /></td>
				</tr>
				<tr>
					<td>Niveau Pv : </td>
					<td><input size="15" name="niv_pv" type="text" value='<?php echo $carac_brute['niv_pv']; ?>' /></td>
					<td><?php echo carac_max_no_galon($race, $grade, 'pv', $carac_brute['niv_pv']); ?> Pv max</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>R&eacute;cup Pv : </td>
					<td><input size="15" name="recup_pv" type="text" value='<?php echo $carac_brute['recup_pv']; ?>' /></td>
					<td></td>
					<td><input size="15" name="recup_pv_mag" type="text" value='<?php echo $carac_alter_mag['alter_recup_pv']; ?>' readonly /></td>
					<td><input size="15" name="recup_pv_alter" type="text" value='<?php echo $carac_alter['alter_recup_pv']; ?>' /></td>
					<td><input size="15" name="recup_pv_plan" type="text" value='<?php echo $carac_alter_plan['alter_recup_pv']; ?>' /></td>
					<td><input size="15" type="text" value='<?php echo $caracs['recup_pv']; ?>' readonly /></td>
				</tr>
				<tr>
					<td>Niveau R&eacute;cup Pv : </td>
					<td><input size="15" name="niv_recup_pv" type="text" value='<?php echo $carac_brute['niv_recup_pv']; ?>' /></td>
					<td><?php echo carac_max_no_galon($race, $grade, 'recup_pv', $carac_brute['niv_recup_pv']); ?>% de r&eacute;cup PV</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><hr/></td>
				</tr>
				<tr>
					<td>Malus Def : </td>
					<td><input size="15" name="malus_def" type="text" value='<?php echo $carac_brute['malus_def']; ?>' /></td>
					<td></td>
					<td><input size="15" name="def_mag" type="text" value='<?php echo $carac_alter_mag['alter_def']; ?>' readonly /></td>
					<td><input size="15" name="def_alter" type="text" value='<?php echo $carac_alter['alter_def']; ?>' /></td>
					<td><input size="15" name="def_plan" type="text" value='<?php echo $carac_alter_plan['alter_def']; ?>' /></td>
					<td><input size="15" type="text" value='<?php echo $caracs['malus_def']-$caracs['def']; ?>' readonly /></td>
				</tr>
				<tr>
					<td>Alter attaque : </td>
					<td><input size="15" type="text" value='0' readonly /></td>
					<td></td>
					<td><input size="15" name="att_mag" type="text" value='<?php echo $carac_alter_mag['alter_att']; ?>' readonly /></td>
					<td><input size="15" name="att_alter" type="text" value='<?php echo $carac_alter['alter_att']; ?>' /></td>
					<td><input size="15" name="att_plan" type="text" value='<?php echo $carac_alter_plan['alter_att']; ?>' /></td>
					<td><input size="15" type="text" value='<?php echo $caracs['att']; ?>' readonly /></td>
				</tr>
				<tr>
					<td><hr/></td>
				</tr>
				<tr>
					<td>Niveau de magie : </td>
					<td><input size="15" name="niv" type="text" value='<?php echo $carac_brute['niv']; ?>' /></td>
					<td></td>
					<td><input size="15" name="niv_mag_mag" type="text" value='<?php echo $carac_alter_mag['alter_niv_mag']; ?>' readonly /></td>
					<td><input size="15" name="niv_mag_alter" type="text" value='<?php echo $carac_alter['alter_niv_mag']; ?>' /></td>
					<td><input size="15" name="niv_mag_plan" type="text" value='<?php echo $carac_alter_plan['alter_niv_mag']; ?>' /></td>
					<td><input size="15" type="text" value='<?php echo $caracs['niv']; ?>' readonly /></td>
				</tr>
				<tr>
					<td>Cercle de magie : </td>
					<?php
				switch($carac_brute['cercle']){
					case 0 : 
						$cercle="Aucun";
						break;
					case 1 : 
						$cercle="Cercle de feu";
						break;
					case 2 : 
						$cercle="Cercle de glace";
						break;
					case 3 : 
						$cercle="Cercle de l'espace-temps";
						break;
					case 4 : 
						$cercle="Cercle du soin";
						break;
					case 5 : 
						$cercle="Cercle de la peur";
						break;
					case 6 : 
						$cercle="Cercle des Parias";
						break;				
					case 7 : 
						$cercle="Cercle Technologique";
						break;				
					}
					?>
					<td><input size="15" type="text" value='<?php echo $cercle; ?>' readonly /></td>
					<td></td>
					<td><select name="cercle">
						<option value="0" <?php echo ($carac_brute['cercle']==0)?"selected='selected'":""; ?>>Aucun</option>
						<option value="1" <?php echo ($carac_brute['cercle']==1)?"selected='selected'":""; ?>>Cercle de feu</option>
						<option value="2" <?php echo ($carac_brute['cercle']==2)?"selected='selected'":""; ?>>Cercle de glace</option>
						<option value="3" <?php echo ($carac_brute['cercle']==3)?"selected='selected'":""; ?>>Cercle de l'espace-temps</option>
						<option value="4" <?php echo ($carac_brute['cercle']==4)?"selected='selected'":""; ?>>Cercle du soin</option>
						<option value="5" <?php echo ($carac_brute['cercle']==5)?"selected='selected'":""; ?>>Cercle de la peur</option>
						<option value="6" <?php echo ($carac_brute['cercle']==6)?"selected='selected'":""; ?>>Cercle des Parias</option>
						<option value="7" <?php echo ($carac_brute['cercle']==7)?"selected='selected'":""; ?>>Cercle technologique</option>
					</select></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>Res magique : </td>
					<td><input size="15" name="res_mag" type="text" value='<?php echo $carac_brute['res_mag']; ?>' /></td>
					<td></td>
					<td><input size="15" name="res_mag_mag" type="text" value='<?php echo $carac_alter_mag['alter_res_mag']; ?>' readonly /></td>
					<td><input size="15" name="res_mag_alter" type="text" value='<?php echo $carac_alter['alter_res_mag']; ?>' /></td>
					<td><input size="15" name="res_mag_plan" type="text" value='<?php echo $carac_alter_plan['alter_res_mag']; ?>' /></td>
					<td><input size="15" type="text" value='<?php echo $caracs['res_mag']; ?>' readonly /></td>
				</tr>
				<tr>
					<td><hr/></td>
				</tr>
				<tr>
					<td>Mouv : </td>
					<td><input size="15" name="mouv" type="text" value='<?php echo $carac_brute['mouv']; ?>' /></td>
					<td></td>
					<td><input size="15" name="mouv_mag" type="text" value='<?php echo $carac_alter_mag['alter_mouv']; ?>' readonly /></td>
					<td><input size="15" name="mouv_alter" type="text" value='<?php echo $carac_alter['alter_mouv']; ?>' /></td>
					<td><input size="15" name="mouv_plan" type="text" value='<?php echo $carac_alter_plan['alter_mouv']; ?>' /></td>
					<td><input size="15" type="text" value='<?php echo $caracs['mouv']; ?>' readonly /></td>
				</tr>		
				<tr>
					<td>Niveau Mouv : </td>
					<td><input size="15" name="niv_mouv" type="text" value='<?php echo $carac_brute['niv_mouv']; ?>' /></td>
					<td><?php echo carac_max_no_galon($race, $grade, 'mouv', $carac_brute['niv_mouv']); ?> Mouv max</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>	
				<tr>
					<td><hr/></td>
				</tr>
				<tr>
					<td>Pa : </td>
					<td><input size="15" name="pa" type="text" value='<?php echo $carac_brute['pa']; ?>' /></td>
					<td></td>
					<td><input size="15" name="pa_mag" type="text" value='<?php echo $carac_alter_mag['alter_pa']; ?>' readonly /></td>
					<td><input size="15" name="pa_alter" type="text" value='<?php echo $carac_alter['alter_pa']; ?>' /></td>
					<td><input size="15" name="pa_plan" type="text" value='<?php echo $carac_alter_plan['alter_pa']; ?>' /></td>
					<td><input size="15" type="text" value='<?php echo $caracs['pa']; ?>' readonly /></td>
				</tr>	
				<tr>
					<td>Niveau Pa : </td>
					<td><input size="15" name="niv_pa" type="text" value='<?php echo $carac_brute['niv_pa']; ?>' /></td>
					<td><?php echo carac_max_no_galon($race, $grade, 'pa', $carac_brute['niv_pa']); ?> PA max</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>	
				<tr>
					<td><hr/></td>
				</tr>
				<tr>
					<td>D&egrave;s Attaque : </td>
					<td><input size="15" name="des_attaque" type="text" value='<?php echo $carac_brute['des_attaque']; ?>' /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>	
				<tr>
					<td>Niveau D&egrave;s : </td>
					<td><input size="15" name="niv_des" type="text" value='<?php echo $carac_brute['niv_des']; ?>' /></td>
					<td><?php echo carac_max_no_galon($race, $grade, 'des', $carac_brute['niv_des']); ?> d&eacute;s max</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>	
				<tr>
					<td><hr/></td>
				</tr>
				<tr>
					<td>Force : </td>
					<td><input size="15" name="force" type="text" value='<?php echo $carac_brute['force']; ?>' /></td>
					<td></td>
					<td><input size="15" name="force_mag" type="text" value='<?php echo $carac_alter_mag['alter_force']; ?>' readonly />%</td>
					<td><input size="15" name="force_alter" type="text" value='<?php echo $carac_alter['alter_force']; ?>' />%</td>
					<td><input size="15" name="force_plan" type="text" value='<?php echo $carac_alter_plan['alter_force']; ?>' />%</td>
					<td><input size="15" type="text" value='<?php echo $caracs['force']; ?>' readonly /></td>
				</tr>	
				<tr>
					<td>Niveau Force : </td>
					<td><input size="15" name="niv_force" type="text" value='<?php echo $carac_brute['niv_force']; ?>' /></td>
					<td><?php echo carac_max_no_galon($race, $grade, 'force', $carac_brute['niv_force']); ?> force max</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>Perception : </td>
					<td><input size="15" name="perception" type="text" value='<?php echo $carac_brute['perception']; ?>' /></td>
					<td></td>
					<td><input size="15" name="perception_mag" type="text" value='<?php echo $carac_alter_mag['alter_perception']; ?>' readonly /></td>
					<td><input size="15" name="perception_alter" type="text" value='<?php echo $carac_alter['alter_perception']; ?>' /></td>
					<td><input size="15" name="perception_plan" type="text" value='<?php echo $carac_alter_plan['alter_perception']; ?>' /></td>
					<td><input size="15" type="text" value='<?php echo $caracs['perception']; ?>' readonly /></td>
				</tr>
				<tr>
					<td>Niveau Perception : </td>
					<td><input size="15" name="niv_perception" type="text" value='<?php echo $carac_brute['niv_perception']; ?>' /></td>
					<td><?php echo carac_max_no_galon($race, $grade, 'perception', $carac_brute['niv_perception']); ?> de perception max</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td><input type="submit" value="Modifier" class="bouton" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</table>
			</form>
	</td>
</tr>

<tr>
	<td colspan=3><hr/></td>
</tr>
<tr>
	<td colspan=3>
		<form name='caracs' action="edition_effets_mag.php" method="post">
		  <input name="id_perso" type="hidden" value='<?php echo $id; ?>' />
			<table>
				<tr>
                                        <th align=center>Suppression</th>	
                                        <th align=center>PA</th>
					<th align=center>PV</th>
					<th align=center>Mouv</th>
					<th align=center>Déf.</th>
					<th align=center>Att.</th>
					<th align=center>Récup. PV</th>
                                        <th align=center>Force</th>
                                        <th align=center>Percep.</th>
                                        <th align=center>Niv. Magie</th>
                                        <th align=center>Rés Mag</th>
                                        <th align=center>Esq Mag</th>
                                        <th align=center>Rés Phy</th>
                                        <th align=center>Nb. de tour</th>
                                        <th align=center>Cassable?</th>
                                        <th align=center>Dissipé à la mort?</th>
				</tr>
                                <?php
                                
                                    for($i=0; $ligne = mysql_fetch_array ($carac_alter_mag_detail); $i++) {
                                        $cass = ($ligne['cassable'] == 1) ? 'checked' : '';
                                        $mort = ($ligne['dissipe_mort'] == 1) ? 'checked' : '';
                                       echo '                                <tr>
                                    <td><input name="uid['.$i.']" type="hidden" value="'.$ligne['unique_id'].'">
                                        <input name="suppression['.$i.']" type="checkbox" value="suppression"/></td>
                                    <td><input size="5" name="pa['.$i.']" type="text" value="'.$ligne['alter_pa'].'" /></td>
                                    <td><input size="5" name="pv['.$i.']" type="text" value="'.$ligne['alter_pv'].'" /></td>
                                    <td><input size="5" name="mouv['.$i.']" type="text" value="'.$ligne['alter_mouv'].'" /></td>
                                    <td><input size="5" name="def['.$i.']" type="text" value="'.$ligne['alter_def'].'" /></td>
                                    <td><input size="5" name="att['.$i.']" type="text" value="'.$ligne['alter_att'].'"  /></td>
                                    <td><input size="5" name="recup['.$i.']" type="text" value="'.$ligne['alter_recup_pv'].'" /></td>
                                    <td><input size="5" name="force['.$i.']" type="text" value="'.$ligne['alter_force'].'" /></td>
                                    <td><input size="5" name="percept['.$i.']" type="text" value="'.$ligne['alter_perception'].'" /></td>
                                    <td><input size="5" name="nivmag['.$i.']" type="text" value="'.$ligne['alter_niv_mag'].'" /></td>
                                    <td><input size="5" name="resmag['.$i.']" type="text" value="'.$ligne['alter_res_mag'].'" /></td>
                                    <td><input size="5" name="esqmag['.$i.']" type="text" value="'.$ligne['alter_esq_mag'].'" /></td>
                                    <td><input size="5" name="resphy['.$i.']" type="text" value="'.$ligne['alter_res_phy'].'" /></td>
                                    <td><input size="5" name="nbtour['.$i.']" type="text" value="'.$ligne['nb_tour'].'" /></td>
                                    <td><input name="cassable['.$i.']" type="checkbox" value="cassable" '.$cass.'/></td>
                                    <td><input name="dissipe_mort['.$i.']" type="checkbox" value="dissipe_mort" '.$mort.'/></td>
                                </tr>' ;
                                    }
                                ?>
                                <tr>
                                    <td><input name="id_perso" type="hidden" value='<?php echo $id; ?>' /></td>
                                    <td><input size="5" name="pa_new" type="text" value='' /></td>
                                    <td><input size="5" name="pv_new" type="text" value='' /></td>
                                    <td><input size="5" name="mouv_new" type="text" value='' /></td>
                                    <td><input size="5" name="def_new" type="text" value='' /></td>
                                    <td><input size="5" name="att_new" type="text" value=''  /></td>
                                    <td><input size="5" name="recup_new" type="text" value='' /></td>
                                    <td><input size="5" name="force_new" type="text" value='' /></td>
                                    <td><input size="5" name="percept_new" type="text" value='' /></td>
                                    <td><input size="5" name="nivmag_new" type="text" value='' /></td>
                                    <td><input size="5" name="resmag_new" type="text" value='' /></td>
                                    <td><input size="5" name="esqmag_new" type="text" value='' /></td>
                                    <td><input size="5" name="resphy_new" type="text" value='' /></td>
                                    <td><input size="5" name="nbtour_new" type="text" value='' /></td>
                                    <td><input name="cassable_new" type="checkbox" value="cassable" /></td>
                                    <td><input name="dissipemort_new" type="checkbox" value="dissipe_mort" /></td>
                                </tr>
                                <tr>
					<td></td>
					<td></td>
					<td><input type="submit" value="Modifier" class="bouton" /></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>  
                                </tr>
                        </table>
        </td>
</tr>
<tr>
	<td colspan=3><hr/></td>
</tr>

<tr>
	<td colspan=3>
		<b>Avatar :</b><br />
		<?php
			if($infos['avatar_url'] == ''){
			echo "<p><img src='./../../images/avatar/no_avatar.png' alt='gravatar'></p>";	
			}else{
			echo "<p><img src='./../../images/avatar/".$infos['avatar_url']."' alt='avatar'></p>";

			}
		?>

    <form enctype="multipart/form-data" action="upload_image.php" method="POST">
    <input name="id_perso" type="hidden" value='<?php echo $id; ?>' />
    <input name="fichier" type="file" />
    <input type="submit" value="Uploader" class="bouton"/>
		</form> 
		<i>Image : png, jpg, gif; taille maxi 140*140</i>
	</td>
</tr>
<tr>
	<td colspan=3><hr/></td>
</tr>
<tr>
	<td colspan=3>
		<b>Message du jour :</b><br />
		<form name='mdj' action="edition_perso.php" method="post">
			<TEXTAREA cols="30" rows="2" name="mdj"><?php echo $infos['mdj']; ?></TEXTAREA>
		  <input name="id_perso" type="hidden" value='<?php echo $id; ?>' />
			<input type="submit" value="Modifier" class="bouton" />
		</form>
	</td>
</tr>
<tr>
	<td colspan=3>&nbsp;</td>
</tr>
<tr>
	<td colspan=3>
		<b>Signature forum :</b><br />
		<form name='signature' action="edition_perso.php" method="post">
			<TEXTAREA cols="40" rows="3" name="signature" class="wysiwyg"><?php echo $infos['signature']; ?></TEXTAREA>
		  <input name="id_perso" type="hidden" value='<?php echo $id; ?>' />
			<input type="submit" value="Modifier" class="bouton" />
		</form>
	</td>
</tr>
<tr>
	<td colspan=3>&nbsp;</td>
</tr>
<tr>
	<td colspan=3>
		<b>Background :</b><br />
		<form name='background' action="edition_perso.php" method="post">
			<TEXTAREA cols="50" rows="5" name="background" class="wysiwyg"><?php echo $infos['background']; ?></TEXTAREA>
	    <input name="id_perso" type="hidden" value='<?php echo $id; ?>' />
			<input type="submit" value="Modifier" class="bouton" />
		</form>
	</td>
</tr>

</table>
</div>
<?php
include("./inventaire.php");
}else{
	echo "Vous n'êtes pas autorisés à effectuer cette action.";
}
//-- Footer --
		include(SERVER_ROOT."/template/footer_new.php");
//------------
?>
