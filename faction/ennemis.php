<?php
/**
 * Faction - Profondeur des ennemies dans le plan
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


if(faction_type ($faction_id) != 2){
echo "<script language='javascript' type='text/javascript' >document.location='../'</script>";exit;
}


?>
<div id="classement">

<h2>E.W.O. - Profondeur des ennemis dans votre plan</h2>

		<table align='center' id="tab_classement" BORDER='0px' CELLPADDING='0'>
			<tr>
				<td align="center" class='cla_td_titre large'>Nom (Mat.) de l'ennemi</td>
				<td align="center" class='cla_td_titre large'>Profondeur</td>
			</tr>
			<?php
			
				$n=4;
				$res = recup_ennemis_prof($faction_id);
				while($resultat = mysql_fetch_array($res)){
				if($n % 2){
						$color = 'row0';
					}else{
						$color = 'row1';
					}
				$perso_id  	= $resultat['perso_id'];
				$pos_y  	= $resultat['pos_y'];
				$url 		= icone_persos($perso_id);
					echo "<tr class='$color winner$n'>";

						echo "<td align='center'><img src='../images/$url' alt='avatar'/><br/>".nom_perso($perso_id,true)."</td>";
						echo "<td align='center'>".$pos_y."</td>";
					echo "<tr>";
						}
			?>
		</table>
</div>
<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
