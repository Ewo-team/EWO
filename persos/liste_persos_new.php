<?php
//-- Header --
$root_url = "..";
include($root_url."/template/header_new.php");
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/
?>

<a href='./liste_persos.php'>>ICI version test de la page perso<</a>

<h2>Liste de vos personnages</h2>

<div align='center'>

<?php

include("".$root_url."/persos/fonctions.php");

$utilisateur_id = $_SESSION['utilisateur']['id'];

if ($_SESSION['persos']['inc']!=0){
$sql = "SELECT nom, id FROM persos WHERE utilisateur_id = '$utilisateur_id' ORDER BY persos.id ASC";
$nomn ='';
$resultat = mysql_query ($sql) or die (mysql_error());
	while ($person = mysql_fetch_array ($resultat)){
		$nomn        .= "<span class='curspointer' onclick=\"retour_top('".$person['id']."');\">".$person['nom']."</span> | ";
	}
echo "<p>".substr($nomn, 0, -2)."</p>";
echo"<div class='clear separation'></div>";


$sql = "SELECT persos.id AS id_perso, persos.race_id, persos.grade_id, persos.nom AS nom_perso, persos.date_tour AS tour, races.color AS couleur, caracs.pv, caracs.niv_pv, caracs.mouv, caracs.niv_mouv, caracs.pa, caracs.niv_pa, caracs.px   
				FROM persos 
					INNER JOIN races 
						ON persos.race_id = races.race_id AND persos.grade_id = races.grade_id
					INNER JOIN caracs 
						ON caracs.perso_id = persos.id
							WHERE utilisateur_id = '$utilisateur_id' ORDER BY persos.id ASC";
$inc=0;							
$resultat = mysql_query ($sql) or die (mysql_error());

while ($perso = mysql_fetch_array ($resultat)){
$inc++;
$id = $perso['id_perso'];

$sql1 = "SELECT damier_persos.pos_x AS pos_x, damier_persos.pos_y AS pos_y, cartes.nom AS cartes 
					FROM damier_persos
						INNER JOIN cartes
							ON damier_persos.carte_id = cartes.id
								WHERE perso_id = '$id'";
$resultat1 = mysql_query ($sql1) or die (mysql_error());
$carac = mysql_fetch_array ($resultat1);

$caracs = calcul_caracs($id);

						
$nom        = $perso['nom_perso'];
$race		    = $perso['race_id'];
$grade	   	= $perso['grade_id'];
$couleur    = $perso['couleur'];
$pv         = $caracs['pv'];
$niv_pv     = $perso['niv_pv'];
$x          = $carac['pos_x'];
$y          = $carac['pos_y'];
$plan       = $carac['cartes'];
$date_tour  = $perso['tour'];
$mouv       = $caracs['mouv'];
$niv_mouv   = $perso['niv_mouv'];
$pa         = $caracs['pa'];
$pa_dec     = $caracs['pa_dec'];
$niv_pa     = $perso['niv_pa'];
$xp         = $perso['px'];

$pa_max		= carac_max ($race, $grade, 'pa', $niv_pa, $id) + carac_max ($race, $grade, 'pa_dec', $niv_pa, $id)/10;
$pv_max		= carac_max ($race, $grade, 'pv', $niv_pv, $id);
$mouv_max	= carac_max ($race, $grade, 'mouv', $niv_mouv, $id);

$url = icone_persos($id);

// Calcule si l'activation du nouveau tour peu etre faite ou pas
$timestamp = strtotime($date_tour);
$date_tour = date('d-m-Y à H:i:s',$timestamp);
$time = time();

$format= 'd-m-Y';
$format2=' H:i:s';
$date = date($format);
$date2= date($format2);

echo "<span id=".$id."></span>";
echo "<span class='tab_list_perso'>Nous sommes le ".$date." 	&agrave; ".$date2."</span>";

?>
<table class='tab_list_perso'>
	<tr>
		<td class='tab_td_icone'><img src='<?php echo $root_url; ?>/images/<?php echo $url; ?>' alt='avatar' title='Avatar de <?php echo $nom; ?>' /></td>
		<td class='tab_td'><a href='<?php echo $root_url; ?>/event/liste_events.php?id=<?php echo $id; ?>'><?php echo $nom; ?></a> (<?php echo $id; ?>)</td>
	</tr>

	<tr>
		<td colspan='2'>
			<table class='tab_list_perso_carac'>
				<tr class='tab_tr_ligne_titre'>
					<td colspan='2'><img class='tab_puce' src='<?php echo $root_url; ?>/images/transparent.png' alt='puce' /> Caractéristiques du personnage :</td>
				</tr>		
				<tr class='tab_tr_ligne0'>
					<td>Prochain tour : </td>
					<td><?php echo $date_tour; ?></td>
				</tr>
				<tr class='tab_tr_ligne1'>
					<td>Position : </td>
					<td>X: <?php echo $x; ?> / Y: <?php echo $y; ?> sur <?php echo $plan; ?></td>
				</tr>
				<tr class='tab_tr_ligne0'>
					<td>Points de vie : </td>
					<td><?php echo $pv; ?> / <?php echo $pv_max; ?></td>
				</tr>
				<tr class='tab_tr_ligne1'>
					<td>Mouvements : </td>
					<td><?php echo $mouv; ?> / <?php echo $mouv_max; ?></td>
				</tr>	
				<tr class='tab_tr_ligne0'>
					<td>Points d'action :</td>
					<td><?php echo $pa+$pa_dec/10; ?> / <?php echo $pa_max; ?></td>
				</tr>
				<tr class='tab_tr_ligne_titre'>
					<td colspan='2'><img class='tab_puce' src='<?php echo $root_url; ?>/images/transparent.png' alt='puce' /> Actions du personnage :</td>
				</tr>
	
				<tr class='tab_tr_ligne0'>
					<td align='center' colspan='2'>
						[<a href='<?php echo $root_url; ?>/persos/editer_perso.php?id=<?php echo $id; ?>'>Editer ce personnage</a>]
			 		</td>
				</tr>
<?php 
$galon_grade = recup_race_grade($id);

$galon = $galon_grade['galon_id'];
$grade = $galon_grade['grade_id'];

if($grade>=3 && $galon>=2){
				?>			
				<tr class='tab_tr_ligne1'>
					<td align='center' colspan='2'><a href='../faction/traitres.php?perso_id=<?php echo $id ?>'>Liste des personnes pouvant &ecirc;tre pass&eacute;es tra&icirc;tre</a></td>
				</tr>
<?php
}
	if (isset($x) AND isset($y)){
					if ($time >= $timestamp){
				?>			
				<tr class='tab_tr_ligne1'>
					<td align='center' colspan='2'>[<a href='activer_tour.php?perso_id=<?php echo $inc; ?>'>Activer son tour</a>]</td>
				</tr>
				<?php
					}
				?>	
				<tr class='tab_tr_ligne1'>
					<td align='center' colspan='2'> [<a href='<?php echo $root_url; ?>/jeu/index.php?perso_id=<?php echo $inc; ?>'>Jouer</a>]</td>
				</tr>														
			</table>
		</td>
	</tr>
<?php
}else{
?>

				<tr class='tab_tr_ligne0'>
					<td colspan='2'>Votre personnage est désincarné</td>
				</tr>
				<tr class='tab_tr_ligne1'>
					<td colspan='2'>[<a href='<?php echo $root_url; ?>/jeu/index.php?perso_id=<?php echo $inc; ?>'> Sélectionner une zone de réincarnation </a>]</td>
				</tr>
			</table>
		</td>
	</tr>			
<?php
	}

?>
	</table>
	
	<p>&nbsp;</p>

<?php
	}
}else{
	echo "Vous ne possédez pas encore de personnage, <a href='../inscription/creation_perso.php'>Créer un personnage<a/>";
}
?>

</div>
<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
