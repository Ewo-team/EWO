<?php
/**
 * Connexion au jeu
 *
 * Script pour la connexion sur ewo
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package connexion
 */
 
ini_set('session.gc_maxlifetime', 86400);
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");
include ($root_url."/persos/fonctions.php");
include ($root_url."/admin/antitriche/class/ConnexionLogger.php.inc");

if($_SSL == 1){
	if($_SERVER["HTTPS"] != "on") {
		 header("Location: https://" . $_URL . $_SERVER["REQUEST_URI"]);
		 exit();
	}
}

$ok = false;

$ewo = bdd_connect('ewo');
$ticket = '';

if(isset($_POST['login']) && isset($_POST['pass'])) {
	$ok = true;
	$password = mysql_real_escape_string($_POST['pass']);
	$utilisateur = mysql_real_escape_string(ucfirst(htmlspecialchars(strip_tags($_POST['login']),ENT_COMPAT, 'UTF-8')));
} 

if(isset($_GET['login']) && isset($_GET['ticket'])) {
	$ok = true;
	$ticket = mysql_real_escape_string($_GET['ticket']);
	$utilisateur = mysql_real_escape_string(ucfirst(htmlspecialchars(strip_tags($_GET['login']),ENT_COMPAT, 'UTF-8')));
}

if($ok){

// Paramètres de connexion à la base de données

	$sql="SELECT utilisateurs.*,utilisateurs_ticket.* 
	FROM utilisateurs 
	LEFT JOIN utilisateurs_ticket ON (utilisateurs_ticket.utilisateur_id = utilisateurs.id AND utilisateurs_ticket.ticket='$ticket') 
	WHERE nom = '$utilisateur'";
	$resultat = mysql_query ($sql) or die (mysql_error());
	$connexion = mysql_fetch_array ($resultat);
	
	$utilisateur_id = $connexion['id'];
	$date_courant = time();
	$_SESSION['utilisateur']['droits_date']=$date_courant;

		//-- Verfification dans la table de bannissement
		$sql1="SELECT*FROM utilisateurs_ban WHERE utilisateur_id = '$utilisateur_id'";
		$resultat1 = mysql_query ($sql1) or die (mysql_error());
		$ban_controle = mysql_fetch_array ($resultat1);
		
		if(!empty($ban_controle['utilisateur_id'])){
		
			$date         = $ban_controle['date'];
			$date_fin     = $ban_controle['date_fin'];
			$date_courant = time();
			$_SESSION['utilisateur']['droits_date']=$date_courant;
			$date = date("d-m-Y", $date);
			$date_fin_t = date("d-m-Y à H:i", $date_fin);
		
			if($date_fin < $date_courant){
				mysql_query("DELETE FROM utilisateurs_ban WHERE utilisateur_id='$utilisateur_id'") or die (mysql_error());
			}else{
				$titre = "Personnage banni";
				$text = "Cet utilisateur est banni ! <br /> 
									Depuis le : ".$date.".<br /> Le ban prendra fin le : ".$date_fin_t."<br />
									Motif : ".$ban_controle['motif'];
				$lien = "..";
				gestion_erreur($titre, $text, $lien);
			}
		}

	// Définition du droit par defaut de l'utilisateur
		$droits = $connexion['droits'];
	//--
	
	// Check du pass
		$hash = $connexion['passwd'];
		$hash_forum = $connexion['passwd_forum'];
	
	// Si on se log avec le password
	if(isset($password)) {
		//-- Gestion du hash du mot de passe
		$pass = hash('sha256',$password);
		
		//echo $pass;exit;
		if ($hash == $pass){
			$passhash = true;
		}else{
			$passhash = false;
		}
	} else {
		if($ticket == $connexion['ticket'] && $connexion['ticket'] != null) {
			$passhash = true;
		} else {
			$passhash = false;
		}
	}
		
	if (($connexion != FALSE) AND ($passhash == true) AND ($droits[0] == 1)){
		if ($passhash == true){
		$_SESSION['utilisateur']['id'] = $connexion['id'];
		$_SESSION['utilisateur']['nom']	= $connexion['nom'];
		$_SESSION['utilisateur']['mail']	= $connexion['email'];
		$_SESSION['utilisateur']['droits']	= $connexion['droits'];
		$_SESSION['utilisateur']['jabberid'] = $connexion['jabberid'];
		$_SESSION['utilisateur']['passwd_forum'] = $connexion['passwd_forum'];
		$_SESSION['utilisateur']['icones_pack'] = $connexion['icones_pack'];
		

	//-- Récupération des infos des persos
		$sql="
			SELECT
				persos.id			AS id,
				persos.nom			AS nom,
				persos.race_id		AS race_id,
				races.camp_id		AS camp_id,
				races.type			AS type,
				persos.grade_id		AS grade_id,
				persos.galon_id		AS galon_id,
				persos.superieur_id	AS superieur_id,
				persos.faction_id	AS faction_id,
				date_tour			AS tour,
				camps.carte_id		AS carte_id,
                                persos.mortel           AS mortel
				
			FROM persos
			INNER JOIN races
				ON (persos.race_id = races.race_id AND persos.grade_id = races.grade_id)
			INNER JOIN camps
				ON (camps.id = races.camp_id) 
			WHERE
				persos.utilisateur_id = '".$connexion['id']."'
			ORDER BY persos.id ASC";
		$resultat = mysql_query ($sql) or die (mysql_error());
		$inc=1;
		$_SESSION['persos']['inc'] = 0;
		while($persos = mysql_fetch_array ($resultat)){
			
			$_SESSION['persos']['inc']					= $inc;
			$_SESSION['persos']['id'][$inc] 			= $persos['id'];
			$_SESSION['persos']['nom'][$inc] 			= $persos['nom'];
			$_SESSION['persos']['race'][$inc] 			= $persos['race_id'];
			$_SESSION['persos']['camp'][$inc] 			= $persos['camp_id'];
			$_SESSION['persos']['type'][$inc] 			= $persos['type'];
			$_SESSION['persos']['carte_respawn'][$inc] 	= $persos['carte_id'];			
			$_SESSION['persos']['grade'][$inc] 			= $persos['grade_id'];
			$_SESSION['persos']['galon'][$inc] 			= $persos['galon_id'];
			$_SESSION['persos']['faction']['id'][$inc]	= $persos['faction_id'];
			$_SESSION['persos']['date_tour'][$inc]		= $persos['tour'];
                        $_SESSION['persos']['mortel'][$inc]             = $persos['mortel'];
			
			$sql="SELECT * FROM damier_persos WHERE perso_id='".$persos['id']."'";
			$res_pos = mysql_query ($sql) or die (mysql_error());
			if($pos = mysql_fetch_array ($res_pos)){
				$_SESSION['persos']['pos_x'][$inc] = $pos["pos_x"];
				$_SESSION['persos']['pos_y'][$inc] = $pos["pos_y"];
				$_SESSION['persos']['carte'][$inc] = $pos["carte_id"];
				}
				
			if (recup_camp($_SESSION['persos']['race'][$inc])==3 || recup_camp($_SESSION['persos']['race'][$inc])==4) {
				if($_SESSION['persos']['grade'][$inc]==5){
					$_SESSION['persos']['anim']['id']		= $_SESSION['persos']['id'][$inc] ;
					$_SESSION['persos']['anim']['race']		= $_SESSION['persos']['race'][$inc];
					$_SESSION['persos']['anim']['grade']	= $_SESSION['persos']['grade'][$inc];
					}
				}elseif(recup_camp($_SESSION['persos']['race'][$inc])==1) {
					if($_SESSION['persos']['grade'][$inc]==4 || $_SESSION['persos']['grade'][$inc]==5){
						$_SESSION['persos']['anim']['id']		= $_SESSION['persos']['id'][$inc] ;
						$_SESSION['persos']['anim']['race']		= $_SESSION['persos']['race'][$inc];
						$_SESSION['persos']['anim']['grade']	= $_SESSION['persos']['grade'][$inc];
						}
					}
			
			
			if ($_SESSION['persos']['faction']['id'][$inc]){
				$sql="SELECT faction_membres.faction_grade_id AS faction_grade, faction_grades.droits AS droits, factions.type AS type
					FROM faction_membres
						INNER JOIN faction_grades ON faction_grades.faction_id = ".$persos['faction_id']." AND faction_grades.grade_id = faction_membres.faction_grade_id
						INNER JOIN factions ON faction_membres.faction_id = factions.id 
						WHERE faction_membres.perso_id = ".$persos['id'];
				$res_fac = mysql_query ($sql) or die (mysql_error());
				$fac_droits = mysql_fetch_array ($res_fac);
				$_SESSION['persos']['faction']['grade'][$inc] 	= $fac_droits['faction_grade'];
				$_SESSION['persos']['faction']['droits'][$inc] 	= $fac_droits['droits'];
				$_SESSION['persos']['faction']['type'][$inc] 	= $fac_droits['type'];				
				}
			$_SESSION['persos']['superieur'][$inc++] = $persos['superieur_id'];
			}
		
		//--connexion du premier personnage sur le forum si celui-ci existe.
		if (isset($_SESSION['persos']['nom'][1])){
			$username = $_SESSION['persos']['nom'][1];
			$pass_hash = $hash_forum;
			$autologin = true;
			$viewonline = 1;
			$admin = 0;
			
                        try {
                            include('forum/connect.php');
                        } catch(Exception $e) {
                            echo $e->getMessage();
                        }
                        

                        /*
			//-- Code phpBB pour la gestion du pass et du login
			//define('IN_PHPBB', true);
			$phpEx = substr(strrchr(__FILE__, '.'), 1);
			$phpbb_root_path = '../forum/';
			require('../forum/common.php');
			require('../forum/includes/functions_user.php');

				//-- Kill des sessions possible deja existante.
			 $user->session_kill();
			 $user->session_begin();

			$result = $auth->login($username, $pass_hash, $autologin, $viewonline, $admin);
			if ($result['status'] != LOGIN_SUCCESS){
				echo 'Erreur de login du premier personnage sur le forum. Pour y remédier : déconnectez-vous, puis effacer vos cookies et videz le cache de votre navigateur. Reconnectez-vous et cliquer sur switch après avoir sélectionné le personnage désiré.';exit;
			}else{
				$auth->acl($user->data);
				//echo 'Connexion du premier personnage sur le forum';exit;
			}
                        */
		}
		
		
		$ewo = bdd_connect('ewo');
		
		$utilisateur_id = $connexion['id'];
		$ip_adresse = $_SERVER['REMOTE_ADDR'];
		$navigateur = $_SERVER['HTTP_USER_AGENT'];
		
		//-- recupere l'host
		include("../conf/proxy.php");		
		$infos_user = ProxyGetInfo();
		$host = $infos_user['VId'];
	
		$date = date("Y-m-d");
		$time = date("H:i:s");
		$datetime = "$date $time";
		
		
		$logger = new at\ConnexionLogger();
		$logger->log($utilisateur_id);

		//---- Redirection en cas de succés de la connexion.
		$redirec = redirection_connexion($utilisateur_id);
		if(isset($_POST['autologin']) || isset($ticket)) {
		// Le login a réussi, l'utilisateur veux un auto-login
			if($ticket == '') {
				$ticket = md5($utilisateur + time() + 'ewo');
			}
			$_SESSION['autologin']["newticket"] = true;
			$_SESSION['autologin']["login"] = $utilisateur;
			$_SESSION['autologin']["ticket"] = $ticket;
			$time = date("Y-m-d H:i:s",time()+60*60*24*30);
			mysql_query("REPLACE INTO utilisateurs_ticket (utilisateur_id,ticket,expiration) VALUES ('$utilisateur_id','$ticket','$time')");
		} else {
			// Suppression de la valeur "ticket" dans la bdd
			//mysql_query("UPDATE utilisateurs SET ticket=null WHERE id = '$utilisateur_id' LIMIT 1");
		}
		
		header("location:http://".$_URL.$redirec);

		}else{
			$titre = "Erreur de connexion";
			$text = "Mauvais mot de pass.";
			$root = "..";
			$lien = "..";
			gestion_erreur($titre, $text, $root, $lien);
		}	
	}else{
		if (isset($pass)) {
			$_SESSION['autologin']["unlogin"] = true;
			$titre = "Erreur de connexion";
			$text = "Cet utilisateur n'existe pas ou n'a pas encore &eacute;t&eacute; valid&eacute;.";
			$root = "..";
			$lien = "..";
			gestion_erreur($titre, $text, $root, $lien);
		} else {
			$_SESSION['autologin']["unlogin"] = true;
			$titre = "Erreur de connexion";
			$text = "Vous avez été déconnecté, veuillez vous connecter à nouveau.";
			$root = "..";
			$lien = "..";
			gestion_erreur($titre, $text, $root, $lien);			
			}
	}
	mysql_close($ewo);

}else{
	$_SESSION['autologin']["unlogin"] = true;
	$titre = "Erreur de connexion";
	$text = "Cet utilisateur n'existe pas.";
	$root = "..";
	$lien = "..";
	gestion_erreur($titre, $text, $root, $lien);
}
?>
