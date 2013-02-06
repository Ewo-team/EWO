<?php
//-- Header --
require_once __DIR__ . '/../../conf/master.php';

include(SERVER_ROOT."/template/header_new.php");
/*-- Connexion basic requise --*/
ControleAcces('admin',1);
/*-----------------------------*/

if (isset($_GET['id'])){
$id = mysql_real_escape_string($_GET['id']);
$ok = 1;
}

if ($ok == 1){

//-- Info personnages
$info = "SELECT*FROM utilisateurs WHERE id = '$id'";																							
$resultat = mysql_query ($info) or die (mysql_error());
$infos = mysql_fetch_array ($resultat);

$droits = $infos['droits'];

$info1 = "SELECT*FROM utilisateurs_ban WHERE utilisateur_id = '$id'";																			
$resultat1 = mysql_query ($info1) or die (mysql_error());
$infos1 = mysql_fetch_array ($resultat1);

$_SESSION['temps']['page'] = SERVER_URL . "/admin/utilisateurs/editer_utilisateur.php?id=$id";

if(!empty($infos1['date'])){
$debut = $infos1['date'];
$fin = $infos1['date_fin'];

$ban_sec = $fin - $debut;
$ban_jours = $ban_sec / 60 / 60 / 24;

$date = date("Y-m-j H:i",$infos1['date_fin']);
}else{
$date = '';
$ban_jours = '';
}
?>

<div align='center' id='contact'>
<h2>Edition de <i><?php echo $infos['nom']; ?></i></h2>
<p>[ <a href='liste_utilisateurs.php'>Retour</a> ]</p>
<table>

<tr>
	<td><hr/></td>
</tr>

<tr>
	<td>
		<form name='utilisateur' action="edition_utilisateur.php" method="post">
		  <input name="id_utilisateur" type="hidden" value='<?php echo $id; ?>' />
			<table>
				<tr>
					<td>Nom : </td>
					<td><input name="nom" type="text" value='<?php echo $infos['nom']; ?>' /></td>
				</tr>
				<tr>
					<td>Mail : </td>
					<td><input name="mail" type="text" value='<?php echo $infos['email']; ?>' /></td>
				</tr>
				<tr>
					<td><hr/></td>
				</tr>
				<tr>
					<td>Droits : </td>
					<td>
						<input type="checkbox" name="droit1" value="1" <?php if($droits[0]==1){echo 'checked="checked"';}?>> Compte valide<br/>
						<input type="checkbox" name="droit2" value="1" <?php if($droits[1]==1){echo 'checked="checked"';}?>> Administration<br/>
						<input type="checkbox" name="droit3" value="1" <?php if($droits[2]==1){echo 'checked="checked"';}?>> Animation<br/>
						<input type="checkbox" name="droit4" value="1" <?php if($droits[3]==1){echo 'checked="checked"';}?>> Anti-triche<br/>						
				</tr>
				<tr>
					<td><hr/></td>
				</tr>
				<tr>
					<td>Options : </td>
					<td><input name="options" type="text" value='<?php echo $infos['options']; ?>' /></td>
				</tr>
				<tr>
					<td><hr/></td>
				</tr>
				<tr>
					<td>Liste personnage : </td>
					<td>
						<ul>
								<?php
									$persos = "SELECT*FROM persos WHERE utilisateur_id = '$id'";							
									$resultat = mysql_query ($persos) or die (mysql_error());
									while ($perso = mysql_fetch_array ($resultat)){
										echo "<li><a href='".SERVER_URL."/admin/persos/editer_perso.php?id=".$perso['id']."'>".$perso['nom']."</a></li>";
									}
								?>
						</ul>
					</td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" value="Modifier" class="bouton" /></td>
					</form>
				</tr>
				<tr>
					<td colspan='2'><hr/></td>
				</tr>
				<tr>
					<td>
						Bannissement : 
					</td>
					<td>
						<form name='bannissement' action="edition_bannissement.php" method="post">
						<input type="checkbox" name="ban_check" value="1" <?php if(!empty($infos1['date'])){echo 'checked="checked"';}?>> Bannir cet utilisateur<br/>
						<input name="ban_existe" type="hidden" value='<?php if(!empty($infos1['date'])){echo 'existe';}?>' />
						Début du ban : <?php echo date('Y-m-d à H:i'); ?><br/>
						Fin du ban  : <input name="ban_fin" type="text" value='<?php echo $date; ?>'/> * AAAA-MM-JJ H:M<br/>
						Motif : <input name="ban_motif" type="text" size="35"value='<?php echo $infos1['motif']; ?>' /><br/>
						Nombres de jours bannie : <?php echo $ban_jours; ?>
						<input name="id_utilisateur" type="hidden" value='<?php echo $id; ?>' />
					</td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" value="Modifier" class="bouton" /></form></td>
				</tr>
				<tr>
					<td colspan='2'><hr/></td>
				</tr>	
				<tr>
					<td>
						Suppression irreversible de cet utilisateur et de ses personnages : 
					</td>
					<td>
						<form name='del_utilisateur' action="del_utilisateur.php" method="post">
						<input type="checkbox" name="supprimer" value="supp" />
						<input name="id_utilisateur" type="hidden" value='<?php echo $id; ?>' />
						<input type="submit" value="Suppression" class="bouton" />
						</form>
					</td>
			</table>
	</td>
</tr>

</table>
</div>
<?php
}else{
	echo "Vous n'êtes pas autorisé à effectuer cette action.";
}

//-- Footer --
include(SERVER_ROOT."/template/footer_new.php");
//------------
?>
