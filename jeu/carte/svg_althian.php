<?php

namespace jeu\carte;

/**
 * Carte de la terre
 *
 * @author Anarion <anarion@ewo.fr>
 * @version 1.0
 * @package carte
 */

$cache_url = __DIR__ . '/../../cache/svg_althian.cache';
//$cache_url = 'D:/wamp/www/cache/svg_althian.cache';

require_once __DIR__ . "/../../conf/master.php";

include(SERVER_ROOT . "/persos/fonctions.php");
include(SERVER_ROOT . "/jeu/fonctions.php");
// Paramètres de connexion à la base de données
$ewo_bdd = bdd_connect('ewo');

$taille = 4;
if(isset($_GET['taille'])) {
	$taille = $_GET['taille'];
}

ControleAcces('utilisateur',1);

$conn = CarteDAO::getInstance();
//$carte = new Carte(1, $conn, 4.6666, 2.6666);
$carte = new Carte(1, $conn, 5, 3);

$encache = false;

if (file_exists($cache_url)) {
	clearstatcache();
	$time = @filemtime($cache_url);
	//if($time && (time() - $time < 60 * 15)) { // Cache de 15min
	if($time && (time() - $time < 15)) { // Cache de 15s
		$encache = true;
	}
}


if($encache) {
		
	// La carte est en cache, on la charge
	$data = file_get_contents($cache_url);
	$carte = Carte::deserializer($data, $conn);
	
} else {
	// La carte n'est pas en cache, on la recréer et la place en cache
	$carte->Persos();	
	$carte->Boucliers();	
	$carte->Portes();

	// Sauvegarde de la carte
	$data = $carte->serializer();
	
	@file_put_contents($cache_url, $data); 
}

// Les viseurs sont ajouté après la mise en cache
$carte->Viseurs($_SESSION['persos']);

// Affichage de la carte
echo $carte->Header();
echo $carte->Start();
echo $carte->Fond();

echo $carte->Compile();

echo $carte->AxeHorizontale(45);
echo $carte->AxeHorizontale(15);
echo $carte->AxeHorizontale(-15);
echo $carte->AxeHorizontale(-45);

echo $carte->AxeVerticale(45);
echo $carte->AxeVerticale(15);
echo $carte->AxeVerticale(-15);
echo $carte->AxeVerticale(-45);

echo $carte->Footer();

mysql_close($ewo_bdd);
?>