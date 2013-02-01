<?php
/**
 * Classement old format
 *
 * @author Ganesh <ganesh@ewo.fr>
 * @version 1.0
 * @package classement
 */
//-- Header --
$root_url = "..";
include($root_url."/template/header_new.php");

include($root_url."/persos/fonctions.php");

ControleAcces('utilisateur',1);

$id_utilisateur = $_SESSION['utilisateur']['id'];

if (isset($_POST['perso_id'])){
	$id = mysql_real_escape_string($_POST['perso_id']);
}elseif(isset($_GET['perso_id'])){
	$id = mysql_real_escape_string($_GET['perso_id']);
}else{
	//echo "<script language='javascript' type='text/javascript' >document.location='./../'</script>";
	//exit;
}

?>
<div id="classement" align="center">	
<h2>E.W.O. - Superman</h2>

<p>Les valeurs fournies entre parenth&egrave;ses correspondent &agrave; la moyenne arrondie &agrave; la seconde d&eacute;cimale des incarnés.</p>

<?php

for ($inc=1; $inc<=4; $inc++)
	{
	switch ($inc){
		case 1 :
			$name="Superman (Humain)";
			$url="persos/humain/roi.gif";
			$grade=4;
			break;
		case 2 :
			$name="Paritalion (Paria)";
			$url="persos/perso/aigle.png";
			$grade=4;
			break;
		case 3 :
			$name="Le Saint (Ange)";
			$url="persos/archange/aa.gif";
			$grade=3;
			break;
		case 4 :
			$name="Luciferion (Demon)";
			$url="persos/seigneur demon/sd.gif";
			$grade=3;
			break;
		default:
			}
	$premier_el = true;
	$sql = "SELECT races.race_id, races.nom, camps.nom AS nom_camp FROM races INNER JOIN camps ON races.camp_id = camps.id WHERE races.grade_id = -2 AND races.camp_id = $inc";
	$res_race = mysql_query ($sql) or die (mysql_error());
	while($rep_race = mysql_fetch_array($res_race)){
	
	$race_id 	= $rep_race['race_id'];
	$name 		= $rep_race['nom'];
	$nom_camp	= $rep_race['nom_camp'];
		// Recherche des valeurs max et moyennes pour chaque race
		$sql="SELECT MAX(niv_pv) AS max_pv, MAX(niv_pa) AS max_pa, MAX(niv_mouv) AS max_mouv, MAX(niv_des) AS max_des,
					 MAX(niv_force) AS max_force, MAX(niv_perception) AS max_perception, MAX(niv) AS max_magie
				FROM caracs
					INNER JOIN persos ON persos.id=caracs.perso_id
				WHERE persos.race_id=$race_id AND persos.grade_id<=$grade";
		$resultat = mysql_query ($sql) or die (mysql_error());
		$rep = mysql_fetch_array($resultat);
		
		$sql="SELECT AVG(niv_pv) AS avg_pv, AVG(niv_pa) AS avg_pa, AVG(niv_mouv) AS avg_mouv, AVG(niv_des) AS avg_des,
					 AVG(niv_force) AS avg_force, AVG(niv_perception) AS avg_perception, AVG(niv) AS avg_magie
				FROM caracs
					INNER JOIN persos ON persos.id=caracs.perso_id
					INNER JOIN damier_persos ON persos.id=damier_persos.perso_id
				WHERE persos.race_id=$race_id AND persos.grade_id<=$grade";
		$resultat = mysql_query ($sql) or die (mysql_error());
		$rep_avg = mysql_fetch_array($resultat);

		//Recherche de la couleur
		$sql_color="SELECT color
				FROM races
					WHERE races.race_id=$race_id AND races.grade_id=0";
		$resultat_color = mysql_query ($sql_color) or die (mysql_error());
		$rep_color = mysql_fetch_array($resultat_color);

		//Si c'est le premier tableau du camp on met le titre
		if($premier_el)
			echo "<h3>$nom_camp</h3>";
		$premier_el = false;
	?>

	<table class='tab_list_perso'>
		<tr>
			<td class='tab_td_icone'><img src='<?php echo $root_url; ?>/images/<?php echo $url; ?>' alt='avatar' title='Avatar de <?php echo $name; ?>' /></td>
			<td class='tab_td'><?php echo $name; ?></td>
		</tr>
		<tr>
			<td colspan='2'>
				<table class='tab_list_perso_carac'>
					<tr class='tab_tr_ligne_titre'>
						<td colspan='2'><img class='tab_puce' src='<?php echo $root_url; ?>/images/transparent.png' alt='puce' /> Caract&eacute;ristique du joueur :</td>
					</tr>		
					<tr class='tab_tr_ligne0'>
						<td>Point de vie : </td>
						<td><?php echo carac_max_no_galon ($race_id, 0, 'pv', $rep['max_pv'])." (".round(carac_max_no_galon ($race_id, 0, 'pv', $rep_avg['avg_pv']),2).")"?></td>
					</tr>
					<tr class='tab_tr_ligne1'>
						<td>Mouvement : </td>
						<td><?php echo carac_max_no_galon ($race_id, 0, 'mouv', $rep['max_mouv'])." (".round(carac_max_no_galon ($race_id, 0, 'mouv', $rep_avg['avg_mouv']), 2).")"?></td>
					</tr>
					<tr class='tab_tr_ligne0'>
						<td>Point d'action : </td>
						<td><?php echo carac_max_no_galon ($race_id, 0, 'pa', $rep['max_pa'])." (".round(carac_max_no_galon ($race_id, 0, 'pa', $rep_avg['avg_pa']), 2).")"?></td>
					</tr>
					<tr class='tab_tr_ligne1'>
						<td>Force : </td>
						<td><?php echo carac_max_no_galon ($race_id, 0, 'force', $rep['max_force'])." (".round(carac_max_no_galon ($race_id, 0, 'force', $rep_avg['avg_force']), 2).")"?></td>
					</tr>	
					<tr class='tab_tr_ligne0'>
						<td>D&eacute; :</td>
						<td><?php echo carac_max_no_galon ($race_id, 0, 'des', $rep['max_des'])." (".round(carac_max_no_galon ($race_id, 0, 'des', $rep_avg['avg_des']), 2).")"?></td>
					</tr>
					<tr class='tab_tr_ligne1'>
						<td>Magie :</td>
						<td><?php echo carac_max_no_galon ($race_id, 0, 'magie', $rep['max_magie'])." (".round(carac_max_no_galon ($race_id, 0, 'magie', $rep_avg['avg_magie']), 2).")"?></td>
					</tr>		
					<tr class='tab_tr_ligne0'>
						<td>Perception :</td>
						<td><?php echo carac_max_no_galon ($race_id, 0, 'perception', $rep['max_perception'])." (".round(carac_max_no_galon ($race_id, 0, 'perception', $rep_avg['avg_perception']), 2).")"?></td>
					</tr>										
				</table>
			</td>
		</tr>
	</table>
	<br />
	<?php
		}
	if($inc<4)
		echo "<hr />";
	}
?>
</div>

<?php	

//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
