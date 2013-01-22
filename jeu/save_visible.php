<?php
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");

bdd_connect('ewo');

$block_id = $_GET['block_id'];
$visible = $_GET['visible'];

if ($visible == 0 or $visible == 1){
	$sql = mysql_query("UPDATE blocks SET visible = '".$visible."' WHERE block_id = '".$block_id."' AND perso_id= '".$_SESSION['persos']['current_id']."'");
	if ($sql != FALSE)
	{
		echo 'done';
	}else{
		echo 'null';
	}
}else{
	echo 'null';
}


?>
