<?php

namespace jeu\classement;

/**
 * Classement vue
 *
 * @author Ganesh <ganesh@ewo.fr>
 * @version 1.0
 * @package classement
 * @category vue
 */
//-- Header --

function afficheSelectionClassement($param) {

global $js;

$js->addScript('classements');
    
?>
<div id="classement">

<h2>E.W.O. - Classement <?php echo $param['type']; ?></h2>

<div align="center">
	<form method="post" action=".">
	<label for='classement_type'>Type de classement : </label>
	<select id="classement_type" name="classement_type">
		  <option value="xp" <?php if($param['classement_type']=="xp"){echo 'selected="selected"';}?>>Chuck Norris (Classement par l'XP)</option>
		  <option value="meurtre" <?php if($param['classement_type']=="meurtre"){echo 'selected="selected"';}?>>Kaizer Sosey (Classement des tueurs)</option>
		  <option value="mort" <?php if($param['classement_type']=="mort"){echo 'selected="selected"';}?>>Oh mon dieu ! Ils ont tu&eacute; Kenny ! (Classement des tu&eacute;s)</option>
		  <option value="cv" <?php if($param['classement_type']=="cv"){echo 'selected="selected"';}?>>C'&eacute;tait pas ma guerre! (Fait d'armes)</option>
		  <option value="survie" <?php if($param['classement_type']=="survie"){echo 'selected="selected"';}?>>Il n'en restera qu'un (Classement Survivor)</option>
		  <option value="famille" <?php if($param['classement_type']=="famille"){echo 'selected="selected"';}?>>Histoire de famille (XP moyenne par familles)</option>
	</select><br/>
	<?php 
		afficheNavigationTemporelle($param);
	?>	
	<br/>

	<select id='race' name="race">
			<option value="0" <?php if($param['race']==0){echo 'selected="selected"';}?>>Choix de la race race :</option>
		  <option value="0" >Toutes les races</option>
		  <option value="1" <?php if($param['race']==1){echo 'selected="selected"';}?>>Humains</option>
		  <option value="2" <?php if($param['race']==2){echo 'selected="selected"';}?>>Parias</option>
		  <option value="3" <?php if($param['race']==3){echo 'selected="selected"';}?>>Anges</option>
		  <option value="4" <?php if($param['race']==4){echo 'selected="selected"';}?>>D&eacute;mons</option>
		  <option value="-1" <?php if($param['race']==-1){echo 'selected="selected"';}?>>Anges et D&eacute;mons</option>
	</select>

	<select id='grade' name="grade_ord">
			<option value="0" <?php if($param['grade_ord']==0){echo 'selected="selected"';}?>>Classement par Grade :</option>
		  <option value="0" >Non</option>
		  <option value="1" <?php if($param['grade_ord']==1){echo 'selected="selected"';}?>>Oui, seulement le Grade</option>
		  <option value="2" <?php if($param['grade_ord']==2){echo 'selected="selected"';}?>>Oui, Grade et galon</option>
	</select>

	<select id="nb_el" name="nb_el">
			<option value="50" <?php if($param['nb_el']==50){echo 'selected="selected"';}?>>Personnages par page : </option>
		  <option value="25" <?php if($param['nb_el']==25){echo 'selected="selected"';}?>>25</option>
		  <option value="50" >50</option>
		  <option value="100" <?php if($param['nb_el']==100){echo 'selected="selected"';}?>>100</option>
		  <option value="150" <?php if($param['nb_el']==150){echo 'selected="selected"';}?>>150</option>
	</select>
	<input type="hidden" name="perso_id" value="<?php echo $param['id']; ?>" />
	<input type="submit" value="Filtrer" /> <br/><br/>
	</form>
</div> <?php if(isset($param['afficheRecherche'])) { ?>
	<div align="center">
	<form method="post" action=".">
	<label for='search'>Rechercher un personnage dans le classement (matricule ou pseudo) : </label>
	<input type="hidden" name="classement_type" value="<?php echo $param['classement_type']; ?>">
	<input type="text" name="search" size="13" />
	<input type="submit" value="Rechercher" />
	</form>
	</div>
<?php
	}
}
		
function afficheNavigationClassement($param) {		
	
	echo '<div align="center">';
		
	if($param['page']!=1) { 
		echo "<a href='",SERVER_URL,"/jeu/classement/?perso_id=0&page=1&classement_type=",$param['classement_type'],"&grade_ord=",$param['grade_ord'],"&race=",$param['race'],"&nb_el=",$param['nb_el'],"&date=",date("d-m-Y", $param['timestamp']),"'><<</a> ";
		echo "<a href='",SERVER_URL,"/jeu/classement/?perso_id=0&page=",max(1,$param['page']-1),"&classement_type=",$param['classement_type'],"&grade_ord=",$param['grade_ord'],"&race=",$param['race'],"&nb_el=",$param['nb_el'],"&date=",date("d-m-Y", $param['timestamp']),"'><</a>";
	}
	
	if($param['page_max']!=0) {
		echo " Page ".$param['page']." ";
	}
	
	if($param['page_max']!=$param['page'] && $param['page_max']!=0){
		echo "<a href='",SERVER_URL,"/jeu/classement/?perso_id=0&page=",min($param['page_max'],$param['page']+1),"&classement_type=",$param['classement_type'],"&grade_ord=",$param['grade_ord'],"&race=",$param['race'],"&nb_el=",$param['nb_el'],"&date=",date("d-m-Y", $param['timestamp']),"'>></a> "; 
		echo "<a href='",SERVER_URL,"/jeu/classement/?perso_id=0&page=",$param['page_max'],"&classement_type=",$param['classement_type'],"&grade_ord=",$param['grade_ord'],"&race=",$param['race'],"&nb_el=",$param['nb_el'],"&date=",date("d-m-Y", $param['timestamp']),"'>>></a> "; 
	}
					
	echo '</div>';
}

function afficheNavigationTemporelle($param) {

	$passe = date("d-m-Y", $param['timestamp'] - 86400);
	$futur = date("d-m-Y", $param['timestamp'] + 86400);
		
	if($param['timestamp'] > $param['first_date']) {
		echo "<a href='",SERVER_URL,"/jeu/classement/?perso_id=0&page=",$param['page'],"&classement_type=",$param['classement_type'],"&grade_ord=",$param['grade_ord'],"&race=",$param['race'],"&nb_el=",$param['nb_el'],"&date=",$passe,"'>Time Travel!</a> - ";
	}
		
	echo "Classement du ",date("d-m-Y", $param['timestamp']);			
		
	if(!$param['aujourdhui']) {
		echo " - <a href='",SERVER_URL,"/jeu/classement/?perso_id=0&page=",$param['page'],"&classement_type=",$param['classement_type'],"&grade_ord=",$param['grade_ord'],"&race=",$param['race'],"&nb_el=",$param['nb_el'],"&date=",$futur,"'>Back to the Futur!</a>";	
	}
}

function afficheListeClassement($tableau, $param) {
		
		
		?>			
		<table align='center' id="tab_classement" BORDER='0px' CELLPADDING='0'>
			<tr>
				<td align="center" class='cla_td_titre small'>Position</td>
				<td align="center" class='cla_td_titre large'>Nom (Mat.)</td>
				<td align="center" class='cla_td_titre large'>Race</td>
				<?php if(isset($param['afficheGrade'])) { ?><td align="center" class='cla_td_titre large'>Grade</td><?php } ?>
				<?php if(isset($param['afficheGalon'])) { ?><td align="center" class='cla_td_titre large'>Galon</td><?php } ?>
				<?php if(isset($param['afficheXp']) || isset($param['afficheXpFamille'])) { ?><td align="center" class='cla_td_titre large'>Experience</td><?php } ?>
				<?php if(isset($param['afficheMeurtre'])) { ?><td align="center" class='cla_td_titre large'>Meurtre</td><?php } ?>
				<?php if(isset($param['afficheMort'])) { ?><td align="center" class='cla_td_titre large'>Mort</td><?php } ?>
				<?php if(isset($param['afficheCv'])) { ?><td align="center" class='cla_td_titre large'>Efficacit&eacute;e</td><?php } ?>
				<?php if(isset($param['afficheDateMort'])) { ?><td align="center" class='cla_td_titre large'>Derni&egrave;re mort</td><?php } ?>
			</tr>
			<?php
			
				
				$lim = $param['startposition'];
				if($param['page']==1){
				$n=0;
				}else {
					$n=4;
					}
				foreach($tableau as $rep) {	
				//while($rep = mysql_fetch_array($resultat)){
				$n++;
					if($n % 2){
						$color = 'row0';
					}else{
						$color = 'row1';
					}
				if($param['page']==1){
					$pos=$lim+$n;
					}else $pos=$lim+$n-4;
					
					if($rep['mat']==@$param['highlight']) {
						echo "<tr class='highlight'>";
					}
					else {
						echo "<tr class='$color winner$n'>";
					}
                                        $race = nom_race($rep['race'], $rep['nom_race']);
						echo "<td align='center'>".($pos)."</td>
									<td class='cla_td_nom'><a name='perso".$rep['mat']."' href='".SERVER_URL."/persos/event/?id=".$rep['mat']."'>".$rep['pseudo']." (".$rep['mat'].")</a></td>
						<td align='center'>".$race."</td>";
						if(isset($param['afficheGrade'])) { echo '<td align="center">'.$rep['grade'].'</td>'; }
						if(isset($param['afficheGalon'])) { echo '<td align="center">'.$rep['galon'].'</td>'; }
						if(isset($param['afficheXp'])) { echo "<td class='cla_td_exp'>".round($rep['xp'])."</td>"; }
						if(isset($param['afficheXpFamille'])) { echo "<td class='cla_td_exp'>".round($rep['px'])."</td>"; }
						if(isset($param['afficheMeurtre'])) { echo '<td align="center">'.$rep['meurtre'].'</td>'; }
						if(isset($param['afficheMort'])) { echo '<td align="center">'.$rep['mort'].'</td>'; }

						if(isset($param['afficheCv'])) { 
							if($rep['mort'] != 0) {
								$ratio = round(100 - (($rep['mort'] / ($rep['mort']+$rep['meurtre']))*100),1);
							} else {
								if($rep['meurtre'] == 0) {
									$ratio = 0;
								} else {
									$ratio = 100;
								}
							}
							echo '<td align="center">'.$ratio.'%</td>'; 
						}
						if(isset($param['afficheDateMort'])) {
							if($rep['datemort'] == null) {
								echo '<td align="center">Jamais!</td>';								
							} else {
								$nbJour = round((($param['timestamp']+86399) - strtotime($rep['datemort']))/(60*60*24)-1)+1;
								if($nbJour == 1) {
									echo '<td align="center">'.$nbJour.' jour</td>'; 
								} elseif($nbJour == 0) {
									echo '<td align="center">Aujourd\'hui!</td>'; 
								} else {
									echo '<td align="center">'.$nbJour.' jours</td>'; 
								}
							}
						}
						
					echo "<tr>";
						}
			?>
		</table>
			<div align="center">
		<?php 	
}

//------------
?>
