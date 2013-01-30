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
        $compte = new compte\Compte($_SESSION['utilisateur']['id']);


	if(!empty($compte->template)){
		$theme_tpl = $compte->template;
	}else{
		$theme_tpl = 'Vanilla';
	}
}else{
	$theme_tpl = 'Vanilla';
}

//$theme_tpl = 'colonne';
//-- Lien des fichiers dans le theme
$template_url = "/template/themes/".$theme_tpl;

/*
echo '<div style="background: url(\'wave.jpg\') no-repeat; width: 800px; height: 600px; text-align: right; margin: auto; ">
	<h1 style="position: relative; top: 230px;  left: -140px;">RAZ en cours...</h1>
	<img src="ajax-loader.gif" style="position: relative;  top: 230px; left: -200px;">
</div>';
exit;*/

//-- Inclusion du header
include(SERVER_ROOT . $template_url . "/header.php");
?>
