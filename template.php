<?php
session_start();

if(isset($_SESSION['header'])){
	if($_SESSION['header'] == 'on'){
		$_SESSION['header'] = 'off';
		echo "off";
	}else{
		$_SESSION['header'] = 'on';
		echo "on";
	}
}else{
	$_SESSION['header'] = 'on';
	echo "off";
}
?>
