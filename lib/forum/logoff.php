<?php

define('IN_PHPBB', true);

$phpEx = 'php';
$phpbb_root_path = SERVER_ROOT . '/forum/';

if (is_file(SERVER_ROOT . '/forum/common.php') && is_file(SERVER_ROOT . '/forum/includes/functions_user.php')) {
	require_once(SERVER_ROOT . '/forum/common.php');
	require_once(SERVER_ROOT . '/forum/includes/functions_user.php');

	$user->session_begin();
	$auth->acl($user->data);
	$user->setup('');

	$user->session_kill();
	$user->session_begin();
}