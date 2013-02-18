<?php

require_once __DIR__ . '/../conf/master.php';

if(!isset($_SESSION['utilisateur']['id'])){
	header("location:../index.php");
}


/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/
echo phpinfo();

?>