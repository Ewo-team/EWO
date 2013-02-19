<?php

namespace conf;

class VariableStorage {

	// Durée de vie d'un lock. 60 secondes
	private static $ttl = 60;

	static function Charge($uri) {
		// Boucle tant que la variable est locké
		while(apc_add('_'.$uri, session_id(), static::$ttl)) {
			return apc_fetch($uri);
		}
	}
	
	static function Verrouille($uri, $ttl = null) {
		$tempo = ($ttl==null) ? static::$ttl : $ttl; // On utilise la ttl si spécifié, ou sinon celle par défaut
		$tempo = ($tempo==0) ? static::$ttl : $tempo; // Si la ttl spécifié est à 0, on utilise celle par défaut
		return apc_add('_'.$uri, session_id() , $tempo);
	}
	
	static function LibereVerrou($uri) {
		if(apc_fetch('_'.$uri) === session_id()) {
			apc_delete('_'.$uri);
		}
	}
	
	static function Sauve($uri, $variable, $ttl = null) {
		$curi = '_'.$uri;
		if(!VariableStorage::Verrouille($uri,$ttl)) {
			// il y a un lock, on vérifie qu'on en est le propriétaire, 
			if(apc_fetch($curi) === session_id()) {
				// on sauvegarde, et libère le lock
				$result = apc_store($uri, $variable);
				apc_delete($curi);
				return $result;
			}
			// On n'est pas le propriétaire, il y a un problème
		} else {
			// Il n'y avais pas de lock
			$result = apc_store($uri, $variable);
			
			apc_delete($curi);
			return $result;
		}

	}
	
	static function Exists($uri) {
		return apc_exists($uri);
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

	$path = __DIR__ . '/../cache/'; 

	function apc_fetch($p1=null,&$p2=null,$p3=null){
		$p2 = false;
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

// Fallback pour les vieilles version de apc
if(!function_exists('apc_exists')){
	function apc_exists($keys){
		$r;
		apc_fetch($keys,$r);
		return $r;
	}
}
?>
