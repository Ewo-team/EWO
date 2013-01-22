<?php

define('IN_PHPBB', true);

$phpEx = 'php';
$phpbb_root_path = $root_url.'/forum/';

require_once($root_url.'/forum/common.php');
require_once($root_url.'/forum/includes/functions_user.php');

$user->session_begin();
$auth->acl($user->data);
$user->setup('');

$user->session_kill();
$user->session_begin();