<?php
/**
 * Légion - Liste des personnages et de leurs Légions
 *
 * @author Anarion <anarion@ewo.fr>
 * @version 1.0
 * @package Légion
 */

//-- Header --
$root_url = '..';
include($root_url.'/template/header_new.php');

/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/
$nb_persos = $_SESSION['persos']['inc'];
?>
<h2>Liste des Légions accessibles : </h2>
<?php
include("../faction/fonctions.php");

if ($nb_persos!=0){
//Test sur une demande d'entrée dans une Légion
if (isset($_POST['ask_fact']))
{
	if (isset($_POST['faction_id'])){
		$perso_id = mysql_real_escape_string($_POST['perso_id']);
		$faction_id= mysql_real_escape_string($_POST['faction_id']);

		$requete = "SELECT utilisateur_id, faction_id FROM persos WHERE id='$perso_id'";
		$reponse = mysql_query ($requete) or die (mysql_error());
		$reponse= mysql_fetch_array($reponse);
		$user_id = $reponse['utilisateur_id'];

		$requete = "SELECT faction_id FROM wait_faction WHERE perso_id='$perso_id' AND faction_id='$faction_id'";
		$reponse_wait = mysql_query ($requete) or die (mysql_error());
		$reponse_wait= mysql_fetch_array($reponse_wait);

		// S'il n'appartient à aucune Légion (donc que l'id vaut 0) et que cette Légion n'est pas déjà dans la liste d'attente
		if(isset($reponse['faction_id']) and $reponse['faction_id']==0 and $reponse_wait['faction_id']!=$faction_id){
		$sql_faction = mysql_query("INSERT INTO `ewo`.`wait_faction` (`id` ,
																	`utilisateur_id` ,
																	`perso_id` ,
																	`faction_id` ,
																	`demandeur`
																		)
																	VALUES (
																			NULL , '$user_id', '$perso_id', '$faction_id', '1'
																			);") or die (mysql_error());
		 }
	}
}


// Fonction pour quitter la Légion d'un personnage
function quit_faction (){
if (isset($_POST['quit_faction']))
	{
	$faction_id  	= mysql_real_escape_string($_POST['faction_id']);
	$faction_nom	= mysql_real_escape_string($_POST['faction_nom']);
	$perso_id		= mysql_real_escape_string($_POST['perso_id']);	
	?>
	<div align='center'><br /><b>Vous souhaitez quitter la Légion <?php echo $faction_nom?></b><br />
	<form method="post"> 
			<div align='center'>	
			<input type="hidden" name="perso_id" value="<?php echo $perso_id ; ?>" />
			<input type="hidden" name="faction_id" value="<?php echo $faction_id ; ?>" /><br/>
			<input type="submit" name="Confirm_quit" value="Confirmer" /> </div>
	</form>
	<p>[<a href='liste_persos.php'>Retour</a>]</p></td>
	</div>

<?php	
	}
// Dans le cas d'une confirmation d'affiliation
	elseif (isset($_POST['Confirm_quit']))
	{
	// On récupère les infos importantes
	$perso_id	= mysql_real_escape_string($_POST['perso_id']);
	$faction_id	= mysql_real_escape_string($_POST['faction_id']);
	
	if(faction_type($faction_id)!=3){
		$inc=1;
		while($inc<=$_SESSION['persos']['inc'] && $perso_id!=$_SESSION['persos']['id'][$inc])
			{
			$inc++;
			}
		
		$retour 	= del_membre($faction_id,$perso_id);
		
		// On procède au départ du membre
		if ($retour)
			{
			$_SESSION['persos']['faction']['id'][$inc]	= 0;
			$_SESSION['persos']['faction']['grade'][$inc] 	= NULL;
			$_SESSION['persos']['faction']['droits'][$inc] 	= NULL;
			quit_event($perso_id);
			echo "<script language='javascript' type='text/javascript' >document.location='liste_persos.php'</script>";exit;
			}
			else 
				{
				// Si on arrive ici il y a eu une erreur dans la requête sql pour quitter la Légion
				echo "<br/>Erreur lors de votre ejection de la Légion. Veuillez contacter un administrateur.
					  <p>[<a href='liste_persos.php'>Retour</a>]</p></td>";
				}
		}else 
				{
				// Si on arrive ici il y a eu une erreur dans la requête sql pour quitter la Légion
				echo "<br/>Vous n'&ecirc;tes pas autoris&eacute; &agrave; quitter les Légions de tra&icirc;tres de votre propre chef.
					  <p>[<a href='liste_persos.php'>Retour</a>]</p></td>";
				}
	}
}

$utilisateur_id = $_SESSION['utilisateur']['id'];

// Recherche de la race de l'utilisateur
$sql_id 	= "SELECT persos.race_id AS race_id, races.nom AS nom
					FROM persos
						INNER JOIN races
							ON races.race_id = persos.race_id AND races.grade_id = 0
								WHERE persos.utilisateur_id = $utilisateur_id";
$res_id		= mysql_query ($sql_id) or die (mysql_error());
$perso_ 	= mysql_fetch_array ($res_id);
$perso_race_id	= $perso_['race_id'];
$perso_race	= $perso_['nom'];


if (!isset($_GET['alpha'])){
	$alpha = 'A';
}else{
	$alpha = mysql_real_escape_string($_GET['alpha']);
}

?>
<div align='center' id='contact'>
<?php
// Liste alpha des lettres
for ($i='A';$i!='AA';$i++){
	$count = "SELECT COUNT(nom) AS nombre FROM factions WHERE race REGEXP '$perso_race_id|2' AND nom REGEXP '^".$i."'";
	$resultat = mysql_query ($count) or die (mysql_error());
	$counter = mysql_fetch_array ($resultat);
	
	if($counter['nombre'])
		echo "<a href='?alpha=$i'>$i (".$counter['nombre'].")</a> | ";
}

$faction = "SELECT * FROM factions WHERE race REGEXP '$perso_race_id|2' AND nom LIKE '".$alpha."%' ORDER BY nom ASC";							

?>
<hr/>
<ul>
<?php
	$resultat = mysql_query ($faction) or die (mysql_error());
	while ($faction = mysql_fetch_array ($resultat)){

		echo "<li>Id : ".$faction['id']." Nom : <a href='editer_faction.php?id=".$faction['id']."'>".$faction['nom']."</a></li>"; 

	}
?>

</ul>
<hr/>
<p>Entrez directement le nom de la Légion &agrave; voir :</p> 
<form name='option' action="editer_faction.php" method="post">
	<b>Nom de la Légion :</b><br />
  <input name="nom_faction" type="text" value='' />
  <input type="submit" value="Editer" class="bouton" />
</form>
<hr/>
<h2>Liste de vos personnages et de leurs Légions : </h2>
<?php
$sql = "SELECT 	persos.id AS id_perso, 
				persos.nom AS nom_perso, 
				races.color AS couleur, 
				persos.superieur_id AS superieur_id, 
				persos.faction_id AS faction_id,
				caracs.px AS px,
				races.race_id AS race,
				races.grade_id AS grade
			FROM persos 
				INNER JOIN races 
					ON persos.race_id = races.race_id AND persos.grade_id = races.grade_id
				INNER JOIN caracs 
					ON caracs.perso_id = persos.id
						WHERE utilisateur_id = $utilisateur_id";
							
$resultat = mysql_query ($sql) or die (mysql_error());
while ($perso = mysql_fetch_array ($resultat)){

$id 			= $perso['id_perso'];					
$nom        	= $perso['nom_perso'];
$couleur   		= $perso['couleur'];
$affil			= $perso['superieur_id'];
$xp				= $perso['px'];
$race			= $perso['race'];
$faction_id		= $perso['faction_id'];

//Acceptation ou non des demande d'entrée dans la Légion

if (isset($_POST['demande_factions']))
{

$perso_id = mysql_real_escape_string($_POST['perso_id']);
$sql_demande 	= "SELECT faction_id FROM wait_faction WHERE perso_id = $perso_id AND demandeur=0";
$res_demande	= mysql_query (mysql_real_escape_string($sql_demande)) or die (mysql_error());
while($demande = mysql_fetch_array ($res_demande))
{
$sql_fact = "SELECT  persos.faction_id AS faction_id
			FROM persos 
				WHERE id = '$perso_id'";
$resultat_fact = mysql_query ($sql_fact) or die (mysql_error());
$resultat_fact_id = mysql_fetch_array ($resultat_fact);

if($resultat_fact_id['faction_id']==0){
	if (isset($demande['faction_id']))
	{
	$demandeur_id = $demande['faction_id'];
	$oui = 'oui'.$demandeur_id;
	if (isset($_POST[$oui]) && $_POST[$oui]==1)
		{
			accepte($demandeur_id, $perso_id);
			$faction_id=$demandeur_id;
			$sql_demande 	= "SELECT faction_id FROM wait_faction WHERE perso_id = $perso_id";
			$res_demande_	= mysql_query (mysql_real_escape_string($sql_demande)) or die (mysql_error());
			while($demande_ = mysql_fetch_array ($res_demande_))
			{
			if (isset($demande_['faction_id']))
				{
				$demandeur_id = $demande_['faction_id'];
				refuse($demandeur_id, $perso_id);
				}
			}
		}
	elseif (isset($_POST[$oui]) && $_POST[$oui]==0)
		{
			refuse($demandeur_id, $perso_id);
		}
	}
	}
}
echo "<script language='javascript' type='text/javascript' >document.location='./liste_persos.php'</script>";exit;
}

$droits  = array(0,0,0,0,0,0,0,0);

//Nom de la Légion dans laquelle il est
if ($faction_id != 0){
	$sql2 = "SELECT factions.nom AS nom_fact
						FROM factions 
							WHERE id = '$faction_id'";
							
	$res = mysql_query ($sql2) or die (mysql_error());
	$nom_fact = mysql_fetch_array ($res);

	$nom_faction = $nom_fact['nom_fact'];
	
	$sql="SELECT faction_membres.faction_grade_id AS faction_grade, faction_grades.droits AS droits
					FROM faction_membres
						INNER JOIN faction_grades ON faction_grades.faction_id = ".$faction_id." AND faction_grades.grade_id = faction_membres.faction_grade_id
						WHERE faction_membres.perso_id = ".$id;
	$res_fac = mysql_query ($sql) or die (mysql_error());
	$fac_droits = mysql_fetch_array ($res_fac);
	$droits=$fac_droits['droits'];
	
	$nbdem_fac = 0;
	if($droits[4] || $droits[0]){
		$nbdems = "SELECT COUNT(wait_faction.id) AS nombre
						FROM wait_faction
						INNER JOIN persos ON persos.id=$id
							WHERE wait_faction.faction_id = persos.faction_id AND demandeur = '1'";									
														
		$resultat1 = mysql_query ($nbdems) or die (mysql_error());
		$nbdem_ = mysql_fetch_array ($resultat1);
		$nbdem_fac += $nbdem_['nombre'];
		}
}
//----


$url = icone_persos($id);

?>
<ul>
<li>
	<table class='tab_list_perso'>
		<tr>
			<td class='tab_td_icone'><img src='<?php echo $root_url; ?>/images/<?php echo $url; ?>' alt='avatar' title='Avatar de <?php echo $nom; ?>' /></td>
			<td class='tab_td'><a href='<?php echo $root_url; ?>/messagerie/index.php?id=<?php echo $id; ?>'><?php echo $nom; ?></a> (<?php echo $id; ?>)</td>
		</tr>
		<tr>
			<td colspan='2'>
			<table class='tab_list_perso_carac'>
			<?php
			if ($faction_id != 0){
				echo "<tr class='tab_tr_ligne_titre'>
						<td colspan='2'><img class='tab_puce' src='$root_url/images/transparent.png' alt='puce' />
							Votre personnage fait partie de la Légion : <a href='editer_faction.php?id=$faction_id&perso_id=$id'>".$nom_faction."</a>";
					if($nbdem_fac > 0) {
							echo " <span style='color:#07a107'>(".$nbdem_fac.")</span>";
						}else{
						echo '';
						}							
				echo	"</td>
					</tr>
					";
				echo '<tr class=\'tab_tr_ligne0\'>
					<td>
					<form method="post"><input type="hidden" name="perso_id" value="'.$id.'" />
					<input type="hidden" name="faction_nom" value="'.$nom_faction.'" />
					<input type="hidden" name="faction_id" value="'.$faction_id.'" />';
				if (!isset($_POST['quitter']))
					echo '<input type="submit" name="quit_faction" value="Quitter la Légion" />';
				if (isset($_POST['perso_id'])&&$_POST['perso_id']==$id)
					{quit_faction();}
				echo "</form>
					</td>
					</tr>";
			}else
				{
				
				echo "<td colspan=\"3\" align='center'>Votre personnage ne fait partie d'aucune légion.<br/>
					Faire une demande à une Légion (ne dispense pas de contacter un recruteur) :";
					?>
					<img onclick="$('#menu_demande<?php echo $id; ?>').toggle();" src='../images/site/add.png' alt='Update'><br/>
					<div id='menu_demande<?php echo $id; ?>' style='display:none;'>
					<form method="post">
					<br/>Les Légions ci-dessous sont soit de votre race, soit Mauve, renseignez-vous.<br/>
					<?php
					$reponsefaction = mysql_query("SELECT id,nom FROM factions WHERE race REGEXP '$race|2'") or die (mysql_error());
					echo "<select name='faction_id'>" ;
					while ($res_fact = mysql_fetch_array($reponsefaction))
					{
					echo "<option value='".$res_fact['id']."'>".$res_fact['nom']."</option>";
					}
				echo "
				</select>
				<input type='hidden' name='perso_id' value='".$id."' />
				<input type='submit' name='ask_fact' value='Demander' class='bouton' /></form></div>";
				echo "<br/>Accepter ou refuser des propositions faites par des Légions :";
				?>
				</div>
				<img onclick="$('#menu_propositions<?php echo $id; ?>').toggle();" src='../images/site/add.png' alt='Update'>
				<div id='menu_propositions<?php echo $id; ?>' style='display:none;'>
				<form method="post">
				<table align='center'>
					<tr>
					<td><b>Nom de la Légion</b></td><td>Accepter</td><td width="55" >Refuser</td>
					</tr>
				<?php 
				$sql_demande 	= "SELECT faction_id FROM wait_faction WHERE perso_id = $id AND demandeur=0";
				$res_demande	= mysql_query (mysql_real_escape_string($sql_demande)) or die (mysql_error());
				while($demande = mysql_fetch_array ($res_demande))
				{
					$faction_id = $demande['faction_id'];?>
						<tr>
				<?php if (isset($faction_id)) 
						{
						$sql_nom 	= "SELECT 	factions.nom AS nom
										FROM factions
												WHERE id = $faction_id";
						$res_nom	= mysql_query ($sql_nom) or die (mysql_error());
						$faction 		= mysql_fetch_array ($res_nom);
						$faction_nom 	= $faction['nom'];
						?>
							<td align='center'><?php echo '<b>'.$faction_nom.'</b>';?></td><td align='center'><input type="radio" name="oui<?php echo $faction_id;?>" value="1" /></td><td align='center'><input type="radio" name="oui<?php echo $faction_id;?>" value="0" /></td>
				<?php		}
						echo '</tr>';
						}
					?>
						<tr>
					<td><input type='hidden' name='perso_id' value='<?php echo $id ; ?>' /></td><td align='center' colspan='2'><input type="submit" name="demande_factions" value="Valider" /></td>
					</tr>
				</table>
				</form>
				</div>
<?php
				}

			 ?>
			 </table>
			 </td>
		</tr>

	</table>
	<p>&nbsp;</p>
</li>
</ul>
<?php
	}
	echo "</div>";
}

//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
