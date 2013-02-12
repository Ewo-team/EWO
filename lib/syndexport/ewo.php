<?php

include('syndexport.php');

$liste_flux = array(
	//'http://www.ewo-le-monde.com/forum_beta/feed.php?f=2',
	//'http://www.ewo-le-monde.com/forum_beta/feed.php?f=19',
	'http://blog.ewo-le-monde.com/feed/atom/'
);

date_default_timezone_set("Europe/Paris");

function getAnnonces($nbmax) {

	$elems = array();
	global $liste_flux;

	foreach($liste_flux as $flux) {

		try {
			$fl = file_get_contents($flux);

			$se = new SyndExport($fl, "ATOM");
			
			$liste=$se->exportItems();
			
			for($i=0;$i!=count($liste);$i++)
			{
			
				$donnees=$liste[$i];
			
				$index = strtotime($donnees['date']) + $i;
				
				$elems[$index] = array (
					'titre' => $donnees['title'],
					//'corps' => nl2br(tronquage($donnees['description'],300)),
					'corps' => $donnees['description'],
					'auteur' => trim($donnees['author']),
					'lien' => $donnees['link']
				);

			}

		} catch(Exception $e) {
			// Nothing to do here
		}		
		

	}
	
	sort($elems);

	return $elems;

}

?>