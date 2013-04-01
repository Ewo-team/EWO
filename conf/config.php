<?php

// Gestion des ticket d'inscription
// 0 : Non ; 1 : Oui
define('TICKET', 0);

// Login SSL
// 0 : Non ; 1 : Oui
define('SSL', 0);


define('WEB_DOMAIN', $_SERVER["HTTP_HOST"]);
define('WEB_SUBFOLDER', '');

$inhttps = ((key_exists('HTTPS', $_SERVER)) ?  $_SERVER["HTTPS"] == 'on' : false);

$protocol = (SSL == 1 || $inhttps) ? 'https' : 'http';

define('SERVER_URL' , $protocol . '://' . WEB_DOMAIN . WEB_SUBFOLDER);
define("SERVER_ROOT", substr(__DIR__, 0, -5));
