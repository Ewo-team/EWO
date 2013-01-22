<?php

session_start();
if(!isset($_SESSION['utilisateur']['id'])){
	header("location:../index.php");
}

//-- Header --
$root_url = "./..";

require_once($root_url.'/conf/master.php');

/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/
echo phpinfo();

?>