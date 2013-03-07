<?php

include 'header.php';

if(!isset($_GET['go'])) {
	echo '<a href="raw.php?go=1">'.$_SESSION['cartographe']['raw'].'</a>';
} else {
    
    echo '<h1>Génération en cours</h1>
        <h2>Cela peut prendre un moment!</h2>';

    include 'generateurRaw.class.php';
    
    set_time_limit(0);

    $gr = new GenerateurRaw();


    $gr->genereFromPng($_SESSION['cartographe']['raw']);  
   
    
    echo '<hr><h1>Génération terminée</h1>';
	
}