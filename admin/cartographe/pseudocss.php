<?php

require_once __DIR__ . '/../../conf/master.php';

header("Content-type: text/css");

$cssfile = '';

if(isset($_SESSION['cartographe']['raw'])) {

    $file = $_SESSION['cartographe']['raw'];

    if(file_exists('palette/'.$file.'.php')) {
        include('palette/'.$file.'.php');

        foreach($css as $couleur => $array) {
            $lignecss = '.damier_'.$array['nom'].' {
                background: url('.SERVER_URL.'/images/decors/motifs/'.$array['img'].')';
                if(isset($array['back'])) {
                    $lignecss .= PHP_EOL . ', url('.SERVER_URL.'/images/decors/motifs/'.$array['back'].')';
                }
                $lignecss .= ';
                width: 45px;
                height: 39px
             }

        ';
			echo $lignecss;
			$cssfile .= $lignecss;
        }
    }
	
	file_put_contents(SERVER_ROOT . '/jeu/decors/css/'.$file.'.css', $cssfile);
}


?>
