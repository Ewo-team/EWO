<?php
include_once('formatter.php');

class anima extends formatter{

	function printPublic(&$bdd){
		$public = parent::getEvent()->infos->getPublicInfos();

		if(isset($public['m'])) {
			return self::getText($bdd, $public['m']);
		}
		return '';
	}
	public function printPrivate(&$bdd){
		$private = parent::getEvent()->infos->getPrivateInfos();

		if(isset($private['p'])) {
                    return '-&nbsp;<i>'.self::getText($bdd, $private['p']).'</i>';
		}
		return '';
	}
	public function getBackground(){
		return '#CCCCCC';
	}

	private function getText($bdd, $id){
		$texte = '';
		if(isset($id) && is_numeric($id)){
			$sql = "SELECT texte FROM evenements_texte WHERE id = $id;";
			$res = mysql_query ($sql) or die (mysql_error());
			$pos = mysql_fetch_array ($res);
			$texte = $pos['texte'];
			mysql_free_result($res);
		}
		return $texte;
	}

}
