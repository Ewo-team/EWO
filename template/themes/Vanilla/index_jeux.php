<?php
/**
 * Template par defaut - Index Jeux
 *
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 1.0
 * @package template-defaut
 */
 
$page_jeu = "1";
?>

<div class='separation' id='p'></div>
<div id='carac'>
<?php
	//-- Carac entete du personnage	
	include("carac.php");
?>
</div>	

<div id="columns">
	<div id="column-1" class="column menu">
        
	<?php	
		if($rose == 0) {
                    //-- Rose des vents	
                    include("panel_mouvement.php");
                }
		//-- Dés de jeux
		include("infos.php");
		//-- Caracteristique du personnage
		include("info_carac.php");

      
	?>	
	</div>
	<div id="column-2" class="column menu_damier"  <?php echo $width__ ?>>
	<?php	

		//-- Damier du jeux
		include("damier.php");

	?>		
	</div>
</div>
