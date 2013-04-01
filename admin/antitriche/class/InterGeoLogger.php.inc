<?php
/**
 * Logger d'interaction géographique
 * 
 * Cette classe a pour but de réaliser le log des inter action de déplacement
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 * @package antitriche
 */
 
	namespace at;
	require_once('Logger.php.inc');
	
/********************************************************
 * Type d'interaction									*
	* 1 : interaction critique							*
	* 2 : interaction dangereuse						*
	* 3 : nouvelle occurence d'interaction critique		*
	* 4 : nouvelle occurence d'interaction dangereuse	*
*******************************************************/

	class InterGeoLogger extends Logger{
	
		const CRITIQUE	= 10;
		const DANGEREUX	= 20;
		const DELAY		= 10800; //trois heure
	
	
		public function __construct(){
		
		}
		
		/*
		 *perso_mat : matricule du perso qui vient de bouger
		 */
		public function log($perso_mat){
			//on récupère l'id du perso dans la session	
			$idTbl = array_keys($_SESSION['persos']['id'], $perso_mat);
	        $perso_id = $idTbl[0];
			
			if($_SESSION['persos']['grade'][$perso_id] != -1){
				$type = $_SESSION['persos']['type'][$perso_id];
				if($type == 4){ // C'est un T4
					$this->checkT4($perso_id);
				}
				else if($type == 3){ //C'est un T1 : oui, c'est con de garder les vieux nom mais c'est comme ça !
					$this->checkT1($perso_id);
				}
			}
		}
		
		private function checkT1($perso_id){
			$i = 1;
			while($i <= $_SESSION['persos']['inc']){
				
				if($i != $perso_id &&  $_SESSION['persos']['grade'][$i] != -1 && isset($_SESSION['persos']['carte'][$i]) 
				&& $_SESSION['persos']['carte'][$i] == $_SESSION['persos']['carte'][$perso_id]){//pas meme perso et meme plan, on ne prend pas en compte les cafards
					
					//Initialisation pour utiliser la fonction distance
					$obj1['pos_x'] = $_SESSION['persos']['pos_x'][$i];
					$obj1['pos_y'] = $_SESSION['persos']['pos_y'][$i];

					$obj2['pos_x'] = $_SESSION['persos']['pos_x'][$perso_id];
					$obj2['pos_y'] = $_SESSION['persos']['pos_y'][$perso_id];
					
					$dist = distance($obj1,$obj2,$_SESSION['persos']['carte'][$i]);
					
					if($dist <= InterGeoLogger :: DANGEREUX ){//Si il y a interaction dangereuse
						$id1 = $i;
						$id2 = $perso_id;
						
						if($id2 < $id1){
							$id2 = $i;
							$id1 = $perso_id;
						}
						
						$this->gen($id1,$id2,$dist);
					}
				}
				$i++;
			}
		}
		
		private function checkT4($perso_id){
			$groups = $this->genGroups();
			$grp	= $groups[$this->groupFromPerso($groups,$perso_id)];
			
			$i = 1;
			while($i <= $_SESSION['persos']['inc']){
				
				if($i != $perso_id &&  $_SESSION['persos']['grade'][$i] != -1 && isset($_SESSION['persos']['carte'][$i]) 
				&& $_SESSION['persos']['carte'][$i] == $_SESSION['persos']['carte'][$perso_id] &&
				!in_array($i,$grp)){//pas meme perso et meme plan, on ne prend pas en compte les cafards, de même on jarte les T4 du même groupe
					
					//Initialisation pour utiliser la fonction distance
					$obj1['pos_x'] = $_SESSION['persos']['pos_x'][$i];
					$obj1['pos_y'] = $_SESSION['persos']['pos_y'][$i];

					$obj2['pos_x'] = $_SESSION['persos']['pos_x'][$perso_id];
					$obj2['pos_y'] = $_SESSION['persos']['pos_y'][$perso_id];
					
					$dist = distance($obj1,$obj2,$_SESSION['persos']['carte'][$i]);
					
					if($dist <= InterGeoLogger :: DANGEREUX ){//Si il y a interaction dangereuse
						$id1 = $i;
						$id2 = $perso_id;
						
						if($id2 < $id1){
							$id2 = $i;
							$id1 = $perso_id;
						}
						
						$this->gen($id1,$id2,$dist);
					}
				}
				$i++;
			}
		}
		
		private function genGroups(){
			$groups = array(); //list des groupes de T4
			$i = 1;
			$g = 0;
			$m = 0;
			while($i <= $_SESSION['persos']['inc']){
				if($m == 0)
					$groups[$g] = array();
				if($_SESSION['persos']['type'][$i] != 4){ // Si c'est pas un T4 osef
					$i++;
					continue; 
				}
				
				$groups[$g][$m++] = $i;
				if($m >= 4){	// le groupe est plein
					$g++; 		// next
					$m = 0;		// on recommence
				}
				$i++;
			}
			return $groups;
		}
		
		private function groupFromPerso($groups,$perso_id){
			foreach($groups as $k => $group)
				if(in_array($perso_id,$group))
					return $k;
		}
		
		/*
		 * id1	: id du perso 1
		 * id2	: id du perso 2
		 * dist	: distance entre les deux
		 * 
		 * Contraintes : A l'appel, on considère que matricule($id1) < matricule($id2) et que dist <= InterGeoLogger :: DANGEREUX  
		 */
		private function gen($id1,$id2,$dist){
			
			//Initialisation
			
			if(!isset($_SESSION['inter']))
				$_SESSION['inter']			= array();
			if(!isset($_SESSION['inter'][$id1]))
				$_SESSION['inter'][$id1]	= array();
			
			if(!isset($_SESSION['inter'][$id1][$id2]))
				$_SESSION['inter'][$id2]	= array();
			
			//On check	
			
			if($dist > InterGeoLogger :: CRITIQUE){
				$type 	= 2;
				$suffix	= 'Dang';
			}
			else{
				$type 	= 1;
				$suffix	= 'Crit';
			}
			
			$last	= parent::log($_SESSION['utilisateur']['id']);
			$ref 	= 'NULL';
			
			
			if(!isset($_SESSION['inter'][$id1][$id2]['lastInter'.$suffix]) ||
			$_SESSION['inter'][$id1][$id2]['lastInter'.$suffix] + InterGeoLogger :: DELAY <= time()){ //Pas d'interaction ou alors le délais a expiré
				
				$_SESSION['inter'][$id1][$id2]['lastInter'.$suffix]	= time();
				$_SESSION['inter'][$id1][$id2]['ref'.$suffix]		= $last;
				
				$type += 2;
			}
			else
				$ref = $_SESSION['inter'][$id1][$id2]['ref'.$suffix];
				
			$_SESSION['persos']['pos_x'][$id1]	= str_replace('</span>','',$_SESSION['persos']['pos_x'][$id1]);	
			$_SESSION['persos']['pos_y'][$id1]	= str_replace('</span>','',$_SESSION['persos']['pos_y'][$id1]);	
			$_SESSION['persos']['pos_x'][$id2]	= str_replace('</span>','',$_SESSION['persos']['pos_x'][$id2]);	
			$_SESSION['persos']['pos_y'][$id2]	= str_replace('</span>','',$_SESSION['persos']['pos_y'][$id2]);	
			
			$sql = '
				INSERT INTO `at_log_inter_geo`
				(`id`,`id_perso1`,`x1`,`y1`,`id_perso2`,`x2`,`y2`,`carte_id`,`ref`,`lvl`)
				VALUES(
					'.$last.',
						'.$_SESSION['persos']['id'][$id1] .',
						'.$_SESSION['persos']['pos_x'][$id1].',
						'.$_SESSION['persos']['pos_y'][$id1].',
						
						'.$_SESSION['persos']['id'][$id2] .',
						'.$_SESSION['persos']['pos_x'][$id2].',
						'.$_SESSION['persos']['pos_y'][$id2].',
					'.$_SESSION['persos']['carte'][$id1].',
					'.$ref.',
					'.$type.'
				);
			';
			
			mysql_query($sql) or die(mysql_error().'<pre>'.$sql.'</pre><br /><pre>'.var_dump($_SESSION['persos']).'</pre>');

			
		}
			
	}

