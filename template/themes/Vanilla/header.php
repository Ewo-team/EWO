<?php
/**
 * Template par defaut - Header
 *
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 1.0
 * @package template-defaut
 */
$template_vanilla = true;
$_SESSION['utilisateur']['template_mage']=true;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<?php
	if(isset($header['title'])){
		echo "<title>".$header['title']." | Eternal War One - EWO</title>";
	}else{
		echo "<title>Eternal War One - EWO</title>";
	}
	if(isset($header['desc'])){
		echo "<meta name='description' content=\"".$header['desc']."\" />";
	}else{
		echo "<meta name='description' content=\"Eternal War One est un jeu multi-joueur où une armée de démons, d'anges et d'humains s'affrontent dans une lutte sans merci pour leur propre survie.\" />";
	}
?>

<meta name="author" content="Ewo Team" />
<meta name="copyright" content="Janvier 2010" />
<meta name="contact" content="aigleblanc@gmail.com" />
<meta name="google-site-verification" content="rZADXCyuEh8aaWXfEkxQxz4uSd_X0k7Ksfw0Td7gimQ" />

<?php

	echo '<link rel="stylesheet" href="'.$template_url.'/css/jquery-ui.css?v='.filemtime($template_url.'/css/jquery-ui.css').'" type="text/css" />';

	//error_reporting(E_ALL);
        
    include($root_url.'/template/less/lessc.inc.php');
    try {
        $less = new lessc();

        $less->addImportDir($template_url.'/less');
        //$less->setFormatter("compressed");

        $less->setVariables(array(
        "template" => "'..'"
        ));

        $less->checkedCompile($template_url.'/less/ewo.less', $template_url.'/css/ewo.css');    
    } catch (Exception $e) {
        // Nothing to do here
    }
    echo '<link rel="stylesheet" href="'.$template_url.'/css/ewo.css?v='.filemtime($template_url.'/css/ewo.css').'" type="text/css" />';
    
    // Fichiers CSS supplémentaires
    if(isset($css_files)) {
        $nom = md5($css_files);
        $array = explode(",", $css_files);

        

        if(file_exists($template_url.'/css/generate/'.$nom.'.css')) {
        $time_gen = filemtime($template_url.'/css/generate/'.$nom.'.css');
        } else {
           $time_gen = 0; 
        }
        $compile = false;
        $import = '@import url(couleurs.less);';

        foreach ($array as $link) {
            
            $less_url = $template_url.'/less/'.$link.'.less';
   
            if(filemtime($less_url) > $time_gen) {
                $compile = true;
            }
            
            $import .= '@import url('.$link.'.less);';
            
        }
        
        if($compile) {
            try {  
            $compiled = $less->compile($import);
            
            if(substr(decoct( fileperms('css/generate/') ), 2) != 777) {
                chmod('css/generate/', 777);
            }
            
            file_put_contents($template_url.'/css/generate/'.$nom.'.css', $compiled);
            } catch(Exception $e) {
                // Nothing to do here
            }
            
        }
        
        echo '<link rel="stylesheet" href="'.$template_url.'/css/generate/'.$nom.'.css?v='.filemtime($template_url.'/css/generate/'.$nom.'.css').'" type="text/css" />';        
    }
?>

<link rel="icon" type="image/png" href="<?php echo $root_url; ?>/images/site/favicon.png" />

<script src="<?php echo $root_url; ?>/js/lib/prefixfree.min.js" type="text/javascript"></script>
<script src="<?php echo $root_url; ?>/js/jeu/autologin.js" type="text/javascript"></script>


<?php

require($root_url . '/template/JSLoader.php');

$js = new JSLoader($root_url);

$js->addScript('ajax');
$js->addScript('jeu');
$js->addCore('lib/jquery');
$js->addLib('jquery-ui');
$js->addVariables('root_url', $root_url);

// Gestionnaire d'autologin
include($root_url."/autologin.php");

?>
</head>

<body>

<?php
//-- Menu top bar --
include($root_url."/menus/menu_liste.php"); ?>

<?php 
detect_sidebar("header");
?>
