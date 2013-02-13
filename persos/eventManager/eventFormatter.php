<?php

namespace persos\eventManager;


class eventFormatter extends tools {

	public static $LIMIT_EVENT = 20;
	// perso / objet_simple / objet_complexe / artefact / porte / bouclier
	private static $TYPE_ACTOR = array(
        'perso' => 1, 'persos' => 1, 'objet_simple' => 2, 'objet_complexe' => 3, 'artefact' => 4, 'porte' => 5, 'bouclier' => 6,'legion' => 7, 'faction' => 7, 'affilia' => 8, 'tp' => 9,
	1 => 'perso', 2 => 'objet_simple', 3 => 'objet_complexe', 4 => 'artefact', 5 => 'porte', 6 => 'bouclier', 7 => 'legion', 8 => 'affilia', 9 => 'tp',
            'max' => 9);

	private static function getPersoEvents($bdd, $mat, $from){

		$return = array();
		if($bdd != null && is_numeric($mat) && is_numeric($from)){

			$sql = "SELECT ev.*, COUNT(*) as sub  ".
			"FROM evenements AS ev ".
			"WHERE ((id_perso_source = $mat AND type_source = 1) ".
			"OR (id_perso_desti = $mat AND type_desti = 1)) ".
			"GROUP BY ev.id_perso_source, ev.type_ev, ev.id_event ".
			"ORDER BY ev.id DESC LIMIT $from,".self::$LIMIT_EVENT.";";

			//echo $sql;

			$res = mysql_query($sql, $bdd);
			$last = 0;
			$name = array();

			while($row = mysql_fetch_assoc($res)){

				$event = new event($row['date_ev'], $row['type_ev'], $row['public_data'], $row['private_data']);

				$event->setSource($row['id_perso_source'], self::convertType($row['type_source']), self::getName($name, $bdd, $row['id_perso_source'], $row['type_source']));
				$event->setAffected($row['id_perso_desti'], self::convertType($row['type_desti']), self::getName($name, $bdd, $row['id_perso_desti'], $row['type_desti']));

                                $event->setState($row['result']);
				// id du master event
				$event->setID($row['id_event']);
				$event->setSub($row['sub']);
				$last = $row['id'];

				$return[$last] = $event;

			}
			mysql_free_result($res);
		}
		return $return;
	}

	public static function printEvents(&$bdd, $mat, $from=0, $world=false){

		$event = array();
		if($bdd != null && is_numeric($mat) && is_numeric($from)){
			if(!$world){
				$events = self::getPersoEvents($bdd, $mat, $from);
			}else{
				$events = self::getWorldEvents($bdd);
			}

			$icones = array();

			foreach($events as $key=>$event){

				$format = $event->getType();;

				if($format != NULL){

					echo '<tr style="background-color:'.$format->getBackground().';" id="event'.$key.'">';
					echo self::printEvent($bdd,$event,$format,$key,!$world);
					echo '</tr>';
				}
			}
		}
	}

	public static function printEvent(&$bdd, &$event, &$format, $key, $show=true){
		$dst = $event->getDst();
		$ret = '';

		if($format != NULL){
			$prive = ((self::checkPrivate($event->getSrc()) || self::checkPrivate($dst)) && $show);
			$ret .= '<td>'.$event->getDate().'</td>'.
				 '<td width="44"><img src="'.self::getIcone($icones, $bdd, $event->getSrc(), $event->getSrcType()).'" alt="avatar" width="44"></td>'.
				 '<td width="70">'.self::printNameMat($event, 'getSrc').'</td>';
			if($event->getSub()>1){
				$ret .='<td width="*">'.$format->printGeneral($bdd);
			}else{
				$ret .='<td width="*">'.$format->printPublic($bdd);
				$ret .= (($prive)?'<br/><div style="font-size:smaller;margin-left:10px;">'.$format->printPrivate($bdd).'</div>':'');
			}
			$ret .= '</td>';
			if($event->getSub()>1){
				$type_ev = get_class($format);
				if($type_ev === "sort"){
					$ret .= '<td colspan="2" style="vertical-align:bottom;padding:4px;cursor:pointer;" onclick="showTarget('.$key.','.$key.','.($event->getSub()-1).','.$event->getSrc().');"><b>Afficher</b> le d&eacute;tail';
				}else{
					$ret .= '<td colspan="2" style="vertical-align:bottom;padding:4px;cursor:pointer;" onclick="showTarget('.$key.',\''.$event->getDate().'\','.$event->getSub().','.$event->getSrc().');"><b>Afficher</b> le d&eacute;tail';
				}
			}elseif(isset($dst) && $dst != NULL && is_numeric($dst)){
				$ret .= '<td width="70">'.self::printNameMat($event, 'getDst').'</td>';
				$ret .= '<td width="44"><img src="'.self::getIcone($icones, $bdd, $event->getDst(), $event->getDstType()).'" alt="avatar" width="44">';
			}else{
				$ret .= '<td colspan="2" style="background-color:#E9E6C3;">';
			}
			$ret .= '</td>';
		}else{
			$ret .= '<td colspan="6">/!\ Erreur de formattage /!\<br/>Veuillez contacter un admin, merci.</td>';
		}
		return $ret;
	}

	public static function getIcone(&$icones, &$bdd, $mat, $type='perso'){
		$ret = '../images/transparent.png';
		$type = ((is_numeric($type))?self::$TYPE_ACTOR[$type]:$type);
		if(isset($icones[$type][$mat]) && $icones[$type][$mat] != NULL){
			$ret = $icones[$type][$mat];
		}else{
			switch($type){
				case 'perso':
					$icones[$type][$mat] = SERVER_URL . '/images/'.icone_persos($mat);
					$ret = $icones[$type][$mat];
					break;
				case 'objet_complexe':
				case 'objet_simple':
				case 'artefact':
					$sql = "SELECT cs.image ".
					"FROM case_$type AS cs ".
					"WHERE cs.id = $mat;";
					$res = mysql_query($sql,$bdd);
					if($res && ($row=mysql_fetch_row($res))){
						$icones[$type][$mat]='../images/'.$row[0];
						$ret = $icones[$type][$mat];
					}
					break;
				case 'porte':
				case 'bouclier':
					$sql = "SELECT nom_image FROM damier_$type WHERE id = $mat";
					$res = mysql_query($sql,$bdd);
					if($res && ($row=mysql_fetch_row($res))){
						$name[$type][$mat] = '../images/decors/'.$type.'s/'.$row[0].'.png';
						$ret = $name[$type][$mat];
					}
					break;
				default:
					$ret = '../images/fail.png';
					break;
			}
		}
		return $ret;
	}
	private static function printNameMat(&$event, $func){
		$ret = '';
		$funcName = $func.'Name';
		$funcType = $func.'Type';
		$type = $event->$funcType();
		$type = ((is_numeric($type))?self::$TYPE_ACTOR[$type]:$type);
		switch($type){
			case 'perso':
				$ret = '<span style="white-space:nowrap;">'.((mb_strlen($event->$funcName(),'UTF-8')>= 11)?mb_substr($event->$funcName(),0,9,'UTF-8').'..':$event->$funcName()).'</span>';
				$ret .= '<br/>&nbsp;&nbsp;<span style="font-size:smaller;">Mat. </span><a href="../event/liste_events.php?id='.$event->$func().'">'.$event->$func().'</a>';
				break;
			case 'objet_simple':
			case 'objet_complexe':
			case 'artefact':
                        case 'legion':
				$ret = '<span>'.$event->$funcName().'</span>';
				break;
			case 'porte':
			case 'bouclier':
				$ret = '<span>'.$event->$funcName().'</span>';
				break;
			default:
				break;
		}
		return $ret;
	}

	public static function getName(&$name, &$bdd, $id, $type){

		$type = ((is_numeric($type))?self::$TYPE_ACTOR[$type]:$type);

		if(isset($name[$type][$id])){
			return $name[$type][$id];
		}
		switch($type){
			case 'perso':
				$sql = "SELECT nom FROM persos WHERE id = $id";
				$res = mysql_query($sql, $bdd);
				if($res && ($row=mysql_fetch_row($res))){
					$name[$type][$id] = $row[0];
					return $row[0];
				}
				return 'perso';
			case 'objet_simple':
			case 'objet_complexe':
			case 'artefact':
				$sql = "SELECT nom FROM case_$type WHERE id = $id";
				$res = mysql_query($sql, $bdd);
				if($res && ($row=mysql_fetch_row($res))){
					$name[$type][$id] = $row[0];
					return $row[0];
				}else{
					return '<b>'.$type.' disparu(e)</b>';
				}
			case 'porte':
			case 'bouclier':
				$sql = "SELECT nom, nom_image FROM damier_$type WHERE id = $id";
				$res = mysql_query($sql, $bdd);
				if($res && ($row=mysql_fetch_row($res))){
					$name[$type][$id] = $row[0].'<br/><span style="font-size:smaller;">('.$row[1].')</span>';
					return $name[$type][$id];
				}else{
					return '<b>'.$type.' disparu(e)</b>';
				}
                        case 'legion':
                                $sql = 'SELECT nom FROM factions WHERE id = '.$id;
                                $res = mysql_query($sql, $bdd);
                                if($res && ($row=mysql_fetch_row($res))){
                                    return $row[0];
                                }else{
                                    return '<b>'.$type.' disparu(e)</b>';
                                }
			default:
				return 'OOPS!';
		}
	}

	public static function convertType($type){
		if(is_numeric($type) && $type > 0 && $type <= self::$TYPE_ACTOR['max']){
			return self::$TYPE_ACTOR[$type];
		}else{
			$type = preg_split("/_[0-9]+/", $type);
			if(isset(self::$TYPE_ACTOR[$type[0]])){
				return self::$TYPE_ACTOR[$type[0]];
			}else{
				return NULL;
			}
		}
	}

	public static function getNbEvents($bdd, $idP){
		if(is_numeric($idP) && $idP >= 0 && $bdd != NULL){
			$sql = "SELECT COUNT(*) AS nb FROM evenements WHERE (id_perso_source = $idP OR id_perso_desti = $idP) AND id_event = id";
			$res = mysql_query($sql, $bdd);
			$row = mysql_fetch_assoc($res);
			mysql_free_result($res);
			return $row['nb'];
		}
	}

	private static function getWorldEvents($bdd){
		$return = array();
		if($bdd != null){

			$sql = "SELECT ev.*, COUNT(*) as sub ".
			"FROM evenements AS ev ".
			"WHERE type_ev != 'mouv' AND type_ev != 'vacances' ".
			"GROUP BY ev.id_event ".
			"ORDER BY ev.id DESC LIMIT 0,".self::$LIMIT_EVENT.";";

			//echo $sql;

			$res = mysql_query($sql, $bdd);
			$last = 0;
			$name = array();
			while($row = mysql_fetch_assoc($res)){
				$event = new event($row['date_ev'], $row['type_ev'], $row['public_data'], $row['private_data']);
				$event->setSource($row['id_perso_source'], self::convertType($row['type_source']), self::getName($name, $bdd, $row['id_perso_source'], $row['type_source']));
				$event->setAffected($row['id_perso_desti'], self::convertType($row['type_desti']), self::getName($name, $bdd, $row['id_perso_desti'], $row['type_desti']));
				$event->setState($row['result']);
				// id du master event
				$event->setID($row['id_event']);
				$event->setSub($row['sub']);
				$last = $row['id'];
				$return[$last] = $event;
			}
			mysql_free_result($res);
		}
		return $return;
	}

	public static function getSubEvents(&$bdd, $key, $mat){
		$return = array();
		if($bdd != null && (($is_key=is_numeric($key)) || is_date($key))){
			$sql = "SELECT * ".
			"FROM evenements ".
			(($is_key)?"WHERE id_event = $key ": "WHERE date_ev = '$key' ").
			" AND (id_perso_source = $mat AND type_source = 1) ".
			"ORDER BY id DESC;";

			$res = mysql_query($sql, $bdd);
			while($row = mysql_fetch_assoc($res)){
				$event = new event($row['date_ev'], $row['type_ev'], $row['public_data'], $row['private_data']);
				$event->setSource($row['id_perso_source'], eventFormatter::convertType($row['type_source']), eventFormatter::getName($name, $bdd, $row['id_perso_source'], $row['type_source']));
				$event->setAffected($row['id_perso_desti'], eventFormatter::convertType($row['type_desti']), eventFormatter::getName($name, $bdd, $row['id_perso_desti'], $row['type_desti']));
				$event->setState($row['result']);
				// id du master event
				$event->setID($row['id_event']);
				$last = $row['id'];
				$return[$last] = $event;
			}
			mysql_free_result($res);
		}
		return $return;
	}
}
?>
