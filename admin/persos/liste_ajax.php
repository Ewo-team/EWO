<?php
//-- Header --
require_once __DIR__ . '/../../conf/master.php';

//include($root_url."/persos/fonctions.php");
include(SERVER_ROOT."/jeu/fonctions.php");


/*-- Connexion basic requise --*/
ControleAcces('admin',0);

/*$requete = @$_GET['term'];

if(isset($requete)) {
    $bdd = admin\AdminDAO::getInstance();
    
    $liste = $bdd->SelectPersosFromString($requete);
    
    echo '[ ' . implode(", ", $liste) . ' ]';

}*/

			$persos = "SELECT p.id as id, p.nom as nom, u.id as user_id, u.nom as username, 
						c.nom as camps, r.type as type, p.grade_id as grade, p.galon_id as galon, p.titre as titre, p.nom_race as nom_race, p.pnj as pnj, p.mortel as mortel
						FROM persos p
						INNER JOIN utilisateurs u ON (u.id = p.utilisateur_id) 
						INNER JOIN races r ON (p.race_id = r.race_id AND r.grade_id = -2) 
						INNER JOIN camps c ON (r.camp_id = c.id) ORDER BY nom ASC LIMIT 20,18446744073709551615;";

			$resultat = mysql_query ($persos) or die (mysql_error());

			while ($perso = mysql_fetch_array ($resultat)){


				$grade = 'G'.$perso['grade'].'g'.$perso['galon'];
				$special = array();

				if($perso['titre'] != null) 	{ $special[] = 'Titre'; }
				if($perso['nom_race'] != null)	{ $special[] = 'Race personnalisée'; }
				if($perso['pnj'] != 0) 		{ $special[] = 'PNJ'; }
				if($perso['mortel'] != 0) 	{ $special[] = 'Mortel'; }

				echo '<tr>';
				echo "<td><a href='editer_perso.php?id=".$perso['id']."'>".$perso['id']."</td>";
				echo "<td><a href='editer_perso.php?id=".$perso['id']."'>".$perso['nom']."</td>";
				echo "<td><a href='../utilisateurs/editer_utilisateur.php?id=".$perso['user_id']."'>".$perso['username']."</td>";
				echo "<td>".$perso['camps']."</td>";
				echo "<td>".$perso['type']."</td>";
				echo "<td>".$grade."</td>";
				echo "<td>".implode(", ", $special)."</td>";
				//echo "<td>Id : ".$perso['id']." Nom : <a href='editer_perso.php?id=".$perso['id']."'>".$perso['nom']."</a> | <a href=''><img src='./../../images/site/delete.png' alt='Supprimer' style='border:0;'></a> |</li>";
				echo '</tr>';
			}						
