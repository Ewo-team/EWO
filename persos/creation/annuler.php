<?php 

require __DIR__ . '/../../conf/master.php';

ControleAcces('utilisateur',1);

if(isset($_SESSION['CreationPerso']['Etape'])) {
	unset($_SESSION['CreationPerso']['Etape']);
}

header("location: .");