<?php
include_once('formatter.php');

class special extends formatter{

	function printPublic(&$bdd){
		$public = parent::getEvent()->infos->getPublicInfos();
		
		if(isset($public['m'])) {
			return self::getText($public['m']);
		}
		return '';
	}
	public function printPrivate(&$bdd){
		$private = parent::getEvent()->infos->getPrivateInfos();

		if(isset($private['p'])) {
                    return '-&nbsp;<i>'.self::getText($private['p']).'</i>';
		}
		return '';
	}
	public function getBackground(){
		return '#CCCCCC';
	}

	private function getText($id){
		return persos\eventManager\SPECIAL_EVENT::$TEXT[$id];
	}

}
