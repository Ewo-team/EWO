<?php
$root_url = "..";
include($root_url."/template/header_new.php");
/*-- Connexion basic requise --*/
ControleAcces('admin',1);
/*-----------------------------*/
include ("fonctions.php");
//------------

$js->addScript('editeur');

?>

<img src="../images/decors/motifs/vide.gif" alt="Img a placer" id="img_souris" style="position:absolute;display:block;z-index:999999;" />
<span id='img_deco_ico'></span>
<span id='img_objet_ico'></span>
<span id='img_artefact_ico'></span>
<span id='img_gomme_ico'></span>

	<?php	include ("menu.php"); ?>
	
<div id="editeur_columns">
	<div id="column-1" class="column editeur_menu">
			<?php	include ("decors.php"); ?>
			<?php include ("objets_simple.php"); ?>
			<?php	include ("artefact.php"); ?>	
	</div>
	<div id="column-2" class="column editeur_menu_damier">
				<?php
					include ("outil.php");
					include ("damier.php");
					include ("tempon.php"); 
				?>	
	</div>
</div>

<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
