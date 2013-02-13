<?php
//-- Header --

$css_files = 'listeperso';

require_once __DIR__ . '/../conf/master.php';
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
include(SERVER_ROOT . "/template/header_new.php");

/*-----------------------------*/
?>
<h2>Liste de vos personnages</h2>

<div align='center'>

<?php

include( SERVER_ROOT . "/persos/fonctions.php");

$utilisateur_id = $_SESSION['utilisateur']['id'];

if ($_SESSION['persos']['inc']!=0){

	$sql = 'SELECT persos.id AS id_perso, persos.race_id, persos.grade_id, persos.nom AS nom_perso, ' .
			'persos.date_tour AS tour, races.color AS couleur, persos.mortel AS mortel
					FROM persos
						INNER JOIN races
							ON persos.race_id = races.race_id AND persos.grade_id = races.grade_id
								WHERE utilisateur_id = \''.$utilisateur_id.'\' AND persos.mortel >= 0 ORDER BY persos.mortel ASC, persos.id ASC';
	$inc=0;
	$resultat = mysql_query ($sql) or die (mysql_error());
	$format= 'd-m-Y';
	$format2=' H:i:s';
	$date = date($format);
	$date2= date($format2);

	echo '<span class="tab_list_perso">Nous sommes le '.$date.' 	&agrave; '.$date2.'</span>';
	echo '<table class="tab_list_perso2">';
	echo '<tr><th colspan="2">Nom</th><th class="fonce">PV</th><th>Mvt</th><th class="fonce">PA</th><th>Force</th><th class="fonce">Dext.</th><th>Malus</th><th class="fonce">Magie</th><th>Recup. PV-Malus</th><th class="fonce">Vue</th><th>PI/XP</th><th class="fonce">Lieu</th><th>Prochain tour</th></tr>';
	$display_traitres = false;
	while ($perso = mysql_fetch_array ($resultat)){
		$id			= $perso['id_perso'];
		$sql1 		= 'SELECT damier_persos.pos_x AS pos_x, damier_persos.pos_y AS pos_y, cartes.nom AS cartes
						FROM damier_persos
						INNER JOIN cartes ON damier_persos.carte_id = cartes.id
						WHERE perso_id = \''.$id.'\'';

		$resultat1	= mysql_query ($sql1) or die (mysql_error());
		$carac		= mysql_fetch_array ($resultat1);

		echo lignePerso($perso,$carac,++$inc);
		if($display_traitres === false){
			$galon_grade	= recup_race_grade($id);
			$galon			= $galon_grade['galon_id'];
			$grade			= $galon_grade['grade_id'];

			/*if($grade>=3 && $galon>=2){
				$display_traitres = '<a href="../faction/traitres.php?perso_id='.$id.'">Liste des personnes pouvant &ecirc;tre pass&eacute;es tra&icirc;tre</a>';
			}*/
		}
	}
	echo '</table>';
	if($display_traitres !== true){
		echo $display_traitres;
	}

}else{
	echo "Vous ne possédez pas encore de personnage, <a href='../inscription/creation_perso.php'>Créer un personnage<a/>";
}
?>

</div>
<?php
//-- Footer --
include( SERVER_ROOT ."/template/footer_new.php");
//------------
?>
