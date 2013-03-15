<?php

require_once __DIR__ . '/../../conf/master.php';

error_reporting(E_ALL);


include (SERVER_ROOT . '/lib/forum/ewo_forum.php');


$forum = new EwoForum(1);

$dao = \conf\ConnecteurDAO::getInstance("ewo");

$sth = $dao->query("SELECT id, nom FROM persos");

$tab = $sth->fetchAll();

foreach($tab as $ligne) {
	$mat = $ligne['id'];
	$nom = $ligne['nom'];
	echo "$nom : $mat<br>";
	$forum->setMatricule($nom, $mat);
}
