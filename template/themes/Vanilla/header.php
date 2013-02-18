<?php
/**
 * Template par defaut - Header
 *
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 1.0
 * @package template-defaut
 */

require_once __DIR__ . '/../../../conf/master.php';

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
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">


<meta name="author" content="Ewo Team" />

<link rel="shortlink" href="ewo.fr">

<!-- IE + Windows Meta -->
<meta http-equiv="imagetoolbar" content="false">

<meta name="application-name" content="EWO">

<meta name="msapplication-tooltip" content="Eternal War One">

<meta name="msapplication-starturl" content="<?php echo SERVER_URL; ?>">

<meta name="msapplication-TileColor" content="#FFFFCC">
<meta name="msapplication-navbutton-color" content="#FFFFCC">

<meta name="msapplication-TileImage" content="<?php echo SERVER_URL; ?>/images/site/apple-touch-icon-114x114-precomposed.png">

<!-- OpenGraph (fb) -->
<meta property="og:image" content="<?php echo SERVER_URL; ?>/images/site/apple-touch-icon-114x114-precomposed.png">

<!-- Twitter -->
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="@EwoLeMonde">
<meta name="twitter:image" content="<?php echo SERVER_URL; ?>/images/site/apple-touch-icon-72x72-precomposed.png">

<!-- Apple Touch Icon -->
<!-- For third-generation iPad with high-resolution Retina display: -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="http://www.ewo.fr/img/apple-touch-icon-144x144-precomposed.png">
<!-- For iPhone with high-resolution Retina display: -->
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="http://www.ewo.fr/img/apple-touch-icon-114x114-precomposed.png">
<!-- For first- and second-generation iPad: -->
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="http://www.ewo.fr/img/apple-touch-icon-72x72-precomposed.png">
<!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
<link rel="apple-touch-icon-precomposed" sizes="57x57" href="http://www.ewo.fr/img/apple-touch-icon-57x57-precomposed.png">		
<!-- Fallback -->
<link rel="apple-touch-icon-precomposed" href="http://www.ewo.fr/img/apple-touch-icon-precomposed.png">		


<meta name="google-site-verification" content="rZADXCyuEh8aaWXfEkxQxz4uSd_X0k7Ksfw0Td7gimQ" />

<link rel="stylesheet" href="<?php echo SERVER_URL; ?>/css/normalize.css?v=<?php echo filemtime(SERVER_ROOT.'/css/normalize.css') ?>" type="text/css" />

<?php

	echo '<link rel="stylesheet" href="'.SERVER_URL.$template_url.'/css/jquery-ui.css?v='.filemtime(SERVER_ROOT.$template_url.'/css/jquery-ui.css').'" type="text/css" />';

	//error_reporting(E_ALL);
        
    include(SERVER_ROOT.'/template/less/lessc.inc.php');
    //try {
        $less = new lessc();

        $less->addImportDir(SERVER_ROOT . $template_url.'/less');
        $less->addImportDir(SERVER_ROOT . '/site/fonts');
        //$less->setFormatter("compressed");

        $less->setVariables(array(
        "template" => "'..'",
		"root" => "'".SERVER_URL."'"
        ));

        
        $less->compileFile(SERVER_ROOT . $template_url.'/less/ewo.less', SERVER_ROOT . $template_url.'/css/ewo.css'); 
        //$less->checkedCompile(SERVER_ROOT . $template_url.'/less/ewo.less', SERVER_ROOT . $template_url.'/css/ewo.css');    
    //} catch (Exception $e) {
        // Nothing to do here
    //}
    echo '<link rel="stylesheet" href="'. SERVER_URL . $template_url.'/css/ewo.css?v='.filemtime( SERVER_ROOT . $template_url.'/css/ewo.css').'" type="text/css" />' . PHP_EOL;
    
    // Fichiers CSS supplémentaires
    if(isset($css_files)) {
        $nom = md5($css_files);
        $array = explode(",", $css_files);

        

        //if(file_exists(SERVER_ROOT . $template_url.'/css/generate/'.$nom.'.css')) {
       // $time_gen = filemtime(SERVER_ROOT . $template_url.'/css/generate/'.$nom.'.css');
        //} else {
           $time_gen = 0; 
        //}
        $compile = false;
        $import = '@import url(couleurs.less);';

        foreach ($array as $link) {
            
            $less_url = SERVER_ROOT . $template_url.'/less/'.$link.'.less';
   
            if(filemtime($less_url) > $time_gen) {
                $compile = true;
            }
            
            $import .= '@import url('.$link.'.less);';
            
        }
        
        if($compile) {
            //try {  
            $compiled = $less->compile($import);
            
            if(substr(decoct( fileperms(SERVER_ROOT . $template_url.'/css/generate/') ), 2) != 777) {
                chmod(SERVER_ROOT . $template_url.'/css/generate/', 777);
            }
            
            file_put_contents(SERVER_ROOT . $template_url.'/css/generate/'.$nom.'.css', $compiled);
            //} catch(Exception $e) {
                // Nothing to do here
            //}
            
        }
        
        echo '<link rel="stylesheet" href="'.SERVER_URL . $template_url.'/css/generate/'.$nom.'.css?v='.filemtime(SERVER_ROOT.$template_url.'/css/generate/'.$nom.'.css').'" type="text/css" />' . PHP_EOL;        
    }
?>

<link rel="icon" type="image/png" href="<?php echo SERVER_URL; ?>/images/site/favicon.png" />

<script src="<?php echo SERVER_URL; ?>/js/lib/prefixfree.min.js" type="text/javascript"></script>
<script src="<?php echo SERVER_URL; ?>/js/jeu/autologin.js" type="text/javascript"></script>


<?php

require(SERVER_ROOT . '/template/JSLoader.php');

$js = new JSLoader(SERVER_URL);

$js->addScript('ajax');
$js->addScript('jeu');
$js->addCore('lib/jquery');
$js->addLib('jquery-ui');
$js->addVariables('root_url', SERVER_URL);

// Gestionnaire d'autologin
include(SERVER_ROOT."/autologin.php");

?>
</head>

<body>

<?php
//-- Menu top bar --
include(SERVER_ROOT."/site/menu.php"); ?>

<?php 
detect_sidebar("header");
?>