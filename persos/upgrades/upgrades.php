<?php
//-- Header --
$root_url = "./../..";
include($root_url."/template/header_new.php");
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
if(isset($admin_mode))	{
	ControleAcces('admin',1);
	}
/*-----------------------------*/

$utilisateur_id = $_SESSION['utilisateur']['id'];

include($root_url."/persos/fonctions.php");
include($root_url."/jeu/fonctions.php");

if(!isset($_SESSION['persos']['current_id']) && !isset($admin_mode)){
	$titre = "Vous n'avez pas de personnage selectioné'";
	$text = "Vous devez passer par la page de jeu pour faire évoluer votre personnage.";
	$root = "./../..";
	$lien = "./../..";
	gestion_erreur($titre, $text, $root, $lien,1);
}

if(!isset($admin_mode))	{
// Détermine le personnage dont il est question.
	$perso_id = $_SESSION['persos']['current_id'];
	$id = $_SESSION['persos']['id'][0];
} else {
	$perso_id = 0;
	$id = 0;
}

if(!isset($admin_mode)){
	// Récupération de la race du personnage dans la session
	$race = $_SESSION['persos']['race'][$id];
	$camp = $_SESSION['persos']['camp'][$id];
	$affilie = ($_SESSION['persos']['superieur'][$id]!=0);

	// Récupération du grade du personnage dans la session
	$grade = $_SESSION['persos']['grade'][$id];
	$galon = $_SESSION['persos']['galon'][$id];
} else {
	$race = $admin_race;
	$grade = $admin_grade;
	$galon = $admin_galon;
	$camp = $admin_race;
	$affilie = true;
}

$donnees = calcul_caracs_no_alter($perso_id);

if(isset($_POST['choix_cercle']) && isset($_POST['cercle']) && $affilie && !$donnees['cercle']) {
	maj_carac($perso_id, "cercle", $_POST['cercle']);
	if(is_numeric($_POST['cercle'])) {
		$donnees['cercle'] = $_POST['cercle'];
	}
}

$caracs = caracs_base($race, $grade);
$bonus['pv'] = bonus_galon('pv', $race, $grade, $galon);
$bonus['force'] = bonus_galon('force', $race, $grade, $galon);
$bonus['pa'] = bonus_galon('pa', $race, $grade, $galon);
$bonus['des'] = bonus_galon('des', $race, $grade, $galon);
$bonus['res_mag'] = bonus_galon('res_mag', $race, $grade, $galon);

$recup_malus 		= recup_malus(carac_max($race, $grade, 'recup_pv', 0, $perso_id, $galon), carac_max($race, $grade, 'pv', 0, $perso_id, $galon));
$recup_malus		= $recup_malus["recup_fixe"];
$recup_malus_max 	= recup_malus(carac_max($race, $grade, 'recup_pv', $donnees['niv_recup_pv'], $perso_id, $galon), carac_max($race, $grade, 'pv', $donnees['niv_pv'], $perso_id, $galon));
$recup_malus_max	= $recup_malus_max["recup_fixe"];

if(!isset($admin_mode)) {
	$pi = $donnees['pi'];
} else {
	$pi = 1000000000;
}

include('valeurs.php');//coûts de base

$coutPv      = (1 +0.1*$donnees['niv_pv'])*$coutPvBase;
$coutRecupPv = (1 +0.1*$donnees['niv_recup_pv'])*$coutRecupPvBase;
$coutMouv    = (1 +0.1*$donnees['niv_mouv'])*$coutMouvBase;
$coutForce   = (1 +0.1*$donnees['niv_force'])*$coutForceBase;
$coutNvMag   = $coutNvMagBase+$addNvMag*$donnees['niv'];
$coutPerc    = (1 +0.1*$donnees['niv_perception'])*$coutPercBase;
$coutPa      = (1 +0.1*$donnees['niv_pa'])*$coutPaBase;
$coutDes     = (1 +0.1*$donnees['niv_des'])*$coutDesBase;

$techno_ok = false;
$cercle_ok = false;

if($race==1) {
	$count = 0;
	$reponse = mysql_query("SELECT cercle FROM caracs 
								INNER JOIN persos ON persos.id=caracs.perso_id
								WHERE persos.utilisateur_id=$utilisateur_id ")or die(mysql_error());
	while($rep_cercle = mysql_fetch_array($reponse)) {
		if($rep_cercle['cercle']==7)
			$count++;
	}
	$techno_ok=($count<2)?true:false;
	$cercle_ok=($count==2)?true:false;
}
?>
<link href="../../css/visualize.css" type="text/css" rel="stylesheet" /> 
<link href="../../css/visualize-light.css" type="text/css" rel="stylesheet" /> 
<div align='center'>
<?php
if (isset($admin_mode)) echo "<h2>Simulateur d'ewolution</h2>";
?>
<!-- Debut du coin -->
<br />
<div class="upperleft" id="coin_100">
	<div class="upperright">
		<div class="lowerleft">
			<div class="lowerright">
			<!-- conteneur -->

<?php
if(isset($admin_mode)) {
?>
	<form method="post">
	<select name="race">
	<option value="1" <?php echo ($race==1)? "selected='selected'" : "" ; ?>> Humain </option>
	<option value="2" <?php echo ($race==2)? "selected='selected'" : "" ; ?>> Paria </option>
	<option value="3" <?php echo ($race==3)? "selected='selected'" : "" ; ?>> Ange </option>
	<option value="4" <?php echo ($race==4)? "selected='selected'" : "" ; ?>> Demon </option>
	</select>
	<select name="grade">
	<option value="0" <?php echo ($grade==0)? "selected='selected'" : "" ; ?>> Grade 0 </option>
	<option value="3" <?php echo ($grade==3)? "selected='selected'" : "" ; ?>> Grade 3 </option>
	<option value="4" <?php echo ($grade==4)? "selected='selected'" : "" ; ?>> Grade 4 </option>
	<option value="5" <?php echo ($grade==5)? "selected='selected'" : "" ; ?>> Grade 5 </option>
	</select>
	<select name="galon">
	<option value="0" <?php echo ($galon==0)? "selected='selected'" : "" ; ?>> Galon 0 </option>
	<option value="1" <?php echo ($galon==1)? "selected='selected'" : "" ; ?>> Galon 1 </option>
	<option value="2" <?php echo ($galon==2)? "selected='selected'" : "" ; ?>> Galon 2 </option>
	<option value="3" <?php echo ($galon==3)? "selected='selected'" : "" ; ?>> Galon 3 </option>
	<option value="4" <?php echo ($galon==4)? "selected='selected'" : "" ; ?>> Galon 4 </option>
	</select>
	<input type="submit" name="Choix" value="Choisir race, grade et galon" />
	</form>
<?php
} else {
	echo "[<a href='$root_url/jeu/index.php?perso_id=$id'>Retour</a>]<br />";
}
?>
<table style="width:95%;margin:auto;border:1px solid #dad7b6;border-collapse:collapse;">
	<tr style="border-bottom:1px solid #dad7b6;">
		<td class="jeu_upgrades" colspan="5" style="padding:15px;text-align:center;">
			<?php if(!isset($admin_mode)) { ?>
				<span style="font-weight:bold;"><?php echo $_SESSION['persos']['nom'][$id];?></span> dispose actuellement de <span id="nbrPi"><?php echo $pi;?></span> <acronym title="Point d'Investissement">PI</acronym>
			<?php } else { ?>
				<span style="font-weight:bold;"> Co&ucirc;t total :  <span id="nbrPi">0</span> <acronym title="Point d'Investissement">PI</acronym>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<th width='20%'>Caracs</th>
		<th width='20%'>Valeur d'origine</th>
		<th width='20%'>Valeur actuelle</th>
		<th width='20%'>Co&ucirc;t de l'am&eacute;lioration suivante</th>
		<th width='20%'>&nbsp;</th>
	</tr>
	<tr>
		<td class="jeu_upgrades">PV</td>
		<td class="jeu_upgrades"><?php echo $caracs['pv']+$bonus['pv']; ?></td>
		<td class="jeu_upgrades"><span id="valPvAff"><?php echo carac_max($race, $grade, 'pv', $donnees['niv_pv'], $perso_id, $galon); ?></span></td>
		<td class="jeu_upgrades"><span id="coutPvAff"><?php echo $coutPv; ?></span></td>
		<td class="jeu_upgrades"> <input type="button" value="+" onclick="upCar(0);"/> </td>
	</tr>
	<tr>
		<td class="jeu_upgrades">R&eacute;cup'pv</td>
		<td class="jeu_upgrades"><?php echo $caracs['recup_pv']." %"; ?></td>
		<td class="jeu_upgrades"><?php
				echo '<span id="valRecupPvAff">'.carac_max($race,$grade, "recup_pv", $donnees['niv_recup_pv'], $perso_id, $galon).'</span> %';
			?></td>
		<td class="jeu_upgrades">
		<?php
			if($donnees['niv_recup_pv']<5)
				echo '<span id="coutRecupPvAff">',$coutRecupPv,'</span>';
			else
				echo 'N/A';
		?></td>
		<td class="jeu_upgrades"> <?php
			if($donnees['niv_recup_pv']<5)
				echo '<span id="upRecupPv"><input type="button" value="+" onclick="upCar(1);"/></span>';
			else
				echo '<span id="upRecupPv"></span>';
			?> </td>
	</tr>
	<tr>
		<td class="jeu_upgrades">R&eacute;cup'malus</td>
		<td class="jeu_upgrades"><?php echo $recup_malus; ?></td>
		<td class="jeu_upgrades"><?php echo $recup_malus_max; ?></td>
		<td class="jeu_upgrades" colspan="2"><?php echo "N/A" ?></td>
	</tr>
	<tr>
		<td class="jeu_upgrades">Mouv</td>
		<td class="jeu_upgrades"><?php echo $caracs['mouv']; ?></td>
		<td class="jeu_upgrades"><span id="valMouvAff"><?php echo carac_max($race, $grade, 'mouv', $donnees['niv_mouv'], $perso_id, $galon); ?></span></td>
		<td class="jeu_upgrades"><span id="coutMouvAff"><?php echo $coutMouv; ?></span></td>
		<td class="jeu_upgrades"> <input type="button" value="+" onclick="upCar(2);"/> </td>
	</tr>
	<tr>
		<td class="jeu_upgrades">PA <a href="#infoPa">*</a></td>
		<td class="jeu_upgrades"><?php echo $caracs['pa']+($bonus['pa']/10); ?></td>
		<td class="jeu_upgrades"><span id="valPaAff"><?php echo (carac_max($race, $grade, 'pa', $donnees['niv_pa'], $perso_id, $galon) + carac_max($race, $grade, 'pa_dec', $donnees['niv_pa'], $perso_id, $galon) / 10); ?></span></td>
		<td class="jeu_upgrades"><span id="coutPaAff"><?php echo $coutPa; ?></span></td>
		<td class="jeu_upgrades"> <input type="button" value="+" onclick="upCar(3);"/> </td>
	</tr>
	<tr>
		<td class="jeu_upgrades">Dext&eacute;rit&eacute;</td>
		<td class="jeu_upgrades"><?php echo $caracs['des']+$bonus['des']; ?></td>
		<td class="jeu_upgrades"><span id="valdesAff"><?php echo carac_max($race, $grade, 'des', $donnees['niv_des'], $perso_id, $galon); ?></span></td>
		<td class="jeu_upgrades"><span id="coutdesAff"><?php echo $coutDes; ?></span></td>
		<td class="jeu_upgrades"> <input type="button" value="+" onclick="upCar(4);"/> </td>
	</tr>
	<tr>
		<td class="jeu_upgrades">Force</td>
		<td class="jeu_upgrades"><?php echo $caracs['force']+$bonus['force']; ?></td>
		<td class="jeu_upgrades"><span id="valForceAff"><?php echo carac_max($race, $grade, 'force', $donnees['niv_force'], $perso_id, $galon); ?></span></td>
		<td class="jeu_upgrades"><span id="coutForceAff"><?php echo $coutForce; ?></span></td>
		<td class="jeu_upgrades"> <input type="button" value="+" onclick="upCar(5);"/> </td>
	</tr>
	<tr>
		<td class="jeu_upgrades">Perception</td>
		<td class="jeu_upgrades"><?php echo $caracs['perception']; ?></td>
		<td class="jeu_upgrades"><span id="valPercAff"><?php echo carac_max($race, $grade, 'perception', $donnees['niv_perception'], $perso_id, $galon); ?></span></td>
		<td class="jeu_upgrades"><span id="coutPercAff"><?php echo $coutPerc; ?></span></td>
		<td class="jeu_upgrades"> <input type="button" value="+" onclick="upCar(6);"/> </td>
	</tr>
	<?php
	if($donnees['cercle']!=0 || isset($admin_mode)){
		echo '
	<tr>
		<td class="jeu_upgrades">Niv de magie</td>
		<td class="jeu_upgrades">',$caracs['magie'],'</td>
		<td class="jeu_upgrades"><span id="valNvMagAff">',carac_max($race, $grade, 'magie', $donnees['niv'], $perso_id, $galon),'</span></td>
		<td class="jeu_upgrades">';
			if($donnees['niv']<5 && ($donnees['cercle']!=0 || isset($admin_mode)))
				echo '<span id="coutNvMagAff">',$coutNvMag,'</span></td>
		<td class="jeu_upgrades"> <span id="upNvMag"><input type="button" value="+" onclick="upCar(7);"/></span';
			else
				echo 'N/A</td>
		<td class="jeu_upgrades">';
		echo '
		 </td>
	</tr>';
		}
	echo '
	<tr>
		<td class="jeu_upgrades" colspan="3">
			&nbsp;		
		</td>
		<td class="jeu_upgrades" colspan="2">
			<input type="button" value="R&eacute;initialiser" onclick="clearEvo()"/> ';
			if(!isset($admin_mode)) 
			{ echo '<input type="button" value="Valider" onclick="submitEvo()"/>'; }
	echo '</td>
	</tr>';
	
if(!isset($admin_mode) && ($affilie || $race<3)){
	echo '
	<tr>
		<td class="jeu_upgrades" colspan="5" style="text-align:center;padding : 5px;">Cercle de magie : '; 
			if($donnees['cercle']!=0 || $cercle_ok){
				switch($donnees['cercle']){
					case 0 : 
						$cercle="Aucun";
						break;
					case 1 : 
						$cercle="Cercle de Feu";
						break;
					case 2 : 
						$cercle="Cercle de Glace";
						break;
					case 3 : 
						$cercle="Cercle de l'Espace-Temps";
						break;
					case 4 : 
						$cercle="Cercle de la Quiétude";
						break;
					case 5 : 
						$cercle="Cercle de l'Effroi";
						break;
					case 6 : 
						$cercle="Cercle du Désespoir";
						break;				
					case 7 : 
						$cercle="Cercle Technologique";
						break;				
					}
				echo $cercle;
				}
				else {
					echo '
						<form method="post">
						<select name="cercle">';
				if($race!=2 && $affilie){
					echo'
						<option value="1">Cercle de Feu</option>
						<option value="2">Cercle de Glace</option>
						<option value="3">Cercle de l\'Espace-Temps</option>
						<option value="4">Cercle de la Quiétude</option>
						<option value="5">Cercle de l\'Effroi</option>';
					if($race==1 && $techno_ok)
					echo '
						<option value="7">Cercle technologique</option>';
				}
				else if($race==1 && $techno_ok){
				echo'
						<option value="7">Cercle technologique</option>';
				
					}
				else echo '
						<option value="6">Cercle du Désespoir</option>';
				echo '
						</select>
					</td>
					<td>
						<input type="hidden" name="perso_id" value="',$perso_id ,'" />
						<input type="submit" name="choix_cercle" value="Choisir le cercle" />
						</form>';
		
		}
		echo '	
			</td>
		</tr>';
		}
		?>		
</table>

<br /><br />

<form method="post" action="upgradesencours.php" name="formUp">
	<input type="hidden" name="pvUp" value="0"/>
	<input type="hidden" name="recupPvUp" value="0"/>
	<input type="hidden" name="paUp" value="0"/>
	<input type="hidden" name="mouvUp" value="0"/>
	<input type="hidden" name="desUp" value="0"/>
	<input type="hidden" name="forceUp" value="0"/>
	<input type="hidden" name="percUp" value="0"/>
	<input type="hidden" name="nvMagUp" value="0"/>
	<input type="hidden" name="perso_id" value="<?php echo $perso_id ;?>" />
</form>
<script type="text/javascript">
	<!--
	<?php

        $js->addScript('upgrade');
        
	$recupPvBase = carac_max_no_galon($race, $grade, 'recup_pv', 0);
	$recupPvMult = 5;
	$pvBase      = carac_max_no_galon($race, $grade, 'pv',0);
	$pvMult      = $pvBase/10;

	$percMult    = 1;
	$percBase    = carac_max_no_galon($race, $grade, 'perception', 0);

	$forceBase   = carac_max_no_galon($race, $grade, 'force', 0);
	$forceMult   = $forceBase/10;

	$desBase     = carac_max_no_galon($race, $grade, 'des', 0);
	$desMult     = 1;

	$mouvBase    = carac_max_no_galon($race, $grade, 'mouv', 0);
	$mouvMult    = 1;

	$paBase = carac_max_no_galon($race, $grade, 'pa', 0);
	$paMult = 0.1;
	
		echo 'function upgradeDyn(){
	setPiV(',$pi,', ',((isset($admin_mode))?1:0),');
	setRace(',$race,');
	setGrade(',$grade,');

			var augPv = new Array();';
			$i = $donnees['niv_pv'];
			$r = 0;
			$f = 0;
			while($r <= $pi || $i == $donnees['niv_pv']){
				echo '
				augPv[',$i-$donnees['niv_pv'],'] = ',$coutPv,';';
				$i++;
				$r += $coutPv;
				$coutPv += 0.1*$coutPvBase;
				if($f == 1)
					$f++;
				else if($r > $pi)
					$f++;
			}
			echo '
	initCoutPv(augPv,',$donnees['niv_pv'],',',$pvBase+$bonus['pv'],',',$pvMult,');';
	
			echo '
			var augRecupPv = new Array();';
			$i = $donnees['niv_recup_pv']; 
			$r = 0;
			$f = 0;
			while($r <= $pi){
				echo '
				augRecupPv[',$i-$donnees['niv_recup_pv'],'] = ',$coutRecupPv,';';
				$i++;
				$r += $coutRecupPv;
				$coutRecupPv += 0.1*$coutRecupPvBase;

				if($f == 1)
					$f++;
				else if($r > $pi)
					$f++;
			}

			echo '
			initCoutRecupPv(augRecupPv,',$donnees['niv_recup_pv'],',',$recupPvBase,',',$recupPvMult,');';
		
			echo '
			var augMouv = new Array();';
			$i = $donnees['niv_mouv']; 
			$r = 0;
			$f = 0;
			while($r <= $pi || $i == $donnees['niv_mouv']){
				echo '
				augMouv[',$i - $donnees['niv_mouv'],'] = ',$coutMouv,';';
				$i++;
				$r += $coutMouv;
				$coutMouv += 0.1*$coutMouvBase;
				if($f == 1)
					$f++;
				else if($r > $pi)
					$f++;
			}
			echo '
			initCoutMouv(augMouv,',$donnees['niv_mouv'],',',$mouvBase,',',$mouvMult,');';

			echo '
			var augPa = new Array();';
			$i = $donnees['niv_pa']; 
			$r = 0;
			$f = 0;
			while($r <= $pi || $i == $donnees['niv_pa']){
				echo '
				augPa[',$i - $donnees['niv_pa'],'] = ',$coutPa,';';
				$i++;
				$r += $coutPa;
				$coutPa += 0.1*$coutPaBase;
				if($f == 1)
					$f++;
				else if($r > $pi)
					$f++;
			}
			echo '
			initCoutPa(augPa,',$donnees['niv_pa'],',',$paBase,',',$paMult,');';			
			
			echo '
			var augdes = new Array();';
			$i = $donnees['niv_des']; 
			$r = 0;
			$f = 0;
			while($f <= 1){
				echo '
				augdes[',$i - $donnees['niv_des'],'] = ',$coutDes,';';
				$i++;
				$r += $coutDes;
				$coutDes += 0.1*$coutDesBase;
				if($f == 1)
					$f++;
				else if($r > $pi)
					$f++;
			}
			echo '
			initCoutdes(augdes,',$donnees['niv_des'],',',$desBase+$bonus['des'],',',$desMult,');';
		
			echo '
			var augForce = new Array();';
			$i = $donnees['niv_force']; 
			$r = 0;
			$f = 0;
			while($f <= 1){
				echo '
				augForce[',$i - $donnees['niv_force'],'] = ',$coutForce,';';
				$i++;
				$r += $coutForce;
				$coutForce += 0.1*$coutForceBase;
				if($f == 1)
					$f++;
				else if($r > $pi)
					$f++;
			}
			echo '
			initCoutForce(augForce,',$donnees['niv_force'],',',$forceBase+$bonus['force'],',',$forceMult,');';
		
			echo '
			var augPerc = new Array();';
			$i = $donnees['niv_perception']; 
			$r = 0;
			$f = 0;
			while($f <= 1){
				echo '
				augPerc[',$i - $donnees['niv_perception'],'] = ',$coutPerc,';';
				$i++;
				$r += $coutPerc;
				$coutPerc += 0.1*$coutPercBase;
				if($f == 1)
					$f++;
				else if($r > $pi)
					$f++;
			}
			echo '
			initCoutPerc(augPerc,',$donnees['niv_perception'],',',$percBase,',',$percMult,');';
		
			echo '
			var augNvMag = new Array();';
			$i = $donnees['niv']; 
			$r = 0;
			$f = 0;
			while($f <= 1){
				echo '
				augNvMag[',$i - $donnees['niv'],'] = ',$i*$addNvMag+$coutNvMagBase,';';
				$i++;
				$r += ($i-1)*$addNvMag+$coutNvMagBase;
				if($f == 1)
					$f++;
				else if($r > $pi)
					$f++;
			}
			echo '
			initCoutNvMag(augNvMag,',$donnees['niv'],');';
	?>
	}
//-->
</script>


<table id="caracs" style="display: none;">
	<caption>Répartition de l'XP</caption>
	<thead>
		<th>Caractéristique</th>
		<th>Cout en XP</th>
	</thead>
	<tbody>
		<?php if($donnees['niv_pv'] != 0) {
			$xpTotalPv = calculeSommeXp($coutPvBase,$donnees['niv_pv']);
		?><tr>
			<th scope="row">PV - <?php echo $xpTotalPv; ?>xp</th>
			<td><?php echo $xpTotalPv; ?></td>
		</tr>
		<?php } 
		if($donnees['niv_recup_pv'] != 0) { 
			$xpTotalRecup = calculeSommeXp($coutRecupPvBase,$donnees['niv_recup_pv']);
		?><tr>
			<th scope="row">Récup. - <?php echo $xpTotalRecup; ?>xp</th>
			<td><?php echo $xpTotalRecup; ?></td>
		</tr>
		<?php } 
		if($donnees['niv_mouv'] != 0) { 
			$xpTotalMouv = calculeSommeXp($coutMouvBase,$donnees['niv_mouv']);
		?><tr>
			<th scope="row">Mouvements - <?php echo $xpTotalMouv; ?>xp</th>
			<td><?php echo $xpTotalMouv; ?></td>
		</tr>
		<?php } 
		if($donnees['niv_pa'] != 0) { 
			$entier = floor($donnees['niv_pa'] / 10);
			$fraction = $donnees['niv_pa'] % 10;
			
			$upentier = (($entier * ($entier+1)) / 2);
			
			$coutfraction = $fraction * (($upentier+1) * ($coutPaBase/10));

			$xpTotalPa = $coutfraction + ($upentier * $coutPaBase);		
		?><tr>
			<th scope="row">PA - <?php echo $xpTotalPa; ?>xp</th>
			<td><?php echo $xpTotalPa; ?></td>
		</tr>
		<?php } 
		if($donnees['niv_des'] != 0) { 
			$xpTotalDes = calculeSommeXp($coutDesBase,$donnees['niv_des']);
		?><tr>
			<th scope="row">Dés - <?php echo $xpTotalDes; ?>xp</th>
			<td><?php echo $xpTotalDes; ?></td>
		</tr>
		<?php } 
		if($donnees['niv_force'] != 0) { 
			$xpTotalForce = calculeSommeXp($coutForceBase,$donnees['niv_force']);
		?><tr>
			<th scope="row">Force - <?php echo $xpTotalForce; ?>xp</th>
			<td><?php echo $xpTotalForce; ?></td>
		</tr>
		<?php } 
		if($donnees['niv_perception'] != 0) { 
			$xpTotalPercept = calculeSommeXp($coutPercBase,$donnees['niv_perception']);
		?><tr>
			<th scope="row">Perception - <?php echo $xpTotalPercept; ?>xp</th>
			<td><?php echo $xpTotalPercept; ?></td>
		</tr>
		<?php } 
		if($donnees['niv'] != 0) { 
			$xpTotalMagie = calculeSommeXp($coutNvMagBase, $donnees['niv'], $addNvMag);
		?><tr>
			<th scope="row">Magie - <?php echo $xpTotalMagie; ?>xp</th>
			<td><?php echo $xpTotalMagie; ?></td>
		</tr><?php } ?>
	</tbody>
</table>

<div class="centrage">	
	[<a href='<?php echo $root_url; ?>/jeu/index.php?perso_id=<?php echo $id;?>'>Retour</a>]<br />
	<br />
	<br />
	<div id="infoPa">* Attention, les PA augmentent de 0.1 en 0.1</div>
</div>
			<!-- fin conteneur -->
			</div>
		</div>
	</div>
</div>
<!-- Fin du coin -->	

<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
