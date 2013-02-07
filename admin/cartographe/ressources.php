<?php

include 'header.php';

if(!isset($_GET['raw'])) {
        $dirname = 'ressource/';
        $dir = opendir($dirname); 

        echo '<table>';
        while($file = readdir($dir)) {
                if($file != '.' && $file != '..' && !is_dir($dirname.$file))
                {
                    echo '<tr><td><a href="ressources.php?raw='.$file.'">'.$file.'</td>
                        <td><img src="'.$dirname.$file.'"></td></tr>';
                }
        }  
        echo '</table>';   
} else {
    
    echo '<h1>Génération en cours</h1>
        <h2>Cela peut prendre un moment!</h2>';

    include 'generateurRaw.class.php';
    
    set_time_limit(0);

    $gr = new GenerateurRaw();

	$raw = substr($_GET['raw'],0,-4);
	
    $gr->genereFromPng($raw);  
    
    $_SESSION['cartographe']['raw'] = $raw;
    
    echo '<hr><h1>Génération terminée</h1>';
}