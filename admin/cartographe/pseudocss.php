<?php

require_once __DIR__ . '/../../conf/master.php';

header("Content-type: text/css");

if(isset($_SESSION['cartographe']['raw'])) {

    $file = $_SESSION['cartographe']['raw'];

    if(file_exists('palette/'.$file.'.php')) {
        include('palette/'.$file.'.php');

        foreach($css as $couleur => $array) {
            echo '.'.$array['nom'].' {
                background: url('.SERVER_URL.'/images/decors/motifs/'.$array['img'].')
                ';
                if(isset($array['back'])) {
                    echo ', url('.SERVER_URL.'/images/decors/motifs/'.$array['back'].')';
                }
                echo ';
                width: 45px;
                height: 39px
             }

        ';
        }
    }
}
?>
