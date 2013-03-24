<?php 
/**
 * Membre de l'anti-triche
 * 
 * Cette classe représente un membre de l'anti-triche
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 * @package antitriche
 */
	require_once('ATLogger.php.inc');
	
	class AT{
			
			private $mId;
			private $mLevel;
			private $mATLogger;
			
			/* 
			 * id 		: int, réfère à un id de compte
			 * level	: int, compris entre 0 et 5
			 */
			public function __construct($id, $level){
				$this->mId 			= $id;
				$this->mLevel		= $level;
				
				$this->mATLogger	= new ATLogger($this);
			}
			
			public function getId(){
				return $this->mId;
			}
			
			public function getLevel(){
				return $this->mLevel;
			}
			
			public function getATLogger(){
				return $this->mATLogger;
			}
			
			
			/*
			 * lvl	: niveau requis
			 */
			public function validateAccess($lvl){
				return $lvl <= $this->mLevel;
			}
			
			/*
			 * id		: int, id du personnage concerné
			 * xpMid	: int, > 0, xp à retirer s'il y a eu un gain frauduleux
			 */
			public function punish($id,$xpMod){
				if(!$this->validateAccess(3))
					return false;
				
				//Log de l'action
					$this->mATLogger->log(array(AT_LOG_PUNISH,'a puni $id->name violentement',$id));

				//TODO
				$idCompte	= 0; // id du compte rataché au perso
					
				//On récupère le dernier avertissement s'il existe
					$lastAvert 	= $this->getLastAvert($idCompte);
					
				
				//En fonction, lui défoncer la tronche plus ou moins fortement
					if($lastAvert == 0)
						return $this->punish_1($id,$idCompte,$xpMod); // Tend la joue gauche mon petit !
					elseif($lastAvert == 1)
						return $this->punish_2($id,$idCompte,$xpMod); // Tu vois les stéroïdes ? Bah ici c'est l'inverse
					elseif($lastAvert == 2)
						return $this->punish_3($id,$idCompte,$xpMod); // C'est l'heure de porter son joli costume rose et de se faire défoncer en boucle
					elseif($lastAvert == 3)
						return $this->punish_4($id,$idCompte,$xpMod); // Ultime pwn
					return false;
			}
			
			
				private function punish_1($id,$idCompte,$xpMod){
					/*
					 * TODO
					 * faire la reqête sql qui va bien
					 */
					 
					 echo "He has been punished ! YEAHHHHH";
					 
					 $this->incrAvertLvl($idCompte,1);
					 return true;
				}
				
				private function punish_2($id,$idCompte,$xpMod){
					/*
					 * TODO
					 * faire la reqête sql qui va bien
					 */
					 
					 $this->incrAvertLvl($idCompte,2);
					 return true;
				}
				
				private function punish_3($id,$idCompte,$xpMod){
					/*function 
					 * TODO
					 * faire la reqête sql qui va bien
					 */
					 
					 $this->incrAvertLvl($idCompte,3);
					 return true;
				}
				
				private function punish_4($id,$idCompte,$xpMod){
					/*
					 * TODO
					 * faire la reqête sql qui va bien
					 */
					 
					 return true;
				}
				
				private function getLastAvert($idCompte){
					$sql = '
						SELECT lvl
						FROM `at_punish`
						WHERE
							id = '.$idCompte.'
						ORDER BY
							lvl DESC
						LIMIT 0,1
					;';
					
					if(!$search = mysql_query($sql)){
						//log de l'erreur
						if(DEBUG)
							echo mysql_error();
						die();
					}
					$data = mysql_fetch_object($search);
					
					if($data)
						return $data->lvl;
					return 0;
				}
				
				private function incrAvertLvl($idCompte,$newLvl){
					$sql = '
						INSERT INTO `at_punish`
							(id,lvl,at,date)
						VALUES
							('.$idCompte.','.$newLvl.','.$this->getId().',NOW());';
					if(!mysql_query($sql)){
						//log de l'erreur
						if(DEBUG)
							echo mysql_error();
						die();
					}
				}
			
			/*
			 * lvl 		: int, compris entre 1 et  4
			 */
			private function sendMsg($lvl){

			}
	}
	
?>
