<?php

require_once __DIR__ . '/../conf/master.php';

$perso_x = 5;
$perso_y = -5;
$percept = 8;

$x_min = $perso_x - $percept;
$x_max = $perso_x + $percept;

$y_min = $perso_y - $percept;
$y_max = $perso_y + $percept;

$damier = new \jeu\Damier($perso_x, $perso_y, $percept, 'prevf2');

$damier->initialize();

echo '<table border="0">';
for($x = $x_min; $x <= $x_max; $x++) {
	echo '<tr>';
	
	if($x == $x_min) {
		
		for($cpt = $y_min; $cpt <= $y_max; $cpt++) {
			echo "<th>$x / $cpt</th>";
		}
		echo '</tr><tr>';
	}
	
	for($y = $y_min; $y <= $y_max; $y++) {
	
		$image = $damier->getCase($x,$y);
		
		if($image) {

				$url = SERVER_URL . '/images/decors/motifs/' . $image->background;

			
	
			echo "<td><img src='$url'></td>";
		} else {
			echo "<td>$x / $y</td>";
		}
	}
	
	if($x == $x_max) {
		echo '</tr><tr>';
		for($cpt = $y_min; $cpt <= $y_max; $cpt++) {
			echo "<th>$x / $cpt</th>";
		}
	}	
	
	echo '</tr>';

}

echo '</table>';