<?php

namespace conf;
use \PDO as PDO, 
    \Exception as Exception;

/**
 * Configuration DAO
 *
 * Connecteur général, destiné à être hérités
 * Il ne s'agit pas d'un véritable DAO, mais le but est le même 
 *
 * @author leo maradan
 * @version 1.0
 * @package conf
 */
 
	
	// Configuration des BDD
	include("connect.conf.php");
	
	// Magasin de variables
	include_once("VariableStorage.php");
	
	class ConnecteurDAO {

		/**
		 * Propriétés:  
		 * $_conn enregistre la référence de connection
		 * $_statement enregistre le dernier jeu de résultat reçu
		 */ 
		 
		/**
		 * Enregistre la référence de connection
		 * @var PDO Reference de connection
		 */
		protected $_conn = null;
		
		/**
		 * Enregistre le dernier jeu de résultat reçu
		 * @var PDOStatement Jeu de résultat
		 */		
		protected $_statement = null;

		/**
		 * Méthode de récupération d'instance
		 * Récupère une instance si elle existe, sinon la créer
		 * Chaque classes enfant ont des connections enregistrés séparément
		 * Chaque base de données également
		 * @param String $base Nom de la BDD
		 * @return Object instance demandée au Singleton
		 */
		public static function getInstance($base = "ewo")
		{
			static $instances = array();

			$class = get_called_class();

			if (isset($instances[$class][$base]) === false)
			{
				$instances[$class][$base] = new $class($base);
			}

			return $instances[$class][$base];   
		} 
	   
	    /**
		 * Constructeur
		 * Mis en visibilité protegé, pour éviter d'instancier manuellement
		 * la classe, obligeant à passer par le pattern Singleton
		 * Etant protegé, elle peut être redéfinie par des classes enfant
		 */
		protected function __construct($base) {
		
			// Construction de la connection
		
			$conf = info_connect($base);
			
			$dsn = 'mysql:dbname='.$conf['bdd'].';host='.$conf['serveur'];
			
			try {
				if($conf['pass'] && $conf['pass'] != "") {
					$connect = new PDO($dsn, $conf['user'], $conf['pass']);
				} else {
					$connect = new PDO($dsn, $conf['user']);
				}
				$connect->exec('SET NAMES utf8');
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (Exception $e) {
				trigger_error("La base de données n'est pas accessible", E_USER_ERROR);
				// Inscription de l'erreur dans un fichier log ? $e->getMessage();				
			}
			
			$this->_conn = $connect;
			
		}

		/**
		 * La méthode __clone() est interdite, il faut passer par le Singleton
		 */
		private function __clone() {}

		/** 
		 * Retourne la connection. Ne devrait pas être utilisé, mais est 
		 * publique pour faciliter le développement
		 * @return PDO connection PDO	 
		 */
		public function getConn() {
			return $this->_conn;
		}

		/**
		 * Exécute une requête via PDO::query, met en mémoire le jeu de valeur et le retourne
		 * @param String $query Requête SQL
		 * @return PDOStatement jeu de données	 
		 */
		public function query($query) {
			try {
				$this->_statement = $this->_conn->query($query);
				return $this->_statement;
			} catch (Exception $e) {
				trigger_error("Une erreur à été générée dans la base de données", E_USER_ERROR);
				// Inscription de l'erreur dans un fichier log ? $e->getMessage();
			}
		}
		
		/**
		 * Prépare une requête via PDO::prepare. La requête devra être exécuté via PDOStatement::execute
		 * @param String $query Requête préparée SQL
		 * @return PDOStatement Linker vers la requête préparée
		 */
		public function prepare($query) { 
			try {
				$this->_statement = $this->_conn->prepare($query);
				return $this->_statement;
			} catch (Exception $e) {
				trigger_error("Une erreur à été générée dans la base de données", E_USER_ERROR);
				// Inscription de l'erreur dans un fichier log ? $e->getMessage();
			}
		}
		
		/**
		 * Exécute une requête via PDO::exec (select n'est pas autorisé), et retourne le nombre de lignes affectés
		 * @param String $query Requête SQL
		 * @return Integer Nombre de lignes traitées 		 
		 */		
		public function exec($query) {
			try {
				return $this->_conn->exec($query);
			} catch (Exception $e) {
				trigger_error("Une erreur à été générée dans la base de données", E_USER_ERROR);
				// Inscription de l'erreur dans un fichier log ? $e->getMessage();
			}				
		}		
		
		public function lastId() {
                    try {
                        return $this->_conn->lastInsertId();
                    } catch (Exception $e)
                    {
                        trigger_error("Une erreur à été générée dans la base de données", E_USER_ERROR);
                    }
                }

		/**
		 * @TODO documenter la fonction
		 */		
		public function executePreparedStatement($pdoStatement = null, $param = null) {
			if($pdoStatement) {
				return $pdoStatement->execute($param);
			}
			
			return $this->_statement->execute($param);				
		}
		
		/**
		 * Cette fonction retourne une ligne d'un jeu d'enregistrement
		 * Si celui-ci n'est pas définie, il utilisera le jeu stocké. Pas de 
		 * vérification si celui-ci est définie ou non
		 * @param PDOStatement $pdoStatement Jeu de données
		 * @param Integer $type Type de tableau
		 * @return Array Tableau contenant les données d'une ligne 		 
		 */
		public function fetch($pdoStatement = null, $type = PDO::FETCH_BOTH) {
			if($pdoStatement) {
				return $pdoStatement->fetch($type);
			}
			
			return $this->_statement->fetch($type);
		}
		
		/**
		 * Cette fonction retourne toutes les lignes d'un jeu d'enregistrement
		 * Si celui-ci n'est pas définie, il utilisera le jeu stocké. Pas de 
		 * vérification si celui-ci est définie ou non
		 * @param PDOStatement $pdoStatement Jeu de données
		 * @param Integer $type Type de tableau
		 * @return Array Tableau contenant toutes les données 		 
		 */		
		public function fetchAll($pdoStatement = null, $type = PDO::FETCH_BOTH) {
			if($pdoStatement) {
				return $pdoStatement->fetchAll($type);
			}
			
			return $this->_statement->fetchAll($type);
		}		
		
		/**
		 * Alias de la fonction fetch, pour avoir une meilleurs visibilité dans le code les utilisants
		 */
		public function fetch_assoc($pdoStatement = null) {
			return $this->fetch($pdoStatement, PDO::FETCH_ASSOC);
		}
		public function fetch_row($pdoStatement = null) {
			return $this->fetch($pdoStatement, PDO::FETCH_NUM);
		}
		public function fetch_array($pdoStatement = null) {
			return $this->fetch($pdoStatement, PDO::FETCH_BOTH);
		}
		
		/**
		 * Alias de la fonction fetchAll, pour avoir une meilleurs visibilité dans le code les utilisants
		 */		
		public function fetchAll_assoc($pdoStatement = null) {
			return $this->fetchAll($pdoStatement, PDO::FETCH_ASSOC);
		}
		public function fetchAll_row($pdoStatement = null) {
			return $this->fetchAll($pdoStatement, PDO::FETCH_NUM);
		}
		public function fetchAll_array($pdoStatement = null) {
			return $this->fetchAll($pdoStatement, PDO::FETCH_BOTH);
		}		
		
		/**
		 * Fonction basique qui retourne les pseudos et matricule des 
		 * persos d'un utilisateur connu
		 * @param Integer $user Id d'un utilisateur
		 * @return Array Tableau associatif contenant les matricules et pseudos des personnages	 		 
		 */
		public function SelectListPersoFromUser($user) {
			$sql = "SELECT persos.id AS id_perso, persos.nom AS nom_perso
					FROM persos 
					WHERE utilisateur_id = '$user'";
			$this->query($sql);
			return $this->fetchAll();
		}
		
		/**
		 * Vérification si un perso dont l'id est connu existe
		 * Le SQL_CACHE ainsi que la LIMIT 1 optimisent la vitesse d'exécution
		 * @param Integer $user Matricule d'un personnage
		 * @return Bool Booléen indiquant si le personnage existe ou non 		 
		 */
		public function persoExist($id) {
			$sql = "SELECT SQL_CACHE count(id) FROM persos WHERE id='$id' LIMIT 1";
			
			$this->query($sql);
			$response = $this->fetch_row();
			
			// retourne TRUE si il y a une ligne, et FALSE sinon
			return ($response[0] == 1) ? TRUE : FALSE;
		}
		
		public function SelectPersoIdByName($name) {
			$sql = "SELECT SQL_CACHE persos.id FROM persos WHERE nom LIKE ? LIMIT 1";
			$this->prepare($sql);
			$this->executePreparedStatement(null,array('%'.$name.'%'));

			$response = $this->fetch();			
			
			return $response[0];
		}
		
		public function SelectPersoNameById($id) {
			$sql = "SELECT SQL_CACHE persos.nom FROM persos WHERE id='?' LIMIT 1";
			$this->prepare($sql);
			$this->executePreparedStatement(null,array($id));
			
			$response = $this->fetch();		
			
			return $response[0];		
		}		
	}


?>