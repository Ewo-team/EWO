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


	define('AT_LOG_PUNISH',0);
	

	class ATLogger extends at\Logger{
	
		private $mAt;
	
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
		public function log($action,$message,$id){
			
			$last	= parent::log($this->mAt->getId());

			$sql	= 'INSERT INTO `at_log_at` (id,action,message) VALUES('.$last.',\''.$action.'\',\''.addslashes($message).'\');';
			if(!mysql_query($sql)){
				//log de l'erreur
				if(DEBUG)
					echo mysql_error();
				die();
			}
		}
	
	}
?>
