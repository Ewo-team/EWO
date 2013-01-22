<?php
//-- Header --
$root_url = "..";
include($root_url."/template/header_new.php");
//------------
?>
<h2><?php if(isset($_SESSION['message']['titre'])){ echo $_SESSION['message']['titre'];}else{ echo "Une erreur s'est produite";} ?></h2>
<!-- Debut du coin -->
<div class='upperleft' id='coin_50'>
	<div class='upperright'>
		<div class='lowerleft'>
			<div class='lowerright'>
			<!-- conteneur -->
			
			<p><?php if(isset($_SESSION['message']['text'])){ echo $_SESSION['message']['text'];}else{ echo "Une erreur s'est produite";} ?></p>
			<p align='center'><a href="<?php if(isset($_SESSION['message']['lien'])){ echo $_SESSION['message']['lien'];}else{ echo "/";} ?>" alt='lien de retour'>[ Retour ]</a></p>
			<!-- fin conteneur -->
			</div>
		</div>
	</div>
</div>
<!-- Fin du coin -->

<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
