<?php

/*
	***********************
	confirm_inscrip.php
	***********************
		
	
	***********************
	ControllerInscription.php
	***********************
		"SELECT nom FROM `utilisateurs` WHERE nom = '$nom'"
		"SELECT email FROM `utilisateurs` WHERE email = '$email'"
		"SELECT numero FROM `invitations` WHERE numero = '$numero'"
		"INSERT INTO utilisateurs(id, nom, email, passwd, passwd_forum, 
			date_enregistrement, jabberid, droits, options, codevalidation, session_id) 
			VALUES('','$nom','$email','$pass','$pass_forum','$datetime','$jabberid',
			'0000','','$code_validation', '$session_id')"
		"INSERT INTO utilisateurs_option (utilisateur_id, bals_speed, template, redirection) VALUES('$id_user','0.5','defaut', '1')"
		"DELETE FROM invitations WHERE numero='$numero'"
	
	***********************
	index.php
	***********************
		"SELECT COUNT(*) FROM utilisateurs"
	
	***********************
	validation.php
	***********************
		"SELECT droits FROM `utilisateurs` WHERE nom = '$nom' AND codevalidation = '$code_validation'"
		"SELECT nom, codevalidation FROM `utilisateurs` WHERE email = '$email' AND codevalidation = '$code_validation'"
		"UPDATE utilisateurs SET droits=1000 WHERE email = '$email'"

*/

?>	