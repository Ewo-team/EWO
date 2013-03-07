<?php

namespace site\mail;

require_once __DIR__ . '/../../conf/master.php';

$header['title'] = "Contact";
$header['desc'] = "Pour contacter l'équipe de gestion du projet Ewo";

$id = (isset($_GET['id'])) ? $_GET['id'] : null;
//$email = (isset($_GET['email'])) ? $_GET['email'] : null;

if($id != null && ctype_alnum($id) /*&& $email != null*/) {
	$dao = \conf\ConnecteurDAO::getInstance();
	
	$dao->prepare("SELECT * FROM emails WHERE hash_id = ? LIMIT 1");
	
	$dao->executePreparedStatement(null, array($id));
	$result = $dao->fetch();  
	
	if(count($result) > 0 /*&& filter_var($email, FILTER_VALIDATE_EMAIL)*/) {
		//$to = base64_decode($result['to_email']);
		
		//if(preg_match('/'.$email.'/i', $to) === 1) {
			$message = base64_decode($result['message']);
			$pattern = '<!DOCTYPE';
			
			$start = strpos($message, $pattern);

			header('Content-Type: text/html; charset=utf-8');
			
			echo substr($message,$start);
		//}
	}
}