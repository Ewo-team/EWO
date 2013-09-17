<?php 

header('Content-Type: text/html; charset=utf-8');

require_once __DIR__ . '/../../conf/master.php';




$list = file_get_contents('http://leomaradan.com/cartographe/map_export.php');

try {
	$xml = simplexml_load_string($list);

	foreach($xml->fichier as $fichier) {
		
		$content = '';

		$dir = (string) $fichier->dir;
		$url = (string) $fichier->url;
		$hash = (string) $fichier->hash;

		echo "Téléchargement de $dir/$url<br>";

		$content = file_get_contents('http://leomaradan.com/cartographe/' . $dir . '/' . $url);

		if(md5($content) === $hash) {


			$dirname = '/usr/local/www/ewo/shared/decors/maps' . substr($dir, 6) . '/';
			$filename = $url . '.php';

			if(!is_dir($dirname)) {
				mkdir($dirname, 0777);
			}

			file_put_contents($dirname.$filename, $content);
			echo "Fichier téléchargé avec succès ($filename)<br>";
		} else {
			echo "Problème lors du téléchargement<br>";
		}

		echo "<br>";

	}
	
	$content = file_get_contents('http://leomaradan.com/cartographe/ewo.css');
	
	file_put_contents('/usr/local/www/ewo/shared/decors/css/ewo.css', $content);
} catch (Exception $e) {
	echo "Erreur lors de l'import : ";
	print_r($e);
}