<?php
	include_once('ref.php.inc');

	function view_log($at){
		if(!isset($_SESSION['at_log_int_geo']) || $_SESSION['at_log_int_geo']+1800 <= time()){//On refresh toute les 30 minutes
			$logger = $at->getATLogger();
			$logger->log(array(ATLogger::LOG_AUTO_INTER_GEO,'a utilis&eacte; l\'outil automatique par IP',null));
			$_SESSION['at_log_int_geo'] = time();
		}
	}

	function content(){
		return '';
	}

