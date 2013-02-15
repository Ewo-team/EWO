<?php

include 'POV.php';

$i = 0;

function fnc_count($item, $key) {
	global $cpt;
	
	$cpt += count($item);
}

function getmicrotime() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
}

$debut = getmicrotime();

for($i = 2; $i < 50; $i++) {
	echo $i.'<br>';
	$pov = POV::getPOV($i);
	echo count($pov) . ' rayons<br>';
	
	$cpt = 0;
	
	array_walk($pov, 'fnc_count');
	
	echo $cpt . ' cases<br>';	
	
	echo '<hr>';
}

$fin = getmicrotime();
$page_time = round($fin-$debut, 3);
echo "Page générée en ".$page_time." secondes.";

