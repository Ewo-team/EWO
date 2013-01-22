<?php

if(!function_exists('apc_exists')){
	function apc_exists($keys){
		$r;
		apc_fetch($keys,$r);
		return $r;
	}
}

class VariableStorage {

	// Dur�e de vie d'un lock. 60 secondes
	private static $ttl = 60;

	static function Charge($uri, $ssid) {
		// Boucle tant que la variable est lock�
		while(apc_add('_'.$uri, $ssid, static::$ttl)) {
			return apc_fetch($uri);
		}
	}
	
	static function Sauve($uri, $variable, $ssid) {
		$curi = '_'.$uri;
		if(!apc_add($curi, $ssid, static::$ttl)) {
			// il y a un lock, on v�rifie qu'on en est le propri�taire, 
			if(apc_fetch($curi) === $ssid) {
				// sauvegarde, et lib�re le lock
				apc_store($uri, $variable);
				apc_delete($curi);
				return true;
			}
			// On n'est pas le propri�taire, il y a un probl�me
		} else {
			// Il n'y avais pas de lock, il y a donc un probl�me
			apc_delete($curi);
		}

		return false;
	}
	
	static function Consulte($uri) {
		$r;
		$result = apc_fetch($uri,$r);
		return ($r) ? $result : false;
	}
	
	static function Temporisation($uri, $ttl) {
	
		// On tente de mettre une variable en magasin
		try {
			$refresh = @apc_add('-'.$uri, time(), $ttl);
		} catch(Exception $e) {
			$refresh = 1;
		}
		
		// Si la variable a �t� ajout� (et donc n'existait pas), 
		// c'est que le cache � expir�, ou n'a jamais exist�.
		if($refresh) {
			return 1;		
		}	
		return 0;
	}
} 

//Fallback if apc in not present
if(!function_exists('apc_fetch')){
	function apc_fetch($p1=null,$p2=null,$p3=null){
		return false;
	}
	function apc_add($p1=null,$p2=null,$p3=null){
		return false;
	}	
	function apc_delete($p1=null,$p2=null,$p3=null){
		return false;
	}	
	function apc_store($p1=null,$p2=null,$p3=null){
		return false;
	}		
}
?>
