<?php
//-- Header --
require_once __DIR__ . '/../conf/master.php';

include(SERVER_ROOT . '/template/header_new.php');

//------------
?>
<h2><?php if(isset($_SESSION['message']['titre'])){ echo $_SESSION['message']['titre'];}else{ echo "Une erreur s'est produite";} ?></h2>
<!-- Debut du coin -->
<div>
			<!-- conteneur -->
			
			<p><?php if(isset($_SESSION['message']['text'])){ echo $_SESSION['message']['text'];}else{ echo "Une erreur s'est produite";} ?></p>
			<p align='center'><a href="<?php if(isset($_SESSION['message']['lien'])){ echo $_SESSION['message']['lien'];}else{ echo "/";} ?>" alt='lien de retour'>[ Retour ]</a></p>
			<!-- fin conteneur -->
</div>
<!-- Fin du coin -->

<?php
//-- Footer --
include(SERVER_ROOT."/template/footer_new.php");
//------------
?>
