<?php
/**
 * Classement record
 *
 * @author Ganesh <ganesh@ewo.fr>
 * @version 1.0
 * @package classement
 */
//-- Header --
$root_url = "..";
include($root_url."/template/header_new.php");

include($root_url."/persos/fonctions.php");

ControleAcces('utilisateur',1);

$id_utilisateur = $_SESSION['utilisateur']['id'];

?>
<div id="classement">

<h2>E.W.O. - Tableau des records</h2>

		<table align='center' id="tab_classement" BORDER='0px' CELLPADDING='0'>
			<tr>
				<td align="center" class='cla_td_titre large'>Type de record</td>
				<td align="center" class='cla_td_titre large'>Nom (Mat.) du realisateur du record</td>
				<td align="center" class='cla_td_titre large'>Valeur</td>
				<td align="center" class='cla_td_titre large'>Date du record</td>
			</tr>
			<?php
			
				$n=4;
				$res = recup_all_record();
				$record_duree=array();
				while($resultat = mysql_fetch_array($res)){
					$type  		= $resultat['type'];
					$perso_id  	= $resultat['perso_id'];
					$valeur  	= unseritab($resultat['valeur']);
					$url 		= icone_persos($perso_id);
				if($type != "Dur&eacute;e_courante" && $type!="Dur&eacute;e_max"){
					if($n % 2){
							$color = 'row0';
						}else{
							$color = 'row1';
						}
						echo "<tr class='$color winner$n'>";
							if($type=="Profondeur"){
								switch($valeur['plan']){
									case 2 :
										$type = "Profondeur en enfer";
										break;
									case 3 :
										$type = "Hauteur au paradis";
										break;
									default :
									}
								};
							echo "<td align='center'>".$type."</td>
										<td align='center'><img src='../images/$url' alt='avatar'/><br/>".nom_perso($perso_id,true)."</td>";
							echo		"<td align='center'>".$valeur['val']."</td>";
							echo		"<td align='center'>".date('d-m-Y H:i:s',$valeur['date'])."</td>";
						echo "<tr>";
						}else{
							$record_duree[$type][$perso_id]=$valeur;
							}
				}
				$record_max['perso_id'] = 0;
				$record_max['val']		= 0;
				$record_max['date']		= 0;
				if(isset($record_duree["Dur&eacute;e_max"])){
					foreach($record_duree["Dur&eacute;e_max"] as $key => $value){
						if($value['val']>$record_max['val']){
							$record_max['perso_id'] = $key;
							$record_max['val']		= $value['val'];
							$record_max['date']		= $value['date'];
							$record_max['plan']		= $value['plan'];
							}
						}
					}
				if($record_max['perso_id'] != 0){
				$url 	= 	icone_persos($record_max['perso_id']) ;
				if($n % 2){
							$color = 'row0';
						}else{
							$color = 'row1';
						}
						$sql="SELECT nom FROM cartes WHERE id=".$record_max['plan'];
						$rep=mysql_query($sql) or die(mysql_error());
						$pplan=mysql_fetch_array($rep);
						echo "<tr class='$color winner$n'>";
							echo "<td align='center'>Temps de survie en plan ennemi</td>
										<td align='center'><img src='../images/$url' alt='avatar'/><br/>".nom_perso($record_max['perso_id'],true)."</td>";
							echo		"<td align='center'>".$record_max['val']." (".$pplan['nom'].")"."</td>";
							echo		"<td align='center'>".date('d-m-Y H:i:s',$record_max['date'])."</td>";
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
