<?php

namespace persos\eventManager;

class tools {
	protected static function checkPrivate($mat){
		$persos = (isset($_SESSION['persos']['id']))?$_SESSION['persos']['id']:array();
		if(isset($persos[0])){
			unset($persos[0]);
		}
		if(in_array($mat,$persos)){
			$_SESSION['persos']['priv_id'] = $_SESSION['persos']['id'][array_search($mat,$persos)];
			return TRUE;
		}else{
			return FALSE;
		}
	}

	protected static function chkSrc($src, $dst){
		if(isset($_SESSION['persos']['current_id']) && $_SESSION['persos']['current_id'] == $src){
			return TRUE;
		}elseif(isset($_SESSION['persos']['current_id']) && $_SESSION['persos']['current_id'] == $dst){
			return FALSE;
		}elseif(isset($_SESSION['persos']['priv_id']) && $_SESSION['persos']['priv_id'] == $src){
			return TRUE;
		}elseif(isset($_SESSION['persos']['priv_id']) && $_SESSION['persos']['priv_id'] == $dst){
			return FALSE;
		}else{
			return null;
		}
	}
}
?>