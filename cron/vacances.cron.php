<?php
/**
 * Cron de désincarnation/réincarnation des vacanciers
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package cron
 */

 mail(thanhatos@gmail.com,vacances_ewo, test1);
 
define('CALL_FROM_CRON', true);

$path = realpath(dirname($_SERVER['SCRIPT_FILENAME']));
$root_url = explode('/',$path);
array_pop($root_url);
$root_url = join('/',$root_url);


//include_once($root_url."/conf/fonctions.php");
include_once($root_url."/conf/master.php");
include_once($root_url."/conf/config.php");
//include_once($root_url."/conf/connect.conf.php");
require_once($root_url.'/compte/config_vacances.php');
require_once($root_url.'/compte/fonctions.php');
require_once($root_url.'/jeu/fonctions.php');

$ewo = bdd_connect('ewo');
if(false === $ewo){
	die('Erreur de base de données');
}

//Récupération des template mail
$mail_depart = file_get_contents($root_url.'/compte/mail_vacances_depart.html');
$mail_retour = file_get_contents($root_url.'/compte/mail_vacances_retour.html');

//Selection de tous les comptes à mettre en vacances
//La date de demande doit être inférieure au lancement du script - le délai
$date_mini = time() - (intval(VACANCES_DELAI_DEPART) * 3600);
$date_mini = date('Y-m-d H:i:s', $date_mini);
$sql = 'SELECT uv.utilisateur_id, u.email, u.nom
FROM utilisateurs_vacances uv
LEFT JOIN utilisateurs u ON uv.utilisateur_id = u.id
WHERE uv.date_demande <= \''.$date_mini.'\' AND uv.traite =\'0\'';

$res = mysql_query($sql);


if(false !==  $res){
	//Parcours de la liste pour retrouver les persos des joueurs et les désicarner
	while($row = mysql_fetch_assoc($res)){
		$sql = 'SELECT id FROM persos WHERE utilisateur_id = '.$row['utilisateur_id'];
		$res2 = mysql_query($sql);
		if(false === $res2){
			echo 'La requête '.$sql.' a échoué'.PHP_EOL;
			continue;
		}
		while($row_perso = mysql_fetch_assoc($res2)){
			//Ajout d'un évènement
			addEventVacances($row_perso['id'],2);
			//Désincarnation
			$ewo = bdd_connect('ewo');
			if(false === $ewo){
				die('Erreur de base de données');
			}
			desincarne($row_perso['id']);
		}
		$sql = 'UPDATE utilisateurs_vacances SET date_depart = NOW(), traite = \'1\' WHERE utilisateur_id = '.$row['utilisateur_id'];
		if(mysql_query($sql)){
			mailVacances($row['email'],$row['nom'],$mail_depart);
		}
		else{
			echo 'La requête '.$sql.' a échoué'.PHP_EOL;
		}
	}
}
else{
	die('Erreur à la récupération des joueurs à mettre en vacances');
}

//Selection de tous les comptes à sortir de vacances
//Comprend ceux qui en ont fait la demande et ceux qui ont dépassé la durée des vacances
$date_mini = time() - (intval(VACANCES_DELAI_MAX) * 24 * 3600);
$date_mini = date('Y-m-d H:i:s', $date_mini);
$sql = 'SELECT uv.utilisateur_id,DATEDIFF ( NOW(),uv.date_depart) as duree, u.email, u.nom FROM utilisateurs_vacances uv
			LEFT JOIN utilisateurs u ON uv.utilisateur_id = u.id
			WHERE ((date_retour <= NOW() AND date_retour <> \'0000-00-00 00:00:00\')
			OR (date_depart <= \''.$date_mini.'\' AND date_depart <> \'0000-00-00 00:00:00\'))
			AND traite = \'1\'';
$res = mysql_query($sql);


if(false !==  $res){
	//Parcours de la liste pour retrouver les persos des joueurs et les incarner
	while($row = mysql_fetch_assoc($res)){
		$sql = 'SELECT id FROM persos WHERE utilisateur_id = '.$row['utilisateur_id'];
		
		$res2 = mysql_query($sql);
		if(false === $res2){
			echo 'La requête '.$sql.' a échoué'.PHP_EOL;
			continue;
		}
		
		$gain = gainxp(2,'vacance', intval($row['duree']));
		//$gain = floor(floatval(VACANCES_GAIN_XP) * intval($row['duree']));
		while($row_perso = mysql_fetch_assoc($res2)){
			//Ajout d'un évènement
			addEventVacances($row_perso['id'],3,$gain);
			//Incarnation
			$ewo = bdd_connect('ewo');
			respawn($row_perso['id'],'autre');
			//Gain d'xp
			
			$sql = 'UPDATE caracs SET px = px + '.$gain.', pi = pi + '.$gain.' WHERE perso_id = '.$row_perso['id'];
			
			if(!mysql_query($sql)){
				echo 'La requête '.$sql.' a échoué'.PHP_EOL;
			}
		}
		$sql = 'DELETE FROM utilisateurs_vacances WHERE utilisateur_id = '.$row['utilisateur_id'];
		if(mysql_query($sql)){
			mailVacances($row['email'],$row['nom'],$mail_retour);
		}
		else{
			echo 'La requête '.$sql.' a échoué'.PHP_EOL;
		}
	}
}
else{
	echo 'La requête '.$sql.' a échoué'.PHP_EOL;
}

mysql_close($ewo);

/**
 * Envoie du mail de confirmation de mise en vacance
 */
function mailVacances($email, $nom, $template){
		$headers ='From: "EwoManager"<ewomanager@ewo.fr>'."\n";
		$headers .='Reply-To: ewomanager@ewo.fr'."\n";
		$headers .='Content-Type: text/html; charset="iso-8859-1"'."\n"; 
		$headers .='Content-Transfer-Encoding: 8bit';
		
		$template = str_replace('###DOMAIN_NAME###',ROOT_HTTP,$template);
		$template = str_replace('###NOM###',$nom,$template);
		mail($email,'[Ewo] Vacances', $template, $headers);
}

 mail(thanhatos@gmail.com,vacances_ewo, test2);
 
?>
