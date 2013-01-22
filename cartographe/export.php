<?php

include 'header.php';

if(isset($_GET['run'])) {
	$file = $_SESSION['cartographe']['raw'];

	echo '<h1>Exportation en cours</h1>
		<h2>Cela peut prendre un moment!</h2>';

	include 'Export.class.php';

	set_time_limit(0);

	$xp = new Export();

	$xp->Build($file,15);

	echo '<hr><h1>Exportation terminée</h1>';
} else {
	echo '<a href="export.php?run=1">Lancer l\'export</a>';
}