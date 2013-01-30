<?php

session_start();

$file = $_SESSION['cartographe']['raw'];

include('raw/'.$file.'_sav.php');

if(isset($_POST['data'])) {
	$value = $_POST['data'];

	$newtab = json_decode($value);
	
	foreach($newtab as $ligne) {
	
		$coord = explode("_", $ligne->pos);
		$x = $coord[0];
		$y = $coord[1];
		
		$carte[$x][$y] = $ligne->classe;
	}	

	writeFile($file,$carte);
	
	echo 'ok';

}

function writeFile($file,$newdata) {

	if($handle = fopen('raw/' . ($file) . '_sav.php','w')) {

		fwrite($handle , '<?php' . PHP_EOL . PHP_EOL);
	
		foreach($newdata as $x => $axeX) {
			foreach($axeX as $y => $ligne) {

				$data = '$carte[';
				$data .= $x;
				$data .= '][';
				$data .= $y;
				$data .= '] = "';
				$data .= $ligne;
				$data .= '";' . PHP_EOL;	

				fwrite($handle , $data);
			
			}
		}
		
		fclose($handle);    
		
	} else {
		echo 'erreur d\'écriture dans le fichier raw/' . ($this->_nomFichier) . '_palette.php';
		exit;
	}	
	
	


}