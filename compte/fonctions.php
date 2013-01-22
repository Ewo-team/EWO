<?php
/**
 * Compte, fonctions
 *
 *	Fonctions utiles pour la page d'option du compte
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package compte
 * @category fonctions
 */	

$path = realpath(dirname($_SERVER['SCRIPT_FILENAME']));
$root_url = explode('/',$path);
array_pop($root_url);
$root_url = join('/',$root_url);

require_once('config_vacances.php');
include_once($root_url.'/event/eventManager.php');
include_once($root_url.'/event/eventFormatter.php');

if(!defined('CALL_FROM_CRON')){
	
	if(function_exists('template_list')){
		$template_list 		= template_list();
	}
	else{
		$template_list 		= array();
	}
	$liste['template']		= array();
	foreach($template_list as $t){
		$liste['template'][$t] = $t;
	}
	$liste['time']	= array('Immédiat' =>'0.0',
												'Effet défilement' => '0.1',
												'0.5 sec' => '0.5',
												'0.7 sec' => '0.7',
												'1 sec' => '1', 
												'1.2 sec' => '1.2',
												'1.5 sec' =>'1.5');
	$liste['redirection']	= array('Page accueil' => '1',
												'Liste des personnages' => '2',
												'Forum' => '3');
	$liste['rose']	= array('Rose des vents' => '0',
												'Flèches du damier' => '1');        
												
	$default_value = '-- Choix --';
}	
/**
 * Récupération des options actuelles dans la base de données
 */
function getUserOptions($utilisateur_id){		
	$sql = 'SELECT * FROM utilisateurs_option WHERE utilisateur_id = \''.$utilisateur_id.'\'';
	
	$res = mysql_query($sql);
	
	if(false === $res || mysql_num_rows($res) == 0){
		return false;
	}
	
	return mysql_fetch_assoc($res);		
	
}

/**
 *
 */
function getSelectOption($type, $choix){
		global $liste, $default_value;
		$html 		= array();
		
		$html[]	= '<select name="'.$type.'">';
		$html[] = '<option value="">'.$default_value.'</option>';
		foreach($liste[$type] as $label=>$value){
			$selected = '';
			if($value == $choix){
				$selected = ' selected="SELECTED" ';
			}
			$html[] = '<option value="'.$value.'"'.$selected.'>'.$label.'</option>';
		}
		$html[]	= '</select>';
		
		return join(PHP_EOL,$html);
}

/**
 *
 */
function getVacancesButton($id_utilisateur){
	$sql = 'SELECT * FROM utilisateurs_vacances WHERE utilisateur_id = '.$id_utilisateur;
	$res = mysql_query($sql);
	if(false === $res){
		//Erreur SQL
		return '<td colspan="2"><span>Indisponnible</a></td>';
	}
	elseif(mysql_num_rows($res) == 0){
		//Pas de demande en vacances en cours
		return '<td><input type="checkbox" name="check_vacances" /><input type="hidden" name="action" value="depart" /></td><td><input type="submit" value="Partir en vacances" /></td>';
	}
	else{
		$row = mysql_fetch_assoc($res);
		if($row['date_retour'] != '0000-00-00 00:00:00'){
			//Le retour est programmé
			return '<td colspan="2"><span>Retour prévu le '.date('d/m/Y H:i:s', strtotime($row['date_retour'])).'</span></td>';
		}
		elseif($row['date_depart'] == '0000-00-00 00:00:00'){
			//Départ en vacances prévu
			$date_depart = strtotime($row['date_demande']) + (intval(VACANCES_DELAI_DEPART) * 3600);
			$date_depart = date('d/m/Y H:i:s',$date_depart);
			return '<td colspan="2"><span>Départ prévu le '.$date_depart.'</span></td>';
		}
		else{
			//Personnage en vacances
			//TODO : checker si le delai entre le depart et le retour est OK (pas demandé pour le moment)
			return '<td><input type="checkbox" name="check_vacances" /><input type="hidden" name="action" value="retour" /></td><td><input type="submit" value="Revenir de vacances" /></td>';
		}
	}
}

/**
 *
 */
function departVacances($id_utilisateur){
	if(!is_array($_SESSION['persos']['id'])){
		return false;
	}
	$persos = $_SESSION['persos']['id'];
	
	$sql = 'INSERT INTO utilisateurs_vacances (utilisateur_id,date_demande) VALUES ('.$id_utilisateur.', NOW())';
	if(mysql_query($sql)){
		/*Gestion des évènements*/
		foreach($persos as $matricule){
			addEventVacances($matricule, 1);
		}
		return true;
	}
	else{
		return false;
	}
}

/**
 *
 */
function retourVacances($id_utilisateur){	
	$date_retour = time() + (intval(VACANCES_DELAI_RETOUR) * 3600);
	$date_retour = date('Y-m-d H:i:s', $date_retour);
	$sql = 'UPDATE utilisateurs_vacances SET date_retour = \''.$date_retour.'\' WHERE utilisateur_id = '.$id_utilisateur;
	
	mysql_query($sql);
}

/**
 *
 */
function addEventVacances($matricule, $action = 1,$private_info=false){
	$evman = new EventManager();
	$evenement = $evman->createEvent('vacances');
	$evenement->setSource($matricule, eventFormatter::convertType('perso'));
	if(false !== $private_info){
		$evenement->infos->addPrivateInfo('xp',$private_info);
	}
	$evenement->setState($action);
}
	
?>
