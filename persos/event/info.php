<?php
use \persos\eventManager\eventFormatter as eventFormatter;

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
	
	$sql = 'SELECT p.nom, cl.Titre as classe_titre, cl.Sub as classe_sub, p.background AS background, x.sexe AS sexe, c.nom AS camp, r.nom AS grade, p.galon_id AS galon, p.grade_id, p.mdj, p.faction_id, p.nom_race, s.id as superieur_mat, s.nom as superieur_nom '.
	'FROM persos AS p '.
	'LEFT JOIN sexe AS x ON (p.sexe = x.id) '.
	'LEFT JOIN persos AS s ON (p.superieur_id = s.id) '.
	'LEFT JOIN races AS r ON (p.race_id = r.race_id AND p.grade_id = r.grade_id) '.
	'LEFT JOIN camps AS c ON (r.camp_id = c.id) '.
	'LEFT JOIN classes AS cl ON (cl.Camps = c.id AND cl.Id = p.classe) '.
	'WHERE p.id = '.$mat.';';
	$res = mysql_query($sql, $bdd);
	$perso = mysql_fetch_assoc($res);
	$icone = array();
	
	$fid; //faction_id de perso
	$sql = 'SELECT f.logo_url, f.nom, f.type_nom, fg.nom AS grade '.
	'FROM factions AS f JOIN faction_grades AS fg ON f.id = fg.faction_id, faction_membres AS fm '.
	'WHERE f.id = fm.faction_id AND fg.grade_id = fm.faction_grade_id '.
	'AND f.id = '.$perso['faction_id'].' AND fm.perso_id = '.$mat.';';
	$res = mysql_query($sql, $bdd);
	if($faction = mysql_fetch_assoc($res)){
		?>
<table class="tab_list_perso" style="width: 49%; float: right;">
	<tr>
		<td class='tab_td_icone'><img
			src='<?php echo $faction['logo_url']; ?>' alt='avatar' width="45"
			height="33" /></td>
		<td class='tab_td'><strong><?php echo $faction['nom']; ?></strong></td>
	</tr>
	<tr>
		<td colspan='2'>
		<table class='tab_list_perso_carac'>
			<tr class='tab_tr_ligne0'>
				<td><i>Type : </i></td>
				<td><?php echo $faction['type_nom']; ?></td>
			</tr>
			<tr class='tab_tr_ligne1'>
				<td><i>Grade : </i></td>
				<td><?php echo htmlspecialchars_decode($faction['grade']); ?> (<?php echo $perso['grade_id']; ?>)</td>
			</tr>		
		</table>
		</td>
	</tr>
</table>

		<?php
	}
        
        $race_affichage = ($perso['nom_race']) ?: $perso['camp'] ;
        $grade_affichage = ($perso['nom_race']) ? "Grade " . $perso['grade_id'] : htmlspecialchars_decode($perso['grade']).' ('.$perso['grade_id'].')' ;
		$classe_affichage = ($perso['classe_titre']) ? "<b>" . $perso['classe_titre'] . "</b><br>" . $perso['classe_sub'] : "" ;
        
	?>
<table class="tab_list_perso" style="width: 49%;">
	<tr>
		<td class='tab_td_icone'><img
			src='<?php echo SERVER_URL.'/images/'.icone_persos($mat); ?>'
			alt='avatar' /></td>
		<td class='tab_td'><strong><?php echo $perso['nom']; ?> (<a
			href='../event/?id=<?php echo $mat;?>'><?php echo $mat; ?></a>)</strong></td>
	</tr>
	<tr>
		<td colspan='2'>
		<table class='tab_list_perso_carac'>
			<tr class='tab_tr_ligne0'>
				<td><i>Camp : </i></td>
				<td><?php echo $race_affichage; ?></td>
			</tr>
			<tr class='tab_tr_ligne1'>
				<td><i>Grade : </i></td>
				<td><?php echo $grade_affichage; ?>, Galon <?php echo $perso['galon'] ?></td>
			</tr>
			<tr class='tab_tr_ligne1'>
				<td><i>Classe : </i></td>
				<td><?php echo $classe_affichage; ?></td>
			</tr>				
			<tr class='tab_tr_ligne0'>
				<td><i>Sexe : </i></td>
				<td><?php echo $perso['sexe']; ?></td>
			</tr>		
			<?php if(isset($perso['superieur_mat'])) { ?>
			<tr class='tab_tr_ligne1'>
				<td><i>Au ordres de : </i></td>
				<td><?php echo $perso['superieur_nom'] ?> (Mat. <?php echo $perso['superieur_mat']; ?>)</td>
			</tr>	
			<?php } ?>
			<tr class='tab_tr_ligne_titre'>
				<td colspan='2'>Message du jour :</td>
			</tr>

			<tr class='tab_tr_ligne0'>
				<td align='left' colspan='2' style="text-align: justify;"><?php echo htmlspecialchars_decode($perso['mdj']); ?></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
	<?php

	$sql = 'SELECT * FROM medailles, medailles_liste WHERE id_perso = '.$mat.' AND id_medaille = id ORDER BY niveau ASC, priorite DESC';
	$result = mysql_query($sql,$bdd);

	
	if(mysql_num_rows($result)) {
		echo '<br /><span class="tab_list_perso" style="clear: both; margin-bottom: 50px;"><strong>&nbsp;Médailles&nbsp;</strong></span>
		<div class="tab_list_perso">';
	
		while($medaille = mysql_fetch_assoc($result)) {
			
			switch($medaille['niveau']) {
				case 1: $fond = 'platine'; break;
				case 2: $fond = 'or'; break;
				case 3: $fond = 'argent'; break;
				case 4: $fond = 'bronze'; break;
				case 5: $fond = 'chocolat'; break;
			}
			
			echo '<div class="medaille">
			<img src="../images/medaille/'.$medaille['image'].'.png">';
			if($medaille['nombre'] > 1) {
				echo '<span>'.$medaille['nombre'].'</span>';
			}
			echo '<div class="info_medaille '.$fond.'"><b>'.$medaille['nom'].'</b><br />'.$medaille['description'].'</div></div>';
		}
		echo '</div>';
	}
			
	if(!empty($perso['background']) && strlen($perso['background']) > 0){

		?>
<br />
<span class="tab_list_perso" style="clear: both; margin-bottom: 50px;"><strong>&nbsp;Histoire&nbsp;</strong></span>
			<div class="tab_list_perso"><?php echo $perso['background']; ?></div>
	<?php
	}


}
?>
<span
	style="display: block; clear: both;"></span>

