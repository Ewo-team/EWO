<?php

session_start();

header("Content-type: text/css");

if(isset($_SESSION['cartographe']['raw'])) {

    $file = $_SESSION['cartographe']['raw'];

    if(file_exists('palette/'.$file.'.php')) {
        include('palette/'.$file.'.php');

        foreach($css as $couleur => $array) {
            echo '.'.$array['nom'].' {
                background: url(../images/decors/motifs/'.$array['img'].')
                ';
                if(isset($array['back'])) {
                    echo ', url(../images/decors/motifs/'.$array['back'].')';
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
