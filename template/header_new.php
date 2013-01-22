<?php
/**
 * Inclusion du header en fonction de la template
 *
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 1.0
 * @package template
 */
@session_start();

//-- Fonction inhérente à l'entete EWO
include("template_fonction.php");

if(isset($_SESSION['utilisateur']['id'])){
	$sqltpl = "SELECT utilisateur_id, template FROM utilisateurs_option WHERE utilisateur_id = ".$_SESSION['utilisateur']['id']."";
	$resultattpl = mysql_query($sqltpl);
	$templ = mysql_fetch_array ($resultattpl);

	if(!empty($templ['utilisateur_id'])){
		$theme_tpl = $templ['template'];
	}else{
		$theme_tpl = 'Vanilla';
	}
}else{
	$theme_tpl = 'Vanilla';
}

//$theme_tpl = 'colonne';
//-- Lien des fichiers dans le theme
$template_url = $root_url."/template/themes/".$theme_tpl;

/*
echo '<div style="background: url(\'wave.jpg\') no-repeat; width: 800px; height: 600px; text-align: right; margin: auto; ">
	<h1 style="position: relative; top: 230px;  left: -140px;">RAZ en cours...</h1>
	<img src="ajax-loader.gif" style="position: relative;  top: 230px; left: -200px;">
</div>';
exit;*/

//-- Inclusion du header
include($template_url."/header.php");
?>
