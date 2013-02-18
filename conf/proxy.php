<?php
/**
 * Retourne les informations grace a l'ip
 *
 * @author Ganesh <ganesh@gmail.com>
 * @version 1.0
 * @package conf
 */

/**
 * Retourne les informations grace a l'ip
 */
function ProxyGetCode(&$In,$Code){
	$Out=$In['REMOTE_ADDR'].'_'.$In['HTTP_VIA'].'_';

	$Test=ereg('([0-9]{1,3}\.){3,3}[0-9]{1,3}',$In[$Code],$OutArray) ;
	if ($Test&&(count($OutArray)>=1))
	{$Out=gethostbyaddr($OutArray[0]);}
	else
	{$Out.=$In[$Code];}

	return $Out;
}

/**
 * Return les infos sur IP d'user via Proxy
 */
function ProxyGetUser($In){
	if (isset($In['HTTP_X_FORWARDED_FOR'])) {$Out=ProxyGetCode($In,'HTTP_X_FORWARDED_FOR');}
	elseif(isset($In['HTTP_X_FORWARDED'])) {$Out=ProxyGetCode($In,'HTTP_X_FORWARDED');}
	elseif(isset($In['HTTP_FORWARDED_FOR'])) {$Out=ProxyGetCode($In,'HTTP_FORWARDED_FOR');}
	elseif(isset($In['HTTP_FORWARDED'])) {$Out=ProxyGetCode($In,'HTTP_FORWARDED');}
	elseif(isset($In['HTTP_VIA'])) {$Out=$In['HTTP_VIA'].'_' . $In['HTTP_X_COMING_FROM'].'_' . $In['HTTP_COMING_FROM'];}
	elseif((isset($In['HTTP_X_COMING_FROM'])||isset($In['HTTP_COMING_FROM']))){$Out=$In['$REMOTE_ADDR'].'_' . $In['HTTP_X_COMING_FROM'].'_' . $In['HTTP_COMING_FROM'];}
	else {$Out=gethostbyaddr($In['REMOTE_ADDR']);}

	return $Out;
}

/**
 * Return les infos de Proxy
 */
function ProxyGetInfo(){
	$Out=Array();

	if (isset($_SERVER['HTTP_VIA'])) $Out['HTTP_VIA'] =$_SERVER['HTTP_VIA'];
	if (isset($_SERVER['HTTP_X_COMING_FROM'])) $Out['HTTP_X_COMING_FROM'] =$_SERVER['HTTP_X_COMING_FROM'];
	if (isset($_SERVER['HTTP_X_FORWARDED'])) $Out['HTTP_X_FORWARDED'] =$_SERVER['HTTP_X_FORWARDED'];
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))$Out['HTTP_X_FORWARDED_FOR']=$_SERVER['HTTP_X_FORWARDED_FOR'];
	if (isset($_SERVER['HTTP_FORWARDED'])) $Out['HTTP_FORWARDED'] =$_SERVER['HTTP_FORWARDED'];
	if (isset($_SERVER['HTTP_COMING_FROM'])) $Out['HTTP_COMING_FROM'] =$_SERVER['HTTP_COMING_FROM'];
	if (isset($_SERVER['HTTP_FORWARDED_FOR'])) $Out['HTTP_FORWARDED_FOR'] =$_SERVER['HTTP_FORWARDED_FOR'];

	if (count($Out)>0)
	{$Out['Proxy']=TRUE;}
	else
	{$Out['Proxy']=FALSE;}
	$Out['REMOTE_ADDR']=$_SERVER['REMOTE_ADDR'];
	$Out['VId']=ProxyGetUser($Out);

	return $Out;
}
?>