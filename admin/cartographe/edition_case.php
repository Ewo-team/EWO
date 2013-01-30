<?php

session_start();

if(isset($_SESSION['cartographe']['raw'])) {

    $file = $_SESSION['cartographe']['raw'];
    
    $x = (isset($_GET['x'])) ? $_GET['x'] : null;
    $y = (isset($_GET['y'])) ? $_GET['y'] : null;
    $classe = (isset($_GET['classe'])) ? $_GET['classe'] : null;
    
    if($x && $y && $classe) {
        $_SESSION['cartographe']['carte'][$raw] = array('x' => $x,'y' => $y,'classe' => $classe);
    }
    
}
