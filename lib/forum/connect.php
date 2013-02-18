<?php

define('IN_PHPBB', true);

$phpEx = 'php';
$phpbb_root_path = SERVER_ROOT . '/forum/';

if (is_file(SERVER_ROOT . '/forum/common.php') && is_file(SERVER_ROOT . '/forum/includes/functions_user.php')) {
	require_once(SERVER_ROOT.'/forum/common.php');
	require_once(SERVER_ROOT.'/forum/includes/functions_user.php');

	$include_forum = true;
	require('ewo_forum.php');

	$ef = new EwoForum($_SESSION['utilisateur']['id']);
	$blanc = $ef->isBlank($username);

	if(isset($password)) {

		if($blanc) {
			$ef->changePasswords($password);
		}

		//-- Kill des sessions possible deja existante.
		$user->session_kill();
		$user->session_begin();

	   $result = $auth->login($username, $password, true, false, $admin);

	   if ($result['status'] != LOGIN_SUCCESS){
		   throw new Exception("Erreur de login du premier personnage sur le forum. Pour y remédier : déconnectez-vous, puis effacer vos cookies et videz le cache de votre navigateur. Reconnectez-vous et cliquer sur switch après avoir sélectionné le personnage désiré.");
	   }else{
			   $auth->acl($user->data);
	   }  
	} else {
		if($blanc) {
			$_SESSION['autologin']["unlogin"] = true;
			$titre = "Erreur de connexion";
			$text = "Une erreur dans le processus de connexion automatique est survenu. Veuillez vous reconnecter.";
			$lien = "..";
			gestion_erreur($titre, $text, $lien);        
		}
	}
}