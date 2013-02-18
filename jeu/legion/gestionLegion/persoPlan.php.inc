<?php

namespace jeu\legion;

/**
 * Gestion des légions en interne : voir la position des ennemis
 *
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 */

$droits = LegionDroits::droitsFromPersoId($id);

if(!$droits->canDo(LegionDroits::LISTE_ENNEMIS))
    die();


function recup_ennemis_prof($id){

    $legion = Legion::getLegionFromId($id);

    $faction_carte = $_SESSION['persos']['carte_respawn'][$id];
    $sql		= 'SELECT persos.nom, damier_persos.perso_id AS perso_id , damier_persos.pos_y AS pos_y
                                    FROM damier_persos
                                    INNER JOIN persos ON persos.id=damier_persos.perso_id
                                    INNER JOIN races ON races.race_id = persos.race_id AND races.grade_id = persos.grade_id
                                    WHERE damier_persos.carte_id='.$_SESSION['persos']['carte_respawn'][$id].'
                                    AND races.camp_id != '.$_SESSION['persos']['camp'][$id].';';

    $resultat	= mysql_query($sql) or die(mysql_error());
    return $resultat;
}

echo '

<div id="classement">

<h2>E.W.O. - Profondeur des ennemis dans votre plan</h2>

		<table align="center" id="tab_classement" BORDER="0px" CELLPADDING="0">
			<tr>
				<td align="center" class="cla_td_titre large">Nom (Mat.) de l\'ennemi</td>
				<td align="center" class="cla_td_titre large">Profondeur</td>
			</tr>';

				$n=4;
				$res = recup_ennemis_prof($id);
				while($resultat = mysql_fetch_array($res)){
				if($n % 2){
						$color = 'row0';
					}else{
						$color = 'row1';
					}
				$perso_id  	= $resultat['perso_id'];
				$pos_y  	= $resultat['pos_y'];
				$url 		= icone_persos($perso_id);
					echo '<tr class="',$color,' winner',$n,'">';

						echo '<td align="center"><img src="../images/',$url,'" alt="avatar"/><br/>'.nom_perso($perso_id,true).'</td>';
						echo '<td align="center">',$pos_y,'</td>';
					echo "<tr>";
						}
			echo '
		</table>
</div>
';
?>
