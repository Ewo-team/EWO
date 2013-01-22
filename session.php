<?php
// destruction des sessions active
session_start();
$root_url = ".";

include ('connexion/forum/logoff.php');

//-- Code phpBB pour la gestion du pass et du login
//define('IN_PHPBB', true);


/*
$phpEx = 'php';
$phpbb_root_path = 'forum/';
require('forum/common.php');
require('forum/includes/functions_user.php');

$user->session_begin();
$auth->acl($user->data);
$user->setup('');

   $user->session_kill();
   $user->session_begin();
*/

session_destroy();

// Un nouvelle session est cr�e pour demander la destruction de l'autologin
session_start();
$_SESSION['autologin']["unlogin"] = true;

// redirection
header("location:index.php");
?>
