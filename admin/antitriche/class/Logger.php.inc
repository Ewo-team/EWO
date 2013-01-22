<?php
/**
 * Class de log
 * 
 * Classe abtraite qui définir le comportement d'un logger d'AT
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 * @package antitriche
 */
	namespace at;

	define('DEBUG',true);

	abstract class Logger{
	
		public function __construct(){
			
		}
		
		/*
		 * id		: id du peros qui subit l'aciton
		 */
		public function log($id){
			$sql	= 'INSERT INTO `at_log` (compte,date) VALUES(\''.$id.'\',NOW());';
			if(!mysql_query($sql)){
				//log de l'erreur
				if(DEBUG)
					echo mysql_error();
				die();
			}
			return  mysql_insert_id();
		}
	
	}

?>
