<?php

include 'header.php';
include('generateurPalette.class.php');

$gp = new GenerateurPalette();

if(isset($_POST['sauver'])) {
    $gp->generer($_SESSION['cartographe']['raw'],$_POST);
}

$gp->affichePalette($_SESSION['cartographe']['raw']);
