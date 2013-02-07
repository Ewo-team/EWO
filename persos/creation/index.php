<?php

require __DIR__ . '/../../conf/master.php';

define('CREATION', true);

if(true) {
    // Etape 1    
    include 'creation_gameplay.php';

} else {
    // Etape 2
    include 'creation_persos.php';    
}

?>
