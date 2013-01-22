<?php
/**
 * Faction - Affichage des traitres
 *
 * @author Anarion <anarion@ewo.fr>
 * @version 1.0
 * @package faction
 */

//-- Header --
$root_url = "..";
include($root_url."/template/header_new.php");

include($root_url."/persos/fonctions.php");

$utilisateur_id = $_SESSION['utilisateur']['id'];

include("./fonctions.php");

$perso_id = 0;

if (isset($_GET['perso_id']) && is_numeric($_GET['perso_id'])){
$inc=1;
while($inc<=$_SESSION['persos']['inc'] && $_GET['perso_id']!=$_SESSION['persos']['id'][$inc])
{
$inc++;
}
if($inc<=$_SESSION['persos']['inc']){
	$perso_id = mysql_real_escape_string($_GET['perso_id']);
	}
}

if (isset($_POST['perso_id']) && is_numeric($_POST['perso_id'])){
$inc=1;
while($inc<=$_SESSION['persos']['inc'] && $_POST['perso_id']!=$_SESSION['persos']['id'][$inc])
{
$inc++;
}
if($inc<=$_SESSION['persos']['inc']){
	$perso_id = mysql_real_escape_string($_POST['perso_id']);
	};
}

if(!isset($perso_id)){
	echo "<script language='javascript' type='text/javascript' >document.location='../'</script>";exit;
	}
$galon_grade = recup_race_grade($perso_id);

$galon = $galon_grade['galon_id'];
$grade = $galon_grade['grade_id'];

if(!($grade>=3 && $galon>=2)){
	if (isset($_POST['faction_id']) && is_numeric($_POST['faction_id'])){
	$faction_id = mysql_real_escape_string($_POST['faction_id']);
	$ok = 1;
	}


	if (isset($_GET['id']) && is_numeric($_GET['id'])){
	$faction_id = mysql_real_escape_string($_GET['id']);
	$ok = 1;
	}

	if(!is_membre($utilisateur_id, $faction_id)){
	echo "<script language='javascript' type='text/javascript' >document.location='../'</script>";exit;
	}


	if(member_faction_grade ($perso_id) == 4){
	echo "<script language='javascript' type='text/javascript' >document.location='../'</script>";exit;
	}


	if(faction_type ($faction_id) != 1){
	echo "<script language='javascript' type='text/javascript' >document.location='../'</script>";exit;
	}
}

if(isset($_POST['submit'])){
$victime_id=mysql_real_escape_string($_POST['victime_id']);
if(is_numeric($victime_id))	
	nvTraitre($victime_id);
}

?>
<div id="classement">

<h2>E.W.O. - Tra&icirc;tres potentiels d&eacute;c&eacute;d&eacute;s dans les 46h pr&eacute;c&eacute;dentes</h2>

<?php
			
			$time=time();
			$time=date('d-m-Y',$time);
			$time=strtotime($time);
			$time_m=date('Y-m-d H:i:s',$time-48*3600);
			
			$resultat = recup_pot_traitre($perso_id, $time_m);

		?>			
		<table align='center' id="tab_classement" BORDER='0px' CELLPADDING='0'>
			<tr>
				<td align="center" class='cla_td_titre large'>Nom (Mat.) du mort</td>
				<td align="center" class='cla_td_titre large'>Heure</td>
				<td align="center" class='cla_td_titre large'>Traitre !</td>
			</tr>
			<?php
			
				$n=4;
			
				while($event = mysql_fetch_array($resultat)){
				$perso_ok=false;
				$id_perso   = $event['id_perso'];	
				$id_victime = $event['mat_victime'];
				$sql="SELECT perso_id
						FROM damier_persos
							WHERE perso_id='$id_victime'";
						
					$resultat_ = mysql_query ($sql) or die (mysql_error());
				if(!mysql_fetch_array($resultat_)){
					$perso_ok=true;
					}
				
				$sql="SELECT faction_id
						FROM persos
							WHERE id='$id_victime' AND faction_id=0";
						
				$resultat_ = mysql_query ($sql) or die (mysql_error());
					
				if(mysql_fetch_array($resultat_)){
					$perso_ok=true;
					}else $perso_ok=false;
					
				if($perso_ok){
					$n++;
					$id_perso   = $event['id_perso'];						
					$nom        = $event['nom_victime'];
					$date_event	= $event['date'];
					$id_victime = $event['mat_victime'];
					$url 		= icone_persos($id_victime);
						
					$url_tueur 		= icone_persos($id_perso);
					
					if($n % 2){
						$color = 'row0';
					}else{
						$color = 'row1';
					}

						echo "<tr class='$color winner$n'>";
							echo "<td align='center'><img src='../images/$url' alt='avatar'/><br/>".$nom." [<a href='../event/liste_events.php?id=$id_victime'>$id_victime</a>]</td>";
							echo "<td align='center'>$date_event</td>";
							echo "<td align='center'>
							<form name='traitre' action='traitres.php' method='post'>
							<input type='hidden' name='victime_id' value='$id_victime' />
							<input type='hidden' name='perso_id' value='$perso_id' />";
							if(isset($faction_id))
								echo "<input type='hidden' name='faction_id' value='$faction_id' />";
							echo "<input type='submit' name='submit' value='Tra&icirc;tre !' class='bouton' />
							</form>
							</td>";
						echo "<tr>";
							}
				}
			?>
		</table>
</div>
<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
