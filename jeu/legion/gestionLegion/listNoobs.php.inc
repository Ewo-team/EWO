<?php

/**
 * Gestion des légions en interne : voir la liste des noobs
 *
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 */

include_once('class/LegionDroits.php.inc');
include_once('class/Legion.php.inc');

use legions\LegionDroits    as LegionDroits;
use legions\Legion          as Legion;

$droits = LegionDroits::droitsFromPersoId($id);

if(!$droits->canDo(LegionDroits::LISTE_NOOBS))
    die();

echo '

<div id="classement">

<h2>Liste des nouveaux</h2>

		<table align="center" id="tab_classement" BORDER="0px" CELLPADDING="0">
			<tr>
				<th align="center" class="cla_td_titre large"></th>
				<th align="center" class="cla_td_titre large">Id</th>
				<th align="center" class="cla_td_titre large">Nom</th>
			</tr>';

				$n=0;
				 $legion = Legion::getLegionFromId($id);

			    $faction_carte = $_SESSION['persos']['carte_respawn'][$id];
			    $sql = '
			    	SELECT
			    		p.id as id, p.nom as name
					FROM
			    		persos p
			    	INNER JOIN
			    		races r ON r.race_id = p.race_id AND r.grade_id = p.grade_id
			        WHERE
			    		DATE_ADD(p.creation_date, INTERVAL 15 DAY) >= NOW()
						AND r.camp_id = '.$_SESSION['persos']['camp'][$id].'
					ORDER BY p.creation_date DESC;';
			    $resultat	= mysql_query($sql) or die(mysql_error());
				while($perso = mysql_fetch_object( $resultat)){
					$url 		= icone_persos($perso->id);
					echo '
			<tr>
				<td align="center"><img src="../../images/',$url,'" alt="avatar"/></td>
				<td align="center"><a href="'.$root_url.'messagerie/index.php?id='.$_GET['mat'].'&dest='.$perso->id.'">'.$perso->id.'</a></td>
				<td align="center">',$perso->name,'</td>
			<tr>';
				}
			echo '
		</table>
</div>
';
?>
