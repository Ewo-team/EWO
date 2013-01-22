<?php

if(!function_exists('apc_exists')){
	function apc_exists($keys){
		$r;
		apc_fetch($keys,$r);
		return $r;
	}
}

class VariableStorage {

	// Durée de vie d'un lock. 60 secondes
	private static $ttl = 60;

	static function Charge($uri, $ssid) {
		// Boucle tant que la variable est locké
		while(apc_add('_'.$uri, $ssid, static::$ttl)) {
			return apc_fetch($uri);
		}
	}
	
	static function Sauve($uri, $variable, $ssid) {
		$curi = '_'.$uri;
		if(!apc_add($curi, $ssid, static::$ttl)) {
			// il y a un lock, on vérifie qu'on en est le propriétaire, 
			if(apc_fetch($curi) === $ssid) {
				// sauvegarde, et libère le lock
				apc_store($uri, $variable);
				apc_delete($curi);
				return true;
			}
			// On n'est pas le propriétaire, il y a un problème
		} else {
			// Il n'y avais pas de lock, il y a donc un problème
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
		
		// Si la variable a été ajouté (et donc n'existait pas), 
		// c'est que le cache à expiré, ou n'a jamais existé.
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
