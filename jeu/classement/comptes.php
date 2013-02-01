<?php
/**
 * Classement
 *
 * @author Ganesh <ganesh@ewo.fr>
 * @version 1.0
 * @package classement
 */
//-- Header --
$root_url = "..";
include($root_url."/template/header_new.php");

include($root_url."/persos/fonctions.php");
include("./fonctions.php");

ControleAcces('utilisateur',1);

$id_utilisateur = $_SESSION['utilisateur']['id'];
		
if(!empty($_POST['nb_jours'])){
	$nb_jours = mysql_real_escape_string($_POST['nb_jours']);
} elseif(!empty($_GET['nb_jours'])){
	$nb_jours = mysql_real_escape_string($_GET['nb_jours']);
} else $nb_jours=0;

if (!empty($_POST['race'])){
	$race = mysql_real_escape_string($_POST['race']);
} elseif (!empty($_GET['race'])){
	$race = mysql_real_escape_string($_GET['race']);
} else $race=0;
	
if (!empty($_POST['croissant'])){
	$croissant = mysql_real_escape_string($_POST['croissant']);
	}
	elseif (!empty($_GET['croissant'])){
		$croissant = mysql_real_escape_string($_GET['croissant']);
		}
		else $croissant="DESC";

if (!empty($_POST['jour'])){
	$time = strtotime(mysql_real_escape_string($_POST['jour']));
	$time_m = date('d-m-Y',$time-24*3600);
	$time_p = date('d-m-Y',$time+24*3600);
	$time = date('d-m-Y',$time);
} elseif (!empty($_GET['jour'])){
	$time = strtotime(mysql_real_escape_string($_GET['jour']));
	$time_m = date('d-m-Y',$time-24*3600);
	$time_p = date('d-m-Y',$time+24*3600);
	$time = date('d-m-Y',$time);
} else {
	$time = time();
	$time = date('d-m-Y',$time);
	$time = strtotime($time);
	$time_m = date('d-m-Y',$time-24*3600);
	$time_p = date('d-m-Y',$time+24*3600);
	$time = date('d-m-Y',$time);
}

switch($race) {
	case 1 :
		$type="humain";
		$where_race = "AND persos.race_id=1";
		break;
	case 2 :
		$type="paria";
		$where_race = "AND persos.race_id=2";
		break;
	case 3 :
		$type="ang&eacute;lique";
		$where_race = "AND persos.race_id=3";
		break;
	case 4 :
		$type="d&eacute;moniaque";
		$where_race = "AND persos.race_id=4";
		break;
	case -1 :
		$type="ang&eacute;monique";
		$where_race = "AND (persos.race_id=3 OR persos.race_id=4)";
		break;
	default :
		$type="toutes races";
		$where_race = "";
};


?>
<div id="classement">

<h2>E.W.O. - Mausol&eacute;e <?php echo $type; ?></h2>

<div align="center">
	<form method="post" action="comptes.php">
	<select id="nb_jours" name="nb_jours">
			<option value="0" <?php if($nb_jours==0){echo 'selected="selected"';}?>>Nombre de jours : </option>
		  <option value="0" <?php if($nb_jours==0){echo 'selected="selected"';}?>>Depuis le d&eacute;but</option>
		  <option value="1" <?php if($nb_jours==1){echo 'selected="selected"';}?>>1</option>
		  <option value="2" <?php if($nb_jours==2){echo 'selected="selected"';}?>>2</option>
		  <option value="7" <?php if($nb_jours==7){echo 'selected="selected"';}?>>7</option>
		  <option value="30" <?php if($nb_jours==30){echo 'selected="selected"';}?>>30</option>
		  <option value="45" <?php if($nb_jours==45){echo 'selected="selected"';}?>>45</option>
	</select>
	<input type="hidden" name="jour" value="<?php echo $time; ?>" />
	<input type="submit" value="Filtrer" /> <br/><br/>
	</form>
</div>
<?php
			$Jour=strtotime($time)+24*3600;
			$JourProf=$Jour-$nb_jours*24*3600;

			$Jour = date('Y-m-d H:i:s', $Jour);
			if($nb_jours)
				$JourProf = date('Y-m-d H:i:s', $JourProf);
				else $JourProf = '0000-00-00 00:00:00';
			
		?>
		<div align="center">
		<?php 	echo "<a href='$root_url/classement/comptes.php?jour=".$time_m."&nb_jours=$nb_jours'><= </a>";
				echo $time;
				$now = date('d-m-Y',time());
				if ($time_p <= $now)
					echo "<a href='$root_url/classement/comptes.php?jour=".$time_p."&nb_jours=$nb_jours'> =></a><br/>";
		?>				
		</div>
				
		<table align='center' id="tab_classement" BORDER='0px' CELLPADDING='0'>			
		<tr><td>

		
<ul>Les Démons ont :
<br />
	<?php
		$resultat = getKillCount("", $time, $nb_jours, $croissant, "AND p1.race_id=4", "AND p2.race_id=4", "", "");
		$nb_kill  = mysql_fetch_array($resultat);
		$nb_kill  = $nb_kill[0];
		$nb_demons= $nb_kill;
		?>
	<li> Affectueusement lynché <b><?php echo $nb_kill ?></b> des leurs.</li>
	<?php
		$resultat = getKillCount("", $time, $nb_jours, $croissant, "AND p1.race_id=4", "AND p2.race_id=3", "", "");
		$nb_kill  = mysql_fetch_array($resultat);
		$nb_kill  = $nb_kill[0];
		$nb_anges = $nb_kill;
		?>	
	<li> Soigneusement zigouillé <b><?php echo $nb_kill ?></b> Anges.</li>
	<?php
		$nb_kill  = $nb_demons + $nb_anges;
		?>	
	<li> Finalement fait la peau à <b><?php echo $nb_kill ?></b> Ailés.</li>
	<?php
		$resultat = getKillCount("", $time, $nb_jours, $croissant, "AND p1.race_id=4", "AND p2.race_id=1", "", "");
		$nb_kill  = mysql_fetch_array($resultat);
		$nb_kill  = $nb_kill[0];
		$nb_humains= $nb_kill;
		?>	
	<li> Délicatement croqué <b><?php echo $nb_kill ?></b> Humains.</li>
	<?php
		$nb_kill  = $nb_demons + $nb_anges + $nb_humains;
		?>
Au total les Démons ont ingénieusement étripé <b><?php echo $nb_kill ?></b> personnages.
</ul>
<br />

<ul>Les Anges ont : 
<br />
	<?php
		$resultat = getKillCount("", $time, $nb_jours, $croissant, "AND p1.race_id=3", "AND p2.race_id=4", "", "");
		$nb_kill  = mysql_fetch_array($resultat);
		$nb_kill  = $nb_kill[0];
		$nb_demons= $nb_kill;
		?>	
	<li>Amicalement crevé <b><?php echo $nb_kill ?></b> Démons.</li>
	<?php
		$resultat = getKillCount("", $time, $nb_jours, $croissant, "AND p1.race_id=3", "AND p2.race_id=3", "", "");
		$nb_kill  = mysql_fetch_array($resultat);
		$nb_kill  = $nb_kill[0];
		$nb_anges= $nb_kill;
		?>	
	<li>Tendrement tabassé <b><?php echo $nb_kill ?></b> des leurs.</li>
	<?php
		$nb_kill  = $nb_demons + $nb_anges;
		?>	
	<li>Finalement fait la peau à <b><?php echo $nb_kill ?></b> Ailés.</li>
	<?php
		$resultat = getKillCount("", $time, $nb_jours, $croissant, "AND p1.race_id=3", "AND p2.race_id=1", "", "");
		$nb_kill  = mysql_fetch_array($resultat);
		$nb_kill  = $nb_kill[0];
		$nb_humains= $nb_kill;
		?>	
	<li>Elégamment martyrisé <b><?php echo $nb_kill ?></b> Humains.</li>
	<?php
	$nb_kill  = $nb_demons + $nb_anges + $nb_humains;
		?>	
Au total les Anges ont frénétiquement étrillé <b><?php echo $nb_kill ?></b> personnages.
</ul>	
<br />
	
<ul>Les Humains ont : 
<br />
	<?php
		$resultat = getKillCount("", $time, $nb_jours, $croissant, "AND p1.race_id=1", "AND p2.race_id=4", "", "");
		$nb_kill  = mysql_fetch_array($resultat);
		$nb_kill  = $nb_kill[0];
		$nb_demons= $nb_kill;
		?>	
	<li>convenablement réglé le compte de <b><?php echo $nb_kill ?></b> Démons.</li>
	<?php
		$resultat = getKillCount("", $time, $nb_jours, $croissant, "AND p1.race_id=1", "AND p2.race_id=3", "", "");
		$nb_kill  = mysql_fetch_array($resultat);
		$nb_kill  = $nb_kill[0];
		$nb_anges= $nb_kill;
		?>	
	<li>agréablement plumé <b><?php echo $nb_kill ?></b> Anges.</li>
	<?php
		$nb_kill  = $nb_demons + $nb_anges;
		?>
	<li>bouté hors d'Althian <b><?php echo $nb_kill ?></b> Ailés.</li>
	<?php
		$resultat = getKillCount("", $time, $nb_jours, $croissant, "AND p1.race_id=1", "AND p2.race_id=1", "", "");
		$nb_kill  = mysql_fetch_array($resultat);
		$nb_kill  = $nb_kill[0];
		$nb_humains= $nb_kill;
		?>	
	<li>méticuleusement liquidé <b><?php echo $nb_kill ?></b> des leurs.</li>
	<?php
	$nb_kill  = $nb_demons + $nb_anges + $nb_humains;
		?>	
Au total les Humains ont fièrement décimé <b><?php echo $nb_kill ?></b> personnages.
</ul>
<br />

<ul>Les Ailés ont : 
<br />
	<?php
		$resultat = getKillCount("", $time, $nb_jours, $croissant, "AND (p1.race_id=3 OR p1.race_id=4)", "AND p2.race_id=4", "", "");
		$nb_kill  = mysql_fetch_array($resultat);
		$nb_kill  = $nb_kill[0];
		$nb_demons= $nb_kill;
		?>	
	<li>savaté <b><?php echo $nb_kill ?></b> Démons.<br/></li>	
	<?php
		$resultat = getKillCount("", $time, $nb_jours, $croissant, "AND (p1.race_id=3 OR p1.race_id=4)", "AND p2.race_id=3", "", "");
		$nb_kill  = mysql_fetch_array($resultat);
		$nb_kill  = $nb_kill[0];
		$nb_anges= $nb_kill;
		?>	
	<li>fraggé <b><?php echo $nb_kill ?></b> Anges.<br/></li>
	<?php
		$nb_kill  = $nb_demons + $nb_anges;
		?>
	<li>se sont jovialement infligé <b><?php echo $nb_kill ?></b> pertes.<br/></li>
	<?php
		$resultat = getKillCount("", $time, $nb_jours, $croissant, "AND (p1.race_id=3 OR p1.race_id=4)", "AND p2.race_id=1", "", "");
		$nb_kill  = mysql_fetch_array($resultat);
		$nb_kill  = $nb_kill[0];
		$nb_humains= $nb_kill;
		?>	
	<li>tué <b><?php echo $nb_kill ?></b> Humains.<br/></li>
	<?php
	$nb_kill  = $nb_demons + $nb_anges + $nb_humains;
		?>	
	<li>ont eu la main un peu lourde avec <b><?php echo $nb_kill ?></b> personnages.<br/></li>
<ul></ul>

		</td></tr>
		</table>
			<div align="center">
			</div>
</div>
<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
