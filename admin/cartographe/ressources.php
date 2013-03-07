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


	$raw = substr($_GET['raw'],0,-4);
	    
    $_SESSION['cartographe']['raw'] = $raw;
    
    echo '<h1>Ressource sélectionné</h1>';
}