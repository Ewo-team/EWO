<?php

use persos\eventManager\eventFormatter as eventFormatter;

require __DIR__ . '/../../conf/master.php';

if(isset($_GET['mat']) && is_numeric($_GET['mat'])){

	function cmp($a, $b)
	{
		return $a['num'] < $b['num'];
	}

	$bdd = bdd_connect('ewo');
	/* recup des infos du perso:
	 * - Nom (persos.nom), Matricule (persos.id)
	 * -- Camp (camps.nom := persos.race_id = camps.id) &
	 * -- Nom du Grade (races.nom := persos.grade_id = races.grade_id)
	 * -- ID entre parenthèse (persos.grade_id)
	 * - mdj (persos.mdj)
	 */
	$mat = $_GET['mat'];
	/*$sql = 'SELECT p.nom, c.nom AS camp, r.nom AS grade, p.grade_id, p.mdj, p.faction_id '.
	'FROM persos AS p, camps AS c, '.
	'races AS r WHERE p.grade_id = r.grade_id AND p.race_id = r.race_id '.
	'AND p.id = '.$mat.' AND r.camp_id = c.id;';*/

	//recuperation du Nemesis + tete a claque
	// => indic sur si deja tue et/ou tueur
	$sql = 'SELECT m.nom_victime, m.mat_victime, COUNT(*) AS nb '.
	'FROM morgue AS m WHERE id_perso = '.$mat.' '.
	'GROUP BY mat_victime ORDER BY nb DESC LIMIT 1;';
	$res = mysql_query($sql, $bdd);
	$claque = mysql_fetch_assoc($res);

	$sql = 'SELECT m.id_perso, COUNT(*) AS nb '.
	'FROM morgue AS m WHERE mat_victime = '.$mat.' '.
	'GROUP BY id_perso ORDER BY nb DESC LIMIT 1;';
	$res = mysql_query($sql, $bdd);
	$nemesis = mysql_fetch_assoc($res);

	// type = 1 : mort par un sort
	$sql = 'SELECT m.*, r.nom as race 
	FROM morgue AS m 
	INNER JOIN races r ON (m.race_victime = r.race_id AND m.grade_victime = r.grade_id) 
	WHERE id_perso = '.$mat.' ORDER BY date DESC;';
	$res = mysql_query($sql, $bdd);
	if($claque){
		$claques = array();
		?>
<br />
<span class="tab_list_perso" style="clear: both; margin-bottom: 50px;"><strong>&nbsp;Les
victimes&nbsp;</strong></span>
<table class="tab_list_perso" style="width: 100%;">
	<tr>
		<td colspan='2'>
		<div style="width: 100%; max-height: 185px; overflow: auto;">
		<table class="mort" width="100%">
			<tr>
				<th colspan="2">Liste des victimes</th>
				<th width="110">Date</th>
			</tr>
			<?php
			$first = true;
			while($tue = mysql_fetch_assoc($res)){
                                $race = ($tue['nom_race_victime']) ?: $tue['race'];
				$claques[$tue['nom_victime']]['num'] = 1 + ((isset($claques[$tue['nom_victime']]['num']))?$claques[$tue['nom_victime']]['num']:0);
				$claques[$tue['nom_victime']]['info']= $tue;
				if($first){
					echo '<tr>';
					$first = false;
				}else{
					echo '<tr style="border-top: 2px dashed #634F18;">';
				}
				echo '<td><img src="'.SERVER_URL.'/images/'.icone_persos($tue['mat_victime']).'" alt="avatar"/></td>'.
			'<td width="100%">'.$tue['nom_victime'].' (<a
			href="../event/?id='.$tue['mat_victime'].'">'.$tue['mat_victime'].'</a>), '.$race.' de Grade '.$tue['grade_victime'].'</td>'.
			'<td><span style="white-space:nowrap">'.$tue['date'].'</span></td></tr>';
			}
			?>
		</table>
		</div>
		</td>
	</tr>
</table>
			<?php
			usort($claques, 'cmp');
			$claque = reset($claques);
	}
	$sql = 'SELECT m.id_perso, m.nom_perso as nom, m.grade_perso, m.nom_race_perso, m.date, r.nom as race '.
	'FROM morgue AS m '.
	'INNER JOIN races r ON (m.race_perso = r.race_id AND m.grade_perso = r.grade_id) '.
	'WHERE mat_victime = '.$mat.' ORDER BY date DESC;';
	$res = mysql_query($sql, $bdd);
	if($nemesis){
		$nemesiss = array();
		?>
<br />
<span class="tab_list_perso"><strong>&nbsp;Les bourreaux&nbsp;</strong></span>
<table class="tab_list_perso" style="width: 100%;">
	<tr>
		<td colspan='2'>
		<div style="width: 100%; max-height: 185px; overflow: auto;">
		<table class="mort" width="100%">
			<tr>
				<th colspan="2">Liste des meurtri'eurs'</th>
				<th>Date</th>
			</tr>
			<?php
			$first = true;
			while($tueur = mysql_fetch_assoc($res)){
                                $race = ($tueur['nom_race_perso']) ?: $tueur['race'];
				$nemesiss[$tueur['nom']]['num'] = 1 + ((isset($nemesiss[$tueur['nom']]['num']))?$nemesiss[$tueur['nom']]['num']:0);
				$nemesiss[$tueur['nom']]['info']= $tueur;
				if($first){
					echo '<tr>';
					$first = false;
				}else{
					echo '<tr style="border-top: 2px dashed #634F18;">';
				}
				echo '<td><img src="'.SERVER_URL.'/images/'.icone_persos($tueur['id_perso']).'" alt="avatar"/></td>'.
			'<td width="100%">'.$tueur['nom'].' (<a
			href="../event/?id='.$tueur['id_perso'].'">'.$tueur['id_perso'].'</a>), '.$race.' de Grade '.$tueur['grade_perso'].'</td>'.
			'<td><span style="white-space:nowrap">'.$tueur['date'].'</span></td></tr>';
			}
			?>
		</table>
		</div>
		</td>
	</tr>
</table>
			<?php
			usort($nemesiss, 'cmp');
			$nemesis = reset($nemesiss);
	}
	if($nemesis && $nemesis['num']>1){
		$perso = reset($nemesiss);
		?>
<div style="float: right; width: 49%; display: block;"><br />
<span class="tab_list_perso"><strong>&nbsp;Nemesis&nbsp;</strong></span>
<table class="tab_list_perso" style="width: 100%;">
<?php while ($nemesis['num'] == $perso['num']) { ?>
	<tr>
		<td class='tab_td_icone'><img
			src='<?php echo SERVER_URL.'/images/'.icone_persos($perso['info']['id_perso']); ?>'
			alt='avatar' /></td>
		<td class='tab_td'><strong><?php echo $perso['info']['nom']; ?>
		(<?php echo '<a
			href="../event/?id='.$perso['info']['id_perso'].'">'.$perso['info']['id_perso']; ?></a>)</strong></td>
	</tr>

	<tr style="background-color: #C3B689;">
		<td colspan="2">L'a tu&eacute; <b><?php echo $perso['num']; ?></b>
		fois</td>
	</tr>
	<?php
	$perso = next($nemesiss);
} ?>
</table>
</div>
<?php }
if($claque && $claque['num']>1){
	$perso = reset($claques);
	?>
<div style="width: 49%;"><br />
<span class="tab_list_perso"><strong>&nbsp;T&ecirc;te &agrave;
claques&nbsp;</strong></span>
<table class="tab_list_perso" style="width: 100%;">
<?php while ($claque['num'] == $perso['num']) { ?>
	<tr>
		<td class='tab_td_icone'><img
			src='<?php echo SERVER_URL.'/images/'.icone_persos($perso['info']['mat_victime']); ?>'
			alt='avatar' /></td>
		<td class='tab_td'><strong><a
			href='../event/?id=<?php echo $perso['info']['mat_victime'];?>'><?php echo $perso['info']['nom_victime']; ?></a>
		(<?php echo $perso['info']['mat_victime']; ?>)</strong></td>
	</tr>

	<tr style="background-color: #C3B689;">
		<td colspan="2">A &eacute;t&eacute; tu&eacute; <b><?php echo $perso['num']; ?></b>
		fois</td>
	</tr>
	<?php
	$perso = next($claques);
} ?>
</table>
</div>
<?php
}
}
?>
<span
	style="display: block; clear: both;"></span>
