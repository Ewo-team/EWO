<?php
/**
 * Mail - Template du mail html
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package mail
 */

function mail_header($titre){
	$header = "<html>
							<head>
							 <title>".$titre."</title>
							</head>
							<body>";
	return $header;
}

function mail_corps($corps){
	$corps = "<div>".$corps."</div>";
	
}

function mail_bottom(){
	$bottom = "</body>
     			</html>";
}

?>
