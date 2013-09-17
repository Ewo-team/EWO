<?php

namespace jeu\carte;

/**
 * Carte de célestia
 *
 * @version 1.0
 * @package carte
 */

$cache_url = __DIR__ . '/../../cache/svg_cifferis.cache';


require_once __DIR__ . "/../../conf/master.php";

include(SERVER_ROOT . "/persos/fonctions.php");
include(SERVER_ROOT . "/jeu/fonctions.php");
// Param�tres de connexion � la base de donn�es
$ewo_bdd = bdd_connect('ewo');

$ratio_hori = 5;
if(isset($_GET['hori'])) {
	$ratio_hori = $_GET['hori'];
}

$ratio_vert = 5;
if(isset($_GET['vert'])) {
	$ratio_vert = $_GET['vert'];
}

ControleAcces('utilisateur',1);

$conn = CarteDAO::getInstance();
//$carte = new Carte(1, $conn, 4.6666, 2.6666);
$carte = new Carte(3, $conn, $ratio_hori, $ratio_vert);

$encache = false;

if (file_exists($cache_url)) {
	clearstatcache();
	$time = @filemtime($cache_url);
	//if($time && (time() - $time < 60 * 15)) { // Cache de 15min
	if($time && (time() - $time < 15)) { // Cache de 15s
		$encache = true;
	}
}

$image_encoded = null;
if($encache) {
		
	// La carte est en cache, on la charge
	$data = file_get_contents($cache_url);
	$carte = Carte::deserializer($data, $conn);

} else {
	// La carte n'est pas en cache, on la recr�er et la place en cache
	$carte->Persos();	
	$carte->Boucliers();	
	$carte->Portes();

	// Sauvegarde de la carte
	$data = $carte->serializer();
	
	@file_put_contents($cache_url, $data); 
}


// Les viseurs sont ajout� apr�s la mise en cache
$carte->Viseurs($_SESSION['persos']);

// Affichage de la carte
echo $carte->Header();
echo $carte->Start();
echo $carte->Fond("fond_celestia");

echo $carte->Compile();

echo $carte->AxeHorizontale(50);
echo $carte->AxeHorizontale(25);
echo $carte->AxeHorizontale(75);

echo $carte->AxeVerticale(-25);
echo $carte->AxeVerticale(0);
echo $carte->AxeVerticale(25);

echo $carte->Footer();

mysql_close($ewo_bdd);
