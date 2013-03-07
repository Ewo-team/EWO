<?php

require_once __DIR__ . '/../../conf/master.php';

error_reporting(E_ALL);


include (SERVER_ROOT . '/lib/forum/ewo_forum.php');


$forum = new EwoForum(1);

$test = @$_GET['test'];

if(isset($_GET['id'])) {
	$id = $_GET['id'];
} else {
	$id = 'test_ganesh'.rand(1,1000);
}

if(isset($_GET['race'])) {
	$race = $_GET['race'];
} else {
	$race = 'paria';
}

if(isset($_GET['grade'])) {
	$grade = $_GET['grade'];
} else {
	$grade = 1;
}

if(isset($_GET['galon'])) {
	$galon = $_GET['galon'];
} else {
	$galon = 1;
}

switch($test) {
	case 'create':
		$forum->createPerso($id, 'ewo@leomaradan.com', 'toto');
		break;
	case 'racegrade':
		$forum->setRaceGrade($id,$race,$grade,$galon);
		break;
}


echo '<ul>
	<li><a href="test_forum.php?test=create&id='.$id.'">create</a></li>
	<li><a href="test_forum.php?test=racegrade&id='.$id.'&race=ange&grade='.$grade.'&galon='.$galon.'">setange</a></li>
	<li><a href="test_forum.php?test=racegrade&id='.$id.'&race=demon&grade='.$grade.'&galon='.$galon.'">setdemon</a></li>
	<li><a href="test_forum.php?test=racegrade&id='.$id.'&race=humain&grade='.$grade.'&galon='.$galon.'">sethumain</a></li>	
	<li><a href="test_forum.php?test=racegrade&id='.$id.'&race=3&grade='.$grade.'&galon='.$galon.'">setange 2</a></li>
	<li><a href="test_forum.php?test=racegrade&id='.$id.'&race=4&grade='.$grade.'&galon='.$galon.'">setdemon 2</a></li>
	<li><a href="test_forum.php?test=racegrade&id='.$id.'&race=1&grade='.$grade.'&galon='.$galon.'">sethumain 2</a></li>
	<li><a href="test_forum.php?test=racegrade&id='.$id.'&race='.$race.'&grade=2&galon=1">g2</a></li>
	<li><a href="test_forum.php?test=racegrade&id='.$id.'&race='.$race.'&grade=3&galon=1">g3g1</a></li>
	<li><a href="test_forum.php?test=racegrade&id='.$id.'&race='.$race.'&grade=3&galon=2">g3g2</a></li>
	<li><a href="test_forum.php?test=racegrade&id='.$id.'&race='.$race.'&grade=4&galon=1">g4</a></li>
	<li><a href="test_forum.php?test=racegrade&id='.$id.'&race='.$race.'&grade=5&galon=2">g5</a></li>
</ul>';

    /*
selectPassEmail($email)
    
lierComptes($email)
    
setRaceGrade($id,$race,$grade,$galon)
    
changePasswords($password)
    
emptyPassword()
    
isBlank($pseudo)*/