<?php
	if(isset($page_jeu)){
		if($page_jeu == "1"){
			$monfichier = fopen('/tmp/ewo.phpload.log', 'a+');
			fputs($monfichier, $page_time."\n");
			fclose($monfichier);
		}
	}
?>
