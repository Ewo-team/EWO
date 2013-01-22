<?php
$root = '..';
if(isset($root_url)){
	$root = $root_url;
}
include_once($root.'/event/eventFormatter.php');

function eventautoload($name){
	if(isset($root_url)){
		$root = $root_url;
	}else{
		$root = '..';
	}
	if(file_exists($root."/event/formatter/$name.php")){
		require_once($root."/event/formatter/$name.php");
	}
}

// Enregistre la fonction comme chargeur automatique de classe
spl_autoload_register('eventautoload');

class eventInfos {

	private $private = array();
	private $public = array();

	function __construct($public, $private){
		if(isset($public) && $public != null){
			$tmp = unseritab($public);
			if (!is_array($tmp))
			$this->public = array();
			else
			$this->public = $tmp;
		}
			
		if(isset($private) && $private != null){
			$tmp = unseritab($private);
			if (!is_array($tmp))
			$this->private = array();
			else
			$this->private = $tmp;
		}
	}

	function addPrivateInfo($key, $value){
		$this->private[$key] = $value;
	}

	function addPublicInfo($key, $value){
		$this->public[$key] = $value;
	}

	function getPublicInfos(){
		return $this->public;
	}

	function getPrivateInfos(){
		return $this->private;
	}

	function sql($bdd){
		return "'".mysql_real_escape_string(seritab($this->public,$bdd),$bdd)."','".mysql_real_escape_string(seritab($this->private,$bdd),$bdd)."'";
	}
}

class event {
	private $src = 0, $dst = 'NULL', $state = 0, $id = -1, $sub = 0;
	private $src_name = NULL, $dst_name = NULL, $actor_type =array(0=>1, 1=>NULL);
	private $type = NULL, $master = NULL, $date = NULL;
	public $infos = NULL;

	function __construct($date, $type, $public=null, $private=null){
		$this->type = $type;
		$this->date = $date;
		$this->infos = new eventInfos($public, $private);
	}

	public function getSrc(){
		return $this->src;
	}
	public function getSrcName(){
		return $this->src_name;
	}
	public function getSrcType(){
		return $this->actor_type[0];
	}
	public function getDst(){
		return $this->dst;
	}
	public function getState(){
		return $this->state;
	}
	public function getDstName(){
		return $this->dst_name;
	}
	public function getDstType(){
		return $this->actor_type[1];
	}
	public function getType(){
		if(class_exists($this->type))
		return new $this->type($this);
		else
		return NULL;
	}
	public function getDate(){
		return $this->date;
	}

	public function getSub(){
		return $this->sub;
	}
	public function setSub($nb){
		if(is_numeric($nb) && $nb > 0){
			$this->sub = $nb;
		}
	}
	public function getID(){
		return $this->id;
	}
	public function setID($id){
		if(isset($id) && is_numeric($id)){
			$this->id = $id;
		}
	}
	public function setSource($src, $type='perso', $name = null){
		if($name == NULL){
			$src = $this->caseID($src, $type);
		}
		if(is_numeric($src)){
			$this->src = $src;
			$this->src_name = $name;
			if(is_numeric($type)){
				$this->actor_type[0] = $type;
			}else{
				$this->actor_type[0] = eventFormatter::convertType($type);
			}
		}
	}

	public function setAffected($mat, $type='perso', $name = null){
		if($name == NULL){
			$mat = $this->caseID($mat, $type);
		}
		if(is_numeric($mat)){
			$this->dst = $mat;
			$this->dst_name = $name;
			if(is_numeric($type)){
				$this->actor_type[1] = $type;
			}else{
				$this->actor_type[1] = eventFormatter::convertType($type);
			}
		}
	}
	private function caseID($id, $type){
		if(is_numeric($type)){
			$type = eventFormatter::convertType($type);
		}
		switch($type){
			case 'objet_simple':
			case 'objet_complexe':
			case 'artefact':
				if(isset($_SESSION['case_id'][$id])){
					return $_SESSION['case_id'][$id];
				}
		}
		return $id;
	}
	public function setMaster(&$ev){
		if(isset($ev) && is_object($ev)){
			$this->master = $ev;
		}
	}

	public function getMaster(){
		if(isset($this->master) && is_numeric($this->master->id)){
			return $this->master->id;
		}else{
			return null;
		}
	}

	public function setState($val){
		if(is_numeric($val))
		$this->state = $val;
		else
		$this->state = 0;
	}

	//retourne la requete SQL - ATTENTION � la taille des infos
	function sql($bdd){
		if(is_numeric($this->src) && isset($bdd) && $bdd != NULL){
			if($this->master != NULL && $this->master->id >= 0){
				$id = $this->master->id;
			}else{
				$id = 'NULL';
			}

			return "INSERT ewo.evenements ".
		"(id, id_perso_source, type_source, id_perso_desti, type_desti, id_event, date_ev, type_ev, public_data, private_data, result) ".
		"VALUES (NULL, $this->src, ".(($this->actor_type[0] != NULL)?$this->actor_type[0]:'NULL').", $this->dst, ".(($this->actor_type[1] != NULL)?$this->actor_type[1]:'NULL')." , $id ,'$this->date', '$this->type', ".$this->infos->sql($bdd).", $this->state);";
		}else
		return ';';
	}
}