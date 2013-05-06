<?php
$sql="SELECT maj_des, maj_esq_mag FROM caracs WHERE perso_id=$perso_id";
$reponse=mysql_query($sql) or die(mysql_error());
$maj_des=mysql_fetch_array($reponse);
$maj_esq_mag=$maj_des['maj_esq_mag'];
$maj_des=$maj_des['maj_des'];

$disabled = "disabled='true'";
if(!$maj_des[0]){
	$disabled = "";
	}

$plan = 0; // Valeur par défaut
$nv_tour = 23; // Valeur par défaut
$sql="SELECT * FROM damier_persos 
		INNER join cartes ON cartes.id=damier_persos.carte_id
			WHERE perso_id='$perso_id'";
	$resultat = mysql_query ($sql) or die (mysql_error());
	if($pos = mysql_fetch_array ($resultat)){
		$plan = $pos['carte_id'];
		$nv_tour = $pos['dla'];
		}

$sql = "SELECT date_tour, date_esquivemagique FROM persos WHERE id = '".$perso_id."'";
$resultat = mysql_query ($sql) or die (mysql_error());
$persos = mysql_fetch_array ($resultat);

$datetour = $persos['date_tour'];
$datechangement = strtotime($persos['date_esquivemagique']);
$datetour = strtotime($datetour);

$time = time();

$nouveautour = $time + 3600*$nv_tour;

$esq_disabled = "disabled='true'";	
//echo "$time<br>$datechangement<br>".($datechangement+28800);
$temps_esquivemagique = "</td></tr><tr><td colspan='5'>Pas de changement avant ".date('G\hi \m\i\n', ($datechangement + 3600*11)-$time);


if($time >= ($datechangement + 3600*11)){

	//if((($maj_esq_mag==2 || $maj_esq_mag==0) &&  $time >= ($datetour - 5*$nv_tour*3600/6)) || (($maj_esq_mag==2 || $maj_esq_mag==0) && $time <= ($datetour - $nv_tour*3600/6) )){
		$esq_disabled = "";
		$temps_esquivemagique = "";
	//	}
}

?>

<!-- Debut contour -->
<div class="block conteneur" id="block-3">
<div class='conteneur_titre'>Actions</div>
<!-- conteneur -->
<?php

// if($time >= ($datetour - 7*$nv_tour*3600/8))
	// echo "bouh";
// if($time >= ($datetour - 7*$nv_tour*3600/8) && $time <= ($datetour - $nv_tour*3600/8) ){
// echo "bouh";
	// if((($maj_esq_mag==2 || $maj_esq_mag==0) &&  $time >= ($datetour - 7*$nv_tour*3600/8)) || (($maj_esq_mag==2 || $maj_esq_mag==0) && $time <= ($datetour - $nv_tour*3600/8) )){
		// $esq_disabled = "";
		// }
// }
        include 'listes_actions.php';
?>
	<div class="maj_ok" style="display:none" id="fonction_des">Force d'attaque mise &agrave; jour</div>
	<!--<div class="maj_ok" style="display:none" id="fonction_esq_mag">Esquive TechnoMagique mise &agrave; jour</div>-->
	<form method='post' name='gestion_des'>
	<table width='100%'>	
                <tr>
                    <td colspan="2" style="text-align: right"><u>Attaque</u></td>
                    <td colspan="2"><u>Défense</u></td>
                </tr>
                <tr>
                    <td style="text-align: right">(<?php echo $caracs['att']; ?>)</td>
                    <td width='55' style="text-align: right"><input name='des_attaque' type='text' value="<?php echo $caracs['des_attaque']; ?>" size='1' readonly/></td>
                    <td width='55'><input name='des_defense' type='text' value="<?php echo $caracs_max['des']-$caracs['des_attaque']; ?>" size='1' readonly/></td>
                    <td>(<?php echo $caracs['def']; ?>)</td>
                </tr> 
                <tr>
                    <td style="text-align: right"><input name='maj_des' type='button' value='++' class='bouton' onClick='des_max();' /></td>
                    <td><input name='maj_des' type='button' value='+' class='bouton' onClick='des_plus();' /></td>
                    <td style="text-align: right"><input name='maj_des' type='button' value='+' class='bouton' onClick='des_moins();' /></td>
                    <td><input name='maj_des' type='button' value='++' class='bouton' onClick='des_min();'  /></td>
                </tr>                 
		<!--<tr>
                    <td colspan="4" align='center'><u>Esquive TechnoMagique</u></td>
                </tr>   
                <tr>   
                    <td colspan='4' align='center'><?php if(!$maj_esq_mag || $maj_esq_mag==3){
				echo "Défense maximale"; 
				} else {
				echo "Défense nulle"; 				
					}?></td>
		</tr>--> 
		<tr>
                        <td colspan='4' align='center'><input <?php echo $disabled; ?> id='modifier_des' name='modifier_des' type='button' value='Modifier les dés' class='bouton' onClick='des_modifier( <?php echo $perso_id; ?>,<?php echo $caracs_max['des']; ?>, document.gestion_des.des_attaque.value);' /></td>
			<!--<td colspan='2' align='center'><input <?php echo $esq_disabled; ?> id='modifier_esq_mag' name='modifier_esq_mag' type='button' value="Modifier l'Esq. TM" class='bouton' onClick='esq_mag_modifier( <?php echo $perso_id; ?>);' /><?php echo $temps_esquivemagique; ?></td>-->
		</tr>	
	 </table>
	 </form>
<!-- fin conteneur -->
</div>
<!-- Fin contour -->	
<div class='separation'></div>
