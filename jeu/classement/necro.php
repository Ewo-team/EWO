<?php
/**
 * Classement necro
 *
 * @author Ganesh <ganesh@ewo.fr>
 * @version 1.0
 * @package classement
 */
//-- Header --
require_once __DIR__ . '/../../conf/master.php';

$css_files = 'classement,listeperso';

include(SERVER_ROOT . "/template/header_new.php");

include(SERVER_ROOT . "/persos/fonctions.php");
include(SERVER_ROOT . "/jeu/classement/fonctions.php");

ControleAcces('utilisateur',1);

$id_utilisateur = $_SESSION['utilisateur']['id'];

if(!empty($_GET['page'])){
	$page = mysql_real_escape_string(max(1,$_GET['page']));
	} else $page=1;

if(!empty($_POST['nb_el'])){
	$nb_el = mysql_real_escape_string($_POST['nb_el']);
	}
	elseif(!empty($_GET['nb_el'])){
		$nb_el = mysql_real_escape_string($_GET['nb_el']);
		}
		else $nb_el=50;
		
if(!empty($_POST['nb_jours'])){
	$nb_jours = mysql_real_escape_string($_POST['nb_jours']);
	}
	elseif(!empty($_GET['nb_jours'])){
		$nb_jours = mysql_real_escape_string($_GET['nb_jours']);
		}
		else $nb_jours=2;

if (!empty($_POST['race'])){
	$race = mysql_real_escape_string($_POST['race']);
	}
	elseif (!empty($_GET['race'])){
		$race = mysql_real_escape_string($_GET['race']);
		}
		else $race=0;

if (isset($_POST['grade'])){
	$grade = mysql_real_escape_string($_POST['grade']);
	}
	elseif (isset($_GET['grade'])){
		$grade = mysql_real_escape_string($_GET['grade']);
		}
		else $grade=-2;
		
$where_grade="";
if($grade!=-2){
	if($grade==0){
		$where_grade="AND persos.grade_id>=0 AND persos.grade_id<=3";
		}else $where_grade="AND persos.grade_id=$grade";
	}
	
	
if (!empty($_POST['croissant'])){
	$croissant = mysql_real_escape_string($_POST['croissant']);
	}
	elseif (!empty($_GET['croissant'])){
		$croissant = mysql_real_escape_string($_GET['croissant']);
		}
		else $croissant="DESC";

if (!empty($_POST['jour'])){
	$time=strtotime(mysql_real_escape_string($_POST['jour']));
	$time_m=date('d-m-Y',$time-24*3600);
	$time_p=date('d-m-Y',$time+24*3600);
	$time=date('d-m-Y',$time);			
	}
	elseif (!empty($_GET['jour'])){
		$time=strtotime(mysql_real_escape_string($_GET['jour']));
		$time_m=date('d-m-Y',$time-24*3600);
		$time_p=date('d-m-Y',$time+24*3600);
		$time=date('d-m-Y',$time);
		}
		else {
			$time=time();
			$time=date('d-m-Y',$time);
			$time=strtotime($time);
			$time_m=date('d-m-Y',$time-24*3600);
			$time_p=date('d-m-Y',$time+24*3600);
			$time=date('d-m-Y',$time);
			}
	
switch($race){
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

<h2>E.W.O. - Mausolée <?php echo $type; ?></h2>

<div align="center">
	<form method="post" action="necro.php">
	<select id='race' name="race">
		<option value="0" <?php if($race==0){echo 'selected="selected"';}?>>Choix de la race :</option>
		<option value="0" >Toutes les races</option>
		<option value="1" <?php if($race==1){echo 'selected="selected"';}?>>Humains</option>
		<option value="2" <?php if($race==2){echo 'selected="selected"';}?>>Parias</option>
		<option value="3" <?php if($race==3){echo 'selected="selected"';}?>>Anges</option>
		<option value="4" <?php if($race==4){echo 'selected="selected"';}?>>D&eacute;mons</option>
		<option value="-1" <?php if($race==-1){echo 'selected="selected"';}?>>Anges et D&eacute;mons</option>
	</select>	
	<select id='grade' name="grade">
		<option value="-2" <?php if($grade==-2){echo 'selected="selected"';}?>>Choix du grade :</option>
		<option value="-2" >Tous les grades</option>
		<option value="0" <?php if($grade==0){echo 'selected="selected"';}?>>Grade 0 à 3</option>
		<option value="4" <?php if($grade==4){echo 'selected="selected"';}?>>Grade 4</option>
		<option value="5" <?php if($grade==5){echo 'selected="selected"';}?>>Grade 5</option>
		<option value="-1" <?php if($grade==-1){echo 'selected="selected"';}?>>Tricheurs</option>
	</select>

	<select id="croissant" name="croissant">
			<option value="DESC" <?php if($croissant=="DESC"){echo 'selected="selected"';}?>>Ordre :</option>
			<option value="DESC" >Croissant</option>
		  <option value="ASC" <?php if($croissant=="ASC"){echo 'selected="selected"';}?>>D&eacute;croissant</option>
	</select>

	<select id="nb_el" name="nb_el">
			<option value="50" <?php if($nb_el==50){echo 'selected="selected"';}?>>Personnages par page : </option>
		  <option value="25" <?php if($nb_el==25){echo 'selected="selected"';}?>>25</option>
		  <option value="50" >50</option>
		  <option value="100" <?php if($nb_el==100){echo 'selected="selected"';}?>>100</option>
		  <option value="150" <?php if($nb_el==150){echo 'selected="selected"';}?>>150</option>
	</select>
	<select id="nb_jours" name="nb_jours">
			<option value="2" <?php if($nb_jours==2){echo 'selected="selected"';}?>>Nombre de jours : </option>
		  <option value="1" <?php if($nb_jours==1){echo 'selected="selected"';}?>>1</option>
		  <option value="2" >2</option>
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
			$JourProf = date('Y-m-d H:i:s', $JourProf);
			//Calcul du nombre de pages max
			$page_max=1;
			$sql="SELECT COUNT(morgue.id) AS nb
					FROM morgue
						JOIN persos ON persos.id=morgue.id_perso
							WHERE ((morgue.date>='$JourProf') AND (morgue.date<'$Jour') $where_race $where_grade)";
			$resultat = mysql_query ($sql) or die (mysql_error());
			$resultat = mysql_fetch_array($resultat);
			//echo $resultat['nb'];
			$page_max = $resultat['nb']/$nb_el+0.499;
			$page_max = round($page_max);
			
		?>
		<div align="center">
		<?php 	echo "<a href='".SERVER_URL."/jeu/classement/necro.php?croissant=$croissant&race=$race&nb_el=$nb_el&grade=$grade&jour=".$time_m."&nb_jours=$nb_jours'><=</a>";
							echo $time;
				echo "<a href='".SERVER_URL."/jeu/classement/necro.php?croissant=$croissant&race=$race&nb_el=$nb_el&grade=$grade&jour=".$time_p."&nb_jours=$nb_jours'>=></a><br/>";
							
				if($page!=1){ 
							echo "<a href='".SERVER_URL."/jeu/classement/necro.php?page=".max(1,$page-1)."&croissant=$croissant&race=$race&nb_el=$nb_el&grade=$grade&jour=$time&nb_jours=$nb_jours'><</a>";
							};
				if($page_max!=0){
					echo " Page ".$page." ";
					};
				if($page_max!=$page && $page_max!=0){
							echo "<a href='".SERVER_URL."/jeu/classement/necro.php?page=".min($page_max,$page+1)."&croissant=$croissant&race=$race&nb_el=$nb_el&grade=$grade&jour=$time&nb_jours=$nb_jours'>></a>"; 
							}
		?>				
		</div>
		<?php
			//Affichage de la nième page
			$lim=(min($page, $page_max+1)-1)*$nb_el;
			$resultat = get_mort_event("", $time, $nb_jours, $croissant, $where_race, $where_grade, "LIMIT $lim,$nb_el")

		?>			
		<table align='center' id="tab_classement" BORDER='0px' CELLPADDING='0'>
			<tr>
				<td align="center" class='cla_td_titre small'>Type de mort</td>
				<td align="center" class='cla_td_titre large'>Nom (Mat.) du mort</td>
				<td align="center" class='cla_td_titre large'>Nom (Mat.) du tueur</td>
				<td align="center" class='cla_td_titre large'>Heure</td>
			</tr>
			<?php
			
				$n=4;
			
				while($event = mysql_fetch_array($resultat)){
				$n++;
				$id         = $event['id'];
				$type       = $event['type'];
				$id_perso   = $event['id_perso'];						
				$nom        = $event['nom_victime'];
				$date_event	= $event['date'];
				$id_victime = $event['mat_victime'];
				$url 		= icone_persos($id_victime);
				switch($type){
					case 1:
						$type = "Sort";
						break;
					default:	//attaque par defaut
						$type = "Attaque";
						break;
					}
					
				$url_tueur 		= icone_persos($id_perso);
				
				if($n % 2){
					$color = 'row0';
				}else{
					$color = 'row1';
				}

					echo "<tr class='$color winner$n'>";
						echo "<td align='center'>".$type."</td>
									<td align='center'><img src='".SERVER_URL."/images/$url' alt='avatar'/><br/>".$nom." [<a href='".SERVER_URL."/persos/event/?id=$id_victime'>$id_victime</a>]</td>";
									if($type!='Destruction'){
									echo "<td align='center'><img src='".SERVER_URL."/images/$url_tueur' alt='avatar'/><br/>".nom_perso($id_perso)." [<a href='".SERVER_URL."/persos/event/?id=$id_perso'>$id_perso</a>]</td>";
									}else {
										echo "<td align='center'>".nom_cible($id_perso,'',true)."</td>";
										}
						echo		"<td align='center'>$date_event</td>";
					echo "<tr>";
						}
			?>
		</table>
			<div align="center">
		<?php 	if($page!=1){ 
							echo "<a href='".SERVER_URL."/jeu/classement/necro.php?page=".max(1,$page-1)."&croissant=$croissant&race=$race&nb_el=$nb_el'><</a>";
							};
				if($page_max!=0){
					echo " Page ".$page." ";
					};
				if($page_max!=$page && $page_max!=0){
							echo "<a href='".SERVER_URL."/jeu/classement/necro.php?page=".min($page_max,$page+1)."&croissant=$croissant&race=$race&nb_el=$nb_el'>></a>"; 
							}
		?>					
			</div>
</div>
<?php
//-- Footer --
include(SERVER_ROOT."/template/footer_new.php");
//------------
?>
