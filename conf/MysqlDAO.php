<?php
/**
 * Constructeur Mysql
 *
 * @author Ganesh <ganesh@gmail.com>
 * @version 1.0
 * @package conf
 */



	require_once ("ConnecteurDAO.php");

	$MysqlDAOHandler = NULL;

	class MysqlDAO extends ConnecteurDAO {
		public function __construct($base) {
			// Instance Mysql pour les fonctions php mysql_
			if( isset($MysqlDAOHandler) && 	$MysqlDAOHandler != NULL ) {
				return $MysqlDAOHandler;
			} else {	
				$conf = info_connect($base);
				//$connect = mysql_connect($conf['serveur'],$conf['user'],$conf['pass']);
				$connect = mysql_connect($conf['serveur'],$conf['user'],$conf['pass']);
				mysql_select_db($conf['bdd'], $connect);
				mysql_set_charset('utf8');
				$this->_conn = $connect;
				$MysqlDAOHandler = $this;
			}
		}
		
	}

