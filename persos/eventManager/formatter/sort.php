<?php
/*
 * state:
 * = 0 : rate
 * = 1 : reussi
 * = 2 : esquive
 */

include_once('formatter.php');

class sort extends formatter{

	private static $sort = array();

	public function printPublic(&$bdd){
		$info = '';
		$public = parent::getEvent()->infos->getPublicInfos();
		switch(parent::getEvent()->getState()){
			case 5:
				$info = 'a <u>tu&eacute;</u> par le <b>sort</b>:<br/>';
				break;
			case 4:
				$info = 'a <u>d&eacute;truit</u> par le <b>sort</b>:<br/>';
				break;
			case 2:
				$info = 'a vu son <b>sort</b> <u>esquiv&eacute;</u>:<br/>';
				break;
			case 1:
				$info = 'a <u>lanc&eacute;</u> le <b>sort</b>:<br/>';
				break;
			case 0:
			default:
				$info = 'a <u>rat&eacute;</u> le <b>sort</b>:<br/>';
				break;
		}
		if(isset($public['s']) && isset($public['c'])) {
			$info .= '-&nbsp;<i>'.self::getSort($bdd, $public['s'], $public['c']).'</i>';
		} elseif(isset($public['s'])) {
			$info .= '-&nbsp;<i>'.self::getSort($bdd, $public['s']).'</i>';
		}
		return $info;
	}
	public function printPrivate(&$bdd){
		$private = parent::getEvent()->infos->getPrivateInfos();
		$deg=(isset($private['deg']))?$private['deg']:null;
		$res=parent::chkSrc(parent::getEvent()->getSrc(),parent::getEvent()->getDst());
		if($res){
			$xp = $private['xpA'];
		}elseif(!$res){
			$xp = $private['xpD'];
		}
		return (($deg != null)?'D&eacute;g&acirc;ts ('.$deg.')<br/>':'')
		.'Vos gains: '.$xp.' XP';
	}

	public function printGeneral(&$bdd){
		return $this->printPublic($bdd);
	}

	public function getBackground(){
		switch(parent::getEvent()->getState()){
			case 5:
			case 4:
				return '#FFCCCC';
			case 2:
				return '#CCCCDD';
			default:
				return '#99CCCC';
		}
	}

	private function getSort($bdd, $id, $camp = 0){
		if(isset($id) && is_numeric($id) && !isset(self::$sort[$id.':'.$camp])){
			$sql = "SELECT nom FROM action WHERE id = $id;";
			$res = mysql_query ($sql) or die (mysql_error());
			$pos = mysql_fetch_array ($res);
			$pos['nom'] = explose_nom_action($pos['nom'],$camp);
			self::$sort[$id.':'.$camp]=$pos['nom'];
			mysql_free_result($res);
		}
		return self::$sort[$id.':'.$camp];
	}
}
