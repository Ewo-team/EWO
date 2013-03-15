<?php
	include('ref.php.inc');
	require_once __DIR__ . '/../../conf/master.php';
	require_once('class/AT.php.inc');
	
	include(SERVER_ROOT."/persos/fonctions.php");
	
	function get(){
		
		if(!ControleAcces('admin;at',0))
			return false;
		
		bdd_connect('ewo');
		
		if(isset($_SESSION['at']))
			$at = unserialize($_SESSION['at']);
		else{
			if(ControleAcces('admin',0))//Si c'est un admin on s'emmerde pas !
				$lvl = 5;
			else{
				$sql = '
					SELECT `lvl`
					FROM `at_members`
					WHERE `id` = '.$_SESSION['utilisateur']['id'].'
					;
				';
				
				$search = mysql_query($sql);
				$data 	= mysql_fetch_object($search);
				if(isset($data))
					$lvl 	= $data->lvl;
				else
					$lvl 	= 1;
			}
			
			
			$at = new AT($_SESSION['utilisateur']['id'],$lvl);
		}
		
		return $at;
	}
	
	function store($at){
		$_SESSION['at'] = serialize($at);
	}
?>
