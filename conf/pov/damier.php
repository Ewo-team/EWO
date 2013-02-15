<?php

function getmicrotime() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
}

function recurse($search, $arr) {
	foreach($arr as $x => $line) {
		foreach($line as $y => $col) {
			if($col == $search) {
				return array($x,$y);
			}
		}
	}
	
	return false;
}

$taille = 18;
$position_x = 2;
$position_y = 10;

$debut = getmicrotime();

$array = array();

for($ligne = 0; $ligne < $taille; $ligne++) {
	$arr = array();
	for($colonne = 0; $colonne < $taille; $colonne++) {
		$arr[] = 0;
	}
	$array[] = $arr;
}

$array[11][5] = 2;
$array[11][6] = 2;
$array[11][7] = 2;
$array[11][8] = 2;
$array[10][8] = 2;
$array[12][8] = 2;
$array[13][8] = 2;


$array[0][4] = 2;
$array[5][0] = 2;

$array[7][9] = 2;

$array[12][12] = 2;

$array[7][14] = 2;
$array[8][14] = 2;


/*$array = array(
	array('P',0,0,0,0,0,0),
	array(0,0,0,0,0,0,0),
	array(0,0,0,0,0,0,0),
	array(0,0,0,0,0,0,0),
	array(0,0,0,0,0,0,0),
	array(0,0,0,0,0,0,0),
	array(0,0,0,0,0,0,0)
);*/

/*$distance_x = 9;
$distance_y = 9;
$distance = sqrt(($distance_x * $distance_x) + ($distance_y * $distance_y));

$vecteur_x = ($distance / $distance_x);
$vecteur_y = ($distance / $distance_y);

echo $vecteur_x.'<br>';
echo $vecteur_y.'<br>';

//echo $distance;

echo ($vecteur_x*1).','.($vecteur_y*1).'<br>';
echo ($vecteur_x*2).','.($vecteur_y*2).'<br>';
echo ($vecteur_x*3).','.($vecteur_y*3).'<br>';
echo ($vecteur_x*4).','.($vecteur_y*4).'<br>';
echo ($vecteur_x*5).','.($vecteur_y*5).'<br>';
echo ($vecteur_x*6).','.($vecteur_y*6).'<br>';
echo ($vecteur_x*7).','.($vecteur_y*7).'<br>';
echo ($vecteur_x*8).','.($vecteur_y*8).'<br>';
echo ($vecteur_x*9).','.($vecteur_y*9).'<br>';
/*
resultat attendu:
1,1
2,2
3,3
4,4
5,5
6,6
7,7
8,8
9,9
*/

/*
echo (3 / $distance) * 1 . '<br>';
echo (3 / $distance) * 2 . '<br>';
echo (3 /$distance) * 3 . '<br>';
echo (4 /$distance) * 4 . '<br>';
//echo sqrt(*/


/**
* Virtually draw a line from (x1, y1) to (x2, y2) using Bresenham algorithm, returning the coordinates of the points that would belong to the line.
* @param $x1 (Int)
* @param $y1 (Int)
* @param $x2 (Int)
* @param $y2 (Int)
* @param $guaranteeEndPoint By default end point (x2, y2) is guaranteed to belong to the line. Many implementation don't have this. If you don't need it, it's a little faster if you set this to false.
* @return (Array of couples forming the line) Eg: array(array(2,100), array(3, 101), array(4, 102), array(5, 103))
* Public domain Av'tW
*/
function bresenham($x1, $y1, $x2, $y2, $guaranteeEndPoint=true) {
	$xBegin = $x1;
	$yBegin = $y1;
	$xEnd = $x2;
	$yEnd = $y2;
	$dots = array();        // Array of couples, returned array</p>

	$steep = abs($y2 - $y1) > abs($x2 - $x1);

	// Swap some coordinateds in order to generalize
	if ($steep) {
		$tmp = $x1;
		$x1 = $y1;
		$y1 = $tmp;
		$tmp = $x2;
		$x2 = $y2;
		$y2 = $tmp;
	}
	
	if ($x1 > $x2) {
		$tmp = $x1;
		$x1 = $x2;
		$x2 = $tmp;
		$tmp = $y1;
		$y1 = $y2;
		$y2 = $tmp;
	}
	
	$deltax = floor($x2 - $x1) + 0.5;
	$deltay = floor(abs($y2 - $y1)) + 0.5;
	$error = 0;
	$deltaerr = $deltay / $deltax;
	$y = $y1;
	$ystep = ($y1 > $y2) ? 1 : -1;

	for ($x = $x1; $x < $x2; $x++) {
		$dots[] = $steep ? array($y, $x) : array($x, $y);
		$error += $deltaerr;

        if ($error >= 0.5) {
			$y += $ystep;
			$error -= 1;
		}
	}

	if ($guaranteeEndPoint) {
		// Bresenham doesn't always include the specified end point in the result line, add it now.
		if ((($xEnd - $x) * ($xEnd - $x) + ($yEnd - $y) * ($yEnd - $y)) <
		(($xBegin - $x) * ($xBegin - $x) + ($yBegin - $y) * ($yBegin - $y))) {
			// Then we're closer to the end
			$dots[] = array($xEnd, $yEnd);
		} else {
			$dots[] = array($xBegin, $yBegin);
		}
	}
		
	return $dots;
}
/*
echo "<table border='1' width='".($taille*40)."px' height='".($taille*40)."px'>";

foreach($array as $x => $ligne) {
	echo "<tr>";
	foreach($ligne as $y => $value) {
		echo "<td class='classe$value'>($x,$y)</td>";
	}
	echo "</tr>";	
}
echo '</table>';*/

$cases = array();
for($i=0;$i<$taille;$i++) {
	$cases[] = array($i,$taille-1);
	$cases[] = array($taille-1,$i);
}

$liste_rayon = array();

parcours($cases, $array);

function parcours($cases, &$array) {

	global $liste_rayon;

	foreach($cases as $rayon) {

		$position_x = $rayon[0];
		$position_y = $rayon[1];

		$line = bresenham(0, 0, $position_x, $position_y, false);
		$line[] = array($position_x, $position_y);
		
		$liste_rayon[] = $line;
		
		$couleur = 1;
		foreach($line as $point) {
			$x = abs($point[0]);
			$y = abs($point[1]);
			
			if($array[$x][$y] == 2) {
				$couleur = 3;
			} else {
				$array[$x][$y] = $couleur;	
			}
		}
	}
}

$recurse = true;
/*
do {
	$case = recurse(0, $array);
		
	if($case == false) {
		$recurse = false;
	} else {
		parcours(array($case), $array);
	}
} while($recurse);*/

//$array[$position_x][$position_y] = 1;
//$array[$position_x][$position_y] = 1;
			 
echo "<table border='1' width='".($taille*40)."px' height='".($taille*40)."px'>";

foreach($array as $x => $ligne) {
	echo "<tr>";
	foreach($ligne as $y => $value) {
		echo "<td class='classe$value'>($x,$y)</td>";
	}
	echo "</tr>";	
}
echo '</table>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>$(document).ready(function() {
	$(".classe0").css("background-color", "cyan");
	$(".classe2").css("background-color", "black");
	$(".classe3").css("background-color", "darkcyan");
	$(".classe4").css("background-color", "red");
});</script>
';	

echo '<pre>';
//print_r($liste_rayon);
echo '</pre>';

$fin = getmicrotime();
$page_time = round($fin-$debut, 3);
echo "Page générée en ".$page_time." secondes.";