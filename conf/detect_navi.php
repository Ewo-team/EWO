<?php
/**
 * Configuration Detection du navigateur
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package conf
 */
 
/**
 * Detection du navigateur grace a Browscap
 */
function navigateur(){
	// Loads the class
	require SERVER_ROOT.'/lib/browscap/Browscap.php';

	// Creates a new Browscap object (loads or creates the cache)
	$bc = new Browscap(SERVER_ROOT.'/lib/browscap/cache');

	// Gets information about the current browser's user agent
	$current_browser = $bc->getBrowser(null, true);

	// Output the result
	return $current_browser;
}

$nav = navigateur();

/**
 * Detection du navigateur compatible avec ewo
 * @return true or false en fonction de la compatibilité definie par les dev
 */
function detect_navi(){
	global $nav;
	//$nav['Version']
	if(($nav['Browser'] == 'Firefox' AND $nav['Version'] < 3.0) OR ($nav['Browser'] == 'Chrome' AND $nav['Version'] < 3.0) OR ($nav['Browser'] == 'IE' AND  $nav['Version'] > 8.0)){
	  return false;
	}else{
		return true;
	}
}

/**
 * Affiche la version du navigateur et du nom de celui-ci
 * @return $nav echo version et nom du navigateur
 */
function affiche_navi(){
	global $nav;
	echo $nav['Version'];
		echo '<br />';
	echo $nav['Browser'];
}
?>
