<?php
/*
@ Interactions
*/
$race = $_SESSION['persos']['race'][$id];
if ($race>4){
			$sql = "SELECT camp_id FROM `races` WHERE race_id=$race LIMIT 1 ";
			$use_race = mysql_query($sql) or die(mysql_error());
			$use_race = mysql_fetch_array($use_race);
			$race = $use_race['camp_id'];
			}
switch($race){
    case 1 :
         $race_im="humain";
        break;
    case 3 :
         $race_im="ange";
        break;
    case 4 :
         $race_im="demon";
        break;
    default :
         $race_im="humain";
        break;
    }
?>

<!-- Debut contour -->
<div class="block conteneur" id="block-1">
<div class='conteneur_titre'><span>Mouvements</span></div>
<!-- conteneur -->
	<div class="deplacement" align='center'>
		<table CELLPADDING='0' CELLSPACING='0' border='0'>
			<tr>
				<td>
					<img usemap="#rose" src="./../images/cartes/roses/rose_<?php echo $race_im; ?>.png" border=0 width=150 height=150>
					<map name="rose" id="rose">
					   <area shape="circle" alt="Centre"coords="74,73,12" href="#"/>
					   <area shape="poly" alt="Nord-Ouest" coords="60,41,23,23,43,60,74,73" href="deplacement.php?persoid=3&dep11=1" />
					   <area shape="poly" alt="Nord" coords="75,5,89,41,75,73,60,41" href="deplacement.php?persoid=3&dep12=1" />
					   <area shape="poly" alt="Nord-Est" coords="89,41,126,23,107,60,75,73" href="deplacement.php?persoid=3&dep13=1" />
					   <area shape="poly" alt="Ouest" coords="42,60,5,74,42,88,75,74" href="deplacement.php?persoid=3&dep21=1" />
					   <area shape="poly" alt="Est" coords="108,88,144,74,104,59,75,73" href="deplacement.php?persoid=3&dep23=1" />
					   <area shape="poly" alt="Sud-Ouest" coords="42,88,25,125,61,106,75,74" href="deplacement.php?persoid=3&dep31=1" />
					   <area shape="poly" alt="Sud" coords="61,107,75,144,90,107,75,74" href="deplacement.php?persoid=3&dep32=1" />
					   <area shape="poly" alt="Sud-Est" coords="90,107,125,125,108,89,75,73" href="deplacement.php?persoid=3&dep33=1" />
					</map>
				</td>
			</tr>
		<?php

		/*
		@ Fonction rose des vents
		Permet de changer le bouton submit en coordonee : array()  = $button_coordone[''];
		*/
		/*
		$button_coordone = array('11'=>'n_w', '12'=>'n', '13'=>'n_e', '21'=>'w', '22'=>'centre', '23'=>'e', '31'=>'s_w', '32'=>'s', '33'=>'s_e');
		
		for($inci=1 ; $inci<=3 ; $inci++){
				echo "<tr>";
				for($incj=1 ; $incj<=3 ; $incj++){
				?>
				<td>
						<form  name="dep<?php echo $inci.$incj;?>" method="post" action="deplacement.php">
						<input type=hidden name="perso_id" value="<?php echo $_SESSION['persos']['id'][0];?>">
						<input type=hidden name="dep<?php echo $inci.$incj;?>">
						<?php
								if($inci.$incj!='22')
										{
										    if ($is_spawn){
										   			 
										        echo "<input border=0 src='./../images/cartes/roses/".$race_im.$inci.$incj.".png' type='image' value='".$button_coordone[$inci.$incj]."' align='middle' />";
										        }
										        else echo "<img src='./../images/cartes/roses/".$race_im.$inci.$incj.".png' border='0' align='middle' />";
										}
								else echo "<img src='./../images/cartes/roses/".$race_im.$inci.$incj.".png' border='0' align='middle' alt='centre' />";
						?>
						</form>
				</td>
				<?php
				}
				echo "</tr>";
		}*/
		?>
		</table><?php if (0){ ?>
			Voir le GPS <span onClick="$('#minicarte').toggle();"><img src='../images/site/add.png' alt='voir' title='voir' /></span>
		<div id='minicarte' style='display:none'>
		
			<img src="./minicarte.php" alt="Mini carte" />
			
		</div><?php } ?>
	</div>
<!-- fin conteneur -->
</div>
<!-- Fin contour -->	
<div class='separation'></div>
