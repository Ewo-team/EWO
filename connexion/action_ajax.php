<?php	
/**
 * Connexion - ction ajax pour la connexion
 *
 * Switch ajax pour le forum phpBB
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package connexion
 */
session_start();

$root_url = "..";

include("../conf/master.php");
/*
if ((isset($_GET['perso_nom'])) AND (isset($_GET['action']))){

$nom_personnage = $_GET['perso_nom'];
if(ControleAcces('utilisateur',0)){
	if($_GET['action']=='renew_link'){
	
			define('IN_PHPBB', true);
			$phpEx = substr(strrchr(__FILE__, '.'), 1);
			$phpbb_root_path = '../forum/';
			require('../forum/common.php');
			require('../forum/includes/functions_user.php');

		echo $root_url."/forum/index.php?sid=".$_COOKIE['phpbb3_s6xek_sid'];
	}else{
		$pass = $_SESSION['utilisateur']['passwd_forum'];

		//-- Code phpBB pour la gestion du pass et du login
			define('IN_PHPBB', true);
			$phpEx = substr(strrchr(__FILE__, '.'), 1);
			$phpbb_root_path = '../forum/';
			require('../forum/common.php');
			require('../forum/includes/functions_user.php');

		//-- Kill des sessions possible deja existante.
			$user->session_kill();
			$user->session_begin();

		//-- Definition des vars pour le forum phpbb
			//$username = $_SESSION['persos']['nom'][1];
			$autologin = false;
			$viewonline = 1;
			$admin = 0;
			
			$result = $auth->login($nom_personnage, $pass, $autologin, $viewonline, $admin);
			if ($result['status'] != LOGIN_SUCCESS){
				echo "Erreur de login du personnage sur le forum Login :".$nom_personnage." Pass : ".$pass."";exit;
			}else{
				$auth->acl($user->data);
				//echo 'Connexion du premier personnage sur le forum';exit;
			}
		}
	}else{
		echo "Veuillez vous connecter au jeu.";exit;
		}
}*/
?>
