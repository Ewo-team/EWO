<?php
/**
 * Faction - Page d'erreur des factions
 *
 * @author Anarion <anarion@ewo.fr>
 * @version 1.0
 * @package faction
 */

//-- Header --
$root_url = "..";
include($root_url."/template/header_new.php");
/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);
/*-----------------------------*/
?>
<div align='center'>
<h2>Erreur !</h2>
<!-- Debut du coin -->
<div class="upperleft" id='coin_75'>
	<div class="upperright">
		<div class="lowerleft">
			<div class="lowerright">
			<!-- conteneur -->		
				<p>Action non autoris&eacute;e, vous n'avez pas les droits suffisants.</p>
			<!-- fin conteneur -->
			</div>
		</div>
	</div>
</div>
<!-- Fin du coin -->
</div>
<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
