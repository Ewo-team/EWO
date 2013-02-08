<?php

require __DIR__ . '/../../conf/master.php';

define('CREATION', true);

$css_files = 'creation';

if(!isset($_SESSION['CreationPerso']['Etape'])) {
	$_SESSION['CreationPerso']['Etape'] = 1;
}

if($_SESSION['CreationPerso']['Etape'] == 1) {
    // Etape 1    
    include 'creation_gameplay.php';

} else {
    // Etape 2
    include 'creation_persos.php';    
}

?>
