<?php


include_once($root_url."/conf/master.php");
include_once($root_url."/conf/config.php");
require_once($root_url.'/event/eventManager.php');
require_once($root_url.'/jeu/fonctions.php');
require_once($root_url.'/persos/fonctions.php');


$ewo_bdd = bdd_connect('ewo');

//error_reporting(E_ALL);

/**
 Pour éviter des boucles, toutes les param�tres pour le calcule de la grille de danger sont prédéfinis
**/


global $LVL;
$LVL = array('10,10' => 0.1, 	'9,10' => 0.1, 	'8,10' => 0.1, 	'7,10' => 0.1, 	'6,10' => 0.1, 	'5,10' => 0.1, 	'4,10' => 0.1, 	'3,10' => 0.1, 	'2,10' => 0.1, 	'1,10' => 0.1, 	'0,10' => 0.1, 
							'10,9' => 0.1, 	'9,9' => 0.2, 	'8,9' => 0.2, 	'7,9' => 0.2, 	'6,9' => 0.2, 	'5,9' => 0.2, 	'4,9' => 0.2, 	'3,9' => 0.2, 	'2,9' => 0.2, 	'1,9' => 0.2, 	'0,9' => 0.2, 
							'10,8' => 0.1, 	'9,8' => 0.2, 	'8,8' => 0.3, 	'7,8' => 0.3, 	'6,8' => 0.3, 	'5,8' => 0.3, 	'4,8' => 0.3, 	'3,8' => 0.3, 	'2,8' => 0.3, 	'1,8' => 0.3, 	'0,8' => 0.3, 
							'10,7' => 0.1, 	'9,7' => 0.2, 	'8,7' => 0.3, 	'7,7' => 0.4, 	'6,7' => 0.4, 	'5,7' => 0.4, 	'4,7' => 0.4, 	'3,7' => 0.4, 	'2,7' => 0.4, 	'1,7' => 0.4, 	'0,7' => 0.4, 
							'10,6' => 0.1, 	'9,6' => 0.2, 	'8,6' => 0.3, 	'7,6' => 0.4, 	'6,6' => 0.5, 	'5,6' => 0.5, 	'4,6' => 0.5, 	'3,6' => 0.5, 	'2,6' => 0.5, 	'1,6' => 0.5, 	'0,6' => 0.5, 
							'10,5' => 0.1, 	'9,5' => 0.2, 	'8,5' => 0.3, 	'7,5' => 0.4, 	'6,5' => 0.5, 	'5,5' => 0.6, 	'4,5' => 0.6, 	'3,5' => 0.6, 	'2,5' => 0.6, 	'1,5' => 0.6, 	'0,5' => 0.6, 
							'10,4' => 0.1, 	'9,4' => 0.2, 	'8,4' => 0.3, 	'7,4' => 0.4, 	'6,4' => 0.5, 	'5,4' => 0.6, 	'4,4' => 0.7, 	'3,4' => 0.7, 	'2,4' => 0.7, 	'1,4' => 0.7, 	'0,4' => 0.7, 
							'10,3' => 0.1, 	'9,3' => 0.2, 	'8,3' => 0.3, 	'7,3' => 0.4, 	'6,3' => 0.5, 	'5,3' => 0.6, 	'4,3' => 0.7, 	'3,3' => 0.8, 	'2,3' => 0.8, 	'1,3' => 0.8, 	'0,3' => 0.8, 
							'10,2' => 0.1, 	'9,2' => 0.2, 	'8,2' => 0.3, 	'7,2' => 0.4, 	'6,2' => 0.5, 	'5,2' => 0.6, 	'4,2' => 0.7, 	'3,2' => 0.8, 	'2,2' => 0.9, 	'1,2' => 0.9, 	'0,2' => 0.9, 
							'10,1' => 0.1, 	'9,1' => 0.2, 	'8,1' => 0.3, 	'7,1' => 0.4, 	'6,1' => 0.5, 	'5,1' => 0.6, 	'4,1' => 0.7, 	'3,1' => 0.8, 	'2,1' => 0.9, 	'1,1' => 1, 	'0,1' => 1, 
							'10,0' => 0.1, 	'9,0' => 0.2, 	'8,0' => 0.3, 	'7,0' => 0.4, 	'6,0' => 0.5, 	'5,0' => 0.6, 	'4,0' => 0.7, 	'3,0' => 0.8, 	'2,0' => 0.9, 	'1,0' => 1, 	'0,0' => 1); 


/**
 IA minimaliste pour EWO
 Cette IA est uniquement capable de ce déplacer, et réagit aux conflits en fuyant
**/
class MicroIA {
	
	private $id;
	private $dna;
	private $status;
	
	private $bdd;
	private $data;
	
	/**
	  Grille d'évaluation du danger. 5 = neutre, 0 = safe, 10 = dangereux
	  La grille est généré en faisant une "aura" autour de tout les persos.
	  Les IA amie ont une aura négatives, les PJ inconnus une aura positive, les PJ connus comme aggressifs une aura très positive
	**/
	public $dangergrid;
	
	public static function Run($id)
	{
		try {
			$instance = new MicroIA($id);
			$move = $instance->Evaluate();
			$instance->Action($move);
			$instance->Planifiate();
		} catch (Exception $e)
		{
		
		}
		unset($instance);
	}
	
	public function __construct($id) {
		$this->bdd = ConnecteurDAO::getInstance();
		
		$this->id = $id;
		
		$this->data = (Object) Array();
		$this->dna = (Object) Array();
		$this->dangergrid = array();

		$this->Load();
		
		for($i = 0-$this->data->percept; $i <= $this->data->percept; $i++)
		{
			for($j = 0-$this->data->percept; $j <= $this->data->percept; $j++)
			{
				$this->dangergrid[$i][$j] = 5;
			}		
		}		
	}
	
	function Load()
	{
		$sql = "SELECT * FROM persos_ia i LEFT JOIN damier_persos p ON (p.perso_id = i.id) WHERE i.id = ".$this->id;
		
		$this->bdd->query($sql);
		
		$ligne = $this->bdd->fetch_assoc();		
	
		if($ligne['pos_x'] != null)
		{
			$this->data->percept = 5;
			$this->data->carte_id = $ligne['carte_id'];
			$this->data->pos_x = $ligne['pos_x'];
			$this->data->pos_y = $ligne['pos_y'];

                        if(isset($ligne['dna']) && $ligne['dna'] != '') {
                            $this->dna = unserialize($ligne['dna']);
                        } else {
                            $this->dna = $this->createDNA(md5(time()));
                        }
                        
                        if(!isset($this->dna->type)) {
                           $this->dna->type = md5(time()) ;
                        }
                        
                        if(!isset($this->dna->reflex)) {
                           $this->dna->reflex =  $this->baseReflexes();
                        }                        
                        
                        //$this->dna = unserialize($ligne['dna']);
			
		} else {
			throw new Exception("IA morte");
		}
                
                $this->data->status = (isset($dna->status)) ? $dna->status : 5;
	}
	
	function Evaluate() {

		$x = $this->data->pos_x;
		$y = $this->data->pos_y;
		
		$x1 = $x - ($this->data->percept*2);
		$x2 = $x + ($this->data->percept*2);
		
		$y1 = $y - ($this->data->percept*2);
		$y2 = $y + ($this->data->percept*2);	
                
                $min_x = min($x1,$x2);
                $max_x = max($x1,$x2);
                $min_y = min($y1,$y2);
                $max_y = max($y1,$y2);                
		
		$carte = $this->data->carte_id;
	
		$this->bdd->query("SELECT * FROM damier_persos p LEFT JOIN persos_ia i ON (p.perso_id = i.id) WHERE (`pos_x` BETWEEN $min_x AND $max_x) AND (`pos_y` BETWEEN $min_y AND $max_y) AND `carte_id` = $carte AND `perso_id` != ".$this->id);
		
		$liste = $this->bdd->fetchAll_assoc();
		
                $this->memorizePersosEvent();
                
                $friend_near = false;
                
		foreach($liste as $ligne) 
		{
			$rel_x = $ligne['pos_x'] - $x;
			$rel_y = $ligne['pos_y'] - $y;
			
                        $this->memorizePersosVue($ligne);
                        
			switch($this->seekMemory($ligne['perso_id']))
			{
				case 'friend':
					$level = $this->dna->reflex->niveauAmis;
                                        $friend_near = $ligne;
					break;
				case 'stranger':
					$level = $this->dna->reflex->niveauEtranger;
					break;
				case 'danger':
					$level = $this->dna->reflex->Danger;
					break;
				default:
					$level = $this->dna->reflex->niveauDefaut;
			}
			
			$this->updateGrid($rel_x, $rel_y, $level);
		}
                
		if($this->dangergrid[0][0] < $this->dna->reflex->seuilNeutre)
		{
			$this->data->status--;
			// neutre
		}
		elseif($this->dangergrid[0][0] < $this->dna->reflex->seuilVigilant)
		{
			// vigilant
		}
		else
		{
			$this->data->status = $this->dna->reflex->valeurPanique;
			// en panique
		}	
                
                if($this->data->status <= $this->dna->reflex->seuilPaix)
                {
                    if(rand(0,$this->dna->reflex->fertilite) == $this->dna->reflex->fertilite)
                    {
                        if($friend_near) 
                        {
                            $this->Procreate(unserialize($friend_near['dna']));
                        }
                    }
                }
                
		return $this->chooseDirection();
		
	}
	
	function Action($move) 
	{
		$x = $move[0] + $this->data->pos_x;
		$y = $move[1] + $this->data->pos_y;
		$id = $this->id;
                
                $new_pos['pos_x'] = $x;
                $new_pos['pos_y'] = $y;
                $new_pos['plan']    = $this->data->carte_id;
                
		if(pos_is_free($new_pos)) 
                {
			
                        $this->bdd->exec("UPDATE damier_persos SET pos_x = $x, pos_y = $y WHERE perso_id = $id");
                        
                        $em = new eventManager();
                        $ev1 = $em->createEvent('mouv');
                        $ev1->setSource($id, 'perso');
                        $ev1->infos->addPrivateInfo('x',$x);
                        $ev1->infos->addPrivateInfo('y',$y);
                        $ev1->infos->addPrivateInfo('p','IA module');                        
                        
                        
		}else
		{
			// position occupée
		}
	}
	
	function Planifiate()
	{
  
		$prio = 4 - max(1,round(min(12,$this->data->status) / 4));
		//$time = date("Y-m-d H:i:s", ($prio * 60) + time()); // TESTS
		$time = date("Y-m-d H:i:s",($prio * 60 * 60 * 2) + time()); // prochain tour
                   
                $this->dna->lasttour = time();
                $this->dna->id = $this->id;
                
                $this->cleanMemory();
                
		$dna = serialize($this->dna);
                
                $type = $this->dna->type;
		
		$sql = "UPDATE persos_ia SET time = '$time', dna = '$dna', type = '$type' WHERE id = ".$this->id;
		
		$this->bdd->exec($sql);
	}
	
        function Procreate($otherdna)
        {
            try {
                
                $id = $this->createPerso('Mini-Ganesh', $this->data);
                
                $newdna = $this->createDNA($this->dna->type);
                
                $newdna->pere = $this->id;
                $newdna->mere = (isset($otherdna->id)) ? $otherdna->id : null;
                $newdna->id = $id;
            
                $newdna->memory->amis = $this->mergeDNAbranch($otherdna->memory->amis, $this->dna->memory->amis, 1);
                $newdna->memory->danger = $this->mergeDNAbranch($otherdna->memory->danger, $this->dna->memory->danger, 1.5);
                $newdna->memory->stranger = $this->mergeDNAbranch($otherdna->memory->stranger, $this->dna->memory->stranger, 3);
                
                $newdna->reflex = $this->mergeDNAbranch($otherdna->reflex, $this->reflex, 1, 100);
                
                $dna = serialize($newdna);
                $type = $this->dna->type;

                $time = date("Y-m-d H:i:s",(4 * 60 * 60 * 2) + time()); // prochain tour
                
		$sql = "INSERT INTO persos_ia (id, time, dna, type) VALUES('$id', '$time', '$dna', '$type')";

		$this->bdd->exec($sql);                
            
            } catch(Exception $e)
            {
                
            }
            
        }
        
        function createPerso($pseudo, $perso)
        {
            
                    $sql = "INSERT INTO persos (
                                `id`, `background`, `description_affil`, `utilisateur_id`, `nb_suicide`, `race_id`,
                                `superieur_id`, `grade_id`, `faction_id`, `nom`, `creation_date`, `date_tour`,
                                `avatar_url`, `icone_id`, `galon_id`, `options`, `mdj`, `signature`, `sexe`, `pnj`)
                        VALUES (
                                NULL, '', '', 1, '', 10,
                                null, 0, '', '$pseudo', CURRENT_TIMESTAMP(), '',
                                '', '', '', '0', '', '', '".rand(1,2)."', 1)";
                    //echo $sql;
                    $this->bdd->exec($sql);
            
            
                    $id_perso = $this->bdd->lastId();

                    $sql = "INSERT INTO `caracs_alter` (
                                `perso_id`, `alter_pa`, `alter_mouv`, `alter_def`, `alter_att`,
                                `alter_recup_pv`, `alter_force`, `alter_perception`, `nb_desaffil`, `alter_niv_mag`)
                        VALUES (
                                '$id_perso', '', '', '', '',
                                '', '', '', '', '')";
                    //echo $sql;
                    $this->bdd->exec($sql);

                    //-- Caracteristique de base des races
                    $caracs_base = caracs_base (10, 0);

                    $px = 0;
                    $pi = 0;
                    $pv = $caracs_base['pv'];
                    $recup_pv = $caracs_base['recup_pv'];
                    $malus_def = 0;
                    $niv = $caracs_base['magie'];
                    $mouv = $caracs_base['mouv'];
                    $pa = $caracs_base['pa'];
                    $des_attaque = floor($caracs_base['des']/2);
                    $force = $caracs_base['force'];
                    $perception = $caracs_base['perception'];
                    $res_mag = $caracs_base['res_mag'];

                    $sql = "INSERT INTO `caracs` (
                                            `perso_id`, `px`, `pi`, `pv`, `recup_pv`, `malus_def`,
                                            `niv`, `cercle`, `mouv`, `pa`, `pa_dec`,
                                            `des_attaque`, `maj_des`, `force`, `perception`,`res_mag`)
                                    VALUES (
                                            '$id_perso', '$px', '$pi', '$pv','$recup_pv', '$malus_def',
                                            '$niv', '', '$mouv', '$pa', '',
                                            '$des_attaque', '', '$force', '$perception', '$res_mag')";
                    //echo $sql;
                    $this->bdd->exec($sql);
                    
                    $ok = false;
                    $nb_essai = 0;
                    
                    $x1 = $perso->pos_x - 5;
                    $x2 = $perso->pos_x + 5;
                    
                    $y1 = $perso->pos_y - 5;
                    $y2 = $perso->pos_y + 5;    
                    
                    $x_min = min($x1,$x2);
                    $x_max = max($x1,$x2);
                    $y_min = min($y1,$y2);
                    $y_max = max($y1,$y2);  
                    
                    while (!$ok && $nb_essai<20) {
                            $new_pos['plan']    = $perso->carte_id;
                            $new_pos['pos_x']    = rand($x_min, $x_max);
                            $new_pos['pos_y']    = rand($y_min, $y_max);

                            $ok = pos_is_free($new_pos);
                            $nb_essai++;
                    }
                    
                    if($ok) {
                        $carte = $perso->carte_id;
                        $x = $new_pos['pos_x'];
                        $y = $new_pos['pos_y'];
                        $sql = "INSERT INTO damier_persos (perso_id, pos_x, pos_y, carte_id) VALUES ($id_perso, $x, $y, $carte)";
                        //echo $sql;
                        $this->bdd->exec($sql);
                    } else {
                        //throw new Exception("impossible de spawner");
                    }
                    
                    return $id_perso;
        }
        
        
        private function mergeDNAbranch($dna1, $dna2, $factor, $mutate = 0)
        {
            $array = (Object) array();
            
            foreach($dna1 as $key => $data)
            {
                $array->$key = $data / $factor;
            }
            
            
            foreach($dna2 as $key => $data) {
                if(isset($array->$key))
                {
                    
                    $array->$key = (((($array->$key * $factor) + $data)/2) / $factor);
                } else {
                    $array->$key = $data / $factor;
                }
            }   
            
            foreach($array as $key => $data) {
                $mutation = 0;
                if($mutate != 0)
                {
                    $mutation = floor(rand(0,$mutate)/$mutate);
                    if(rand(0,1)==0)
                    {
                        $mutation = 0 - $mutation;
                    }
                }
                $array->$key = round($data + $mutation);
            }
            
            return $array;
        }
        
	public function updateGrid($x, $y, $level)
	{
		$boucle = abs($level);
		$grid = array();
		
		global $LVL;
		
		for($i = $x-$boucle; $i <= $x+$boucle; $i++)
		{
			for($j = $y-$boucle; $j <= $y+$boucle; $j++)
			{
				$xp = abs($i - $x);
				$yp = abs($j - $y);			
				$ind = $xp . ',' . $yp;
				if(isset($this->dangergrid[$i][$j])) {
					$this->dangergrid[$i][$j] += min(10,max(0,($LVL[$ind] * $level)));
				}
			}		
		}			

	}
	
        private function memorizePersosEvent()
        {
            
            //print_r($this);
            
            if(!isset($this->dna->lasttour))
            {
                $this->dna->lasttour = (60 * 60 * 2);
            }
                        
            $sql = "SELECT * FROM evenements WHERE date_ev > '".date("Y-m-d H:i:s", $this->dna->lasttour)."' AND id_perso_desti = " . $this->id;
            //echo $sql;
            $this->bdd->query($sql);

            $liste = $this->bdd->fetch_assoc();	 
            
            //var_dump($liste);
            
            if($liste) {
            
                foreach($liste as $ligne)
                {
                    
                    $id = $ligne['id_perso_source'];
                    
                    if(isset($this->dna->memory->danger->$id))
                    {
                        $this->dna->memory->danger->$id += (1 * floor($this->dna->memory->danger->$id / $this->dna->reflex->additionDanger));
                    }
                    else
                    {
                        $this->dna->memory->danger->$id = $this->dna->reflex->additionDanger;
                    }

                }
            
            }
            
        }
        
        private function memorizePersosVue($perso) {
            
            //print_r($perso);
            
            $id = $perso['perso_id'];
            
            if($perso['type'] == $this->dna->type)
            {
                $this->dna->memory->amis->$id = $this->dna->reflex->additionAmis;
            } else {
                
                if(isset($this->dna->memory->stranger->$id))
                {
                    $this->dna->memory->stranger->$id += (1 * floor($this->dna->memory->stranger->$id / $this->dna->reflex->additionEtranger));
                }
                else
                {
                    $this->dna->memory->stranger->$id = $this->dna->reflex->additionEtranger;
                }
            }
            
        }
        
        private function cleanMemory()
        {
            foreach($this->dna->memory->amis as $id => $mem)
            {
                $mem--;
                if($mem == 0)
                {
                    unset($this->dna->memory->amis->$id);
                }
            }
            
            foreach($this->dna->memory->stranger as $id => $mem)
            {
                $mem--;
                if($mem == 0)
                {
                    unset($this->dna->memory->stranger->$id);
                }
            }
            
            foreach($this->dna->memory->danger as $id => $mem)
            {
                $mem--;
                if($mem == 0)
                {
                    unset($this->dna->memory->danger->$id);
                }
            }            
        }


        private function seekMemory($info)
	{
            if(isset($this->dna->memory->danger->$info))
                return 'danger';
            
            if(isset($this->dna->memory->stranger->$info)) {
                if($this->dna->memory->stranger->$info > $this->dna->reflex->seuilEtranger)
                    return 'danger';
                elseif($this->dna->memory->stranger->$info > $this->dna->reflex->seuilDangerPotentiel)
                {
                    return 'stranger';
                }
            }
            
            if(isset($this->dna->memory->amis->$info))
                return 'friend';            
                  
            return '';
	}
	
	
	//ok
	private function chooseDirection()
	{		
           
                if($this->data->status > $this->dna->reflex->seuilFuite) {
                    $directions = $this->selectCasesAlerte();
                } else {
                    $directions = $this->selectCasesNeutre();
                }
		

		
		return $directions;
	}
	
	//ok
	private function selectCasesNeutre()
	{
		$arr = array(
		array(-1,-1),
		array(-1,0),
		array(-1,1),
		array(0,-1),
		array(0,0),
		array(0,1),
		array(1,-1),
		array(1,0),
		array(1,1)		
		);
		
		shuffle($arr);
		
		return current($arr);
	}	
	
	//ok
	private function selectCasesAlerte()
	{
		$min = 100;
		$directions = array();
		
		for($x = -1; $x <= 1; $x++)
		{
			for($y = -1; $y <= 1; $y++)
			{
				if($this->dangergrid[$x][$y] < $min) {
					$min = $this->dangergrid[$x][$y];
					$directions = array(array($x,$y));
				} elseif($this->dangergrid[$x][$y] == $min)
				{
					$directions[] = array($x,$y);
				}
			}		
		}
		
		$dir = rand(0,count($directions)-1);
		
		return $directions[$dir];
	}
        
        private function createDNA($type)
        {
            $dna = (Object) Array();
            $dna->type = $type;
            
            $dna->reflex = $this->baseReflexes();
                       
            $dna->memory = (Object) Array();
            
            $dna->memory->amis = (Object) Array();
            $dna->memory->stranger = (Object) Array();
            $dna->memory->danger = (Object) Array();
            
            return $dna;
        }
        
        private function baseReflexes()
        {
           $reflex = (Object) Array();
            $reflex->seuilFuite=6;
            $reflex->seuilDangerPotentiel=10;
            $reflex->seuilEtranger=3;
            $reflex->additionEtranger=1;
            $reflex->additionAmis=10;
            $reflex->additionDanger=10;
            $reflex->fertilite=20;
            $reflex->seuilPaix=0;
            $reflex->valeurPanique=10;
            $reflex->seuilVigilant=9;
            $reflex->seuilNeutre=5;
            $reflex->niveauAmis=-1;
            $reflex->niveauEtranger=2;
            $reflex->niveauDanger=5;
            $reflex->niveauDefaut=1;    
            
            return $reflex;
        }

}

/*
DNA : Valeur de config de l'IA (par exemple nom du chef, race "allié", etc
Data : valeurs courantes
  
Status : 
  Neutre -> rien de particulier
  Alerté -> en �tat d'alerte. Chance de fuite
  Panique -> en mode panique. Va fuir
  Fertil -> possible uniquement si neutre, si l'IA passe � ce status une nouvelle IA "enfant" sera cr�e

*/