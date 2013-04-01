<?php
/**
 * Logger d'action d'un membre de l'anti-triche
 * 
 * Cette classe a pour de logger les actions réalisées par les membres de l'anti-triche
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 * @package antitriche
 */
	require_once('AT.php.inc');
	require_once('Logger.php.inc');


	
	

	class ATLogger extends at\Logger{
	
		private $mAt;
		
		const LOG_AUTO_INTER_GEO	 	= 1;
		const LOG_AUTO_INTER_ACTION	= 2;
		const LOG_AUTO_IP			= 3;
		const LOG_AUTO_COOKIE		= 4;
		const LOG_COMPARE_IP			= 5;
		const LOG_COMPARE_COOKIE		= 6;
		const LOG_COMPARE_EVENTS		= 7;
		const LOG_LOGS				= 8;
		const LOG_PUNISH				= 9;
		const LOG_ADMIN				= 10;
		const LOG_LOGS_AT			= 11;
		
		/*
		 * at	: un objet de type AT valide 
		 */
		public function __construct($at){
			$this->mAt	= $at;
		}
		
		/*
		 * action	: action réalisée (voir les defines en haut)
		 * message	: message (système de parsage des messages, voir le wiki pour plus d'info)
		 * id		: id du compte qui subit l'action
		 */
		public function log($param){
			$action = $param[0];
			$message = $param[1];
			$id = $param[2];
			$last	= parent::log($this->mAt->getId());

			$sql	= 'INSERT INTO `log_at` (id,action,message) VALUES('.$last.',\''.$action.'\',\''.addslashes($message).'\');';
			if(!mysql_query($sql)){
				//log de l'erreur
				if(DEBUG)
					echo mysql_error();
				die();
			}
		}
	
	}

