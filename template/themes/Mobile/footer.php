<?php
/**
 * Template par defaut - Footer
 *
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 1.0
 * @package template-defaut
 */


// On affiche les CSS supplémentaires liés aux pages
detect_sidebar("footer");

if (isset($pagetype)) {
	$footer = 'footer_' . $pagetype;
} else {
	$footer = 'footer';
}

?>
<!-- End Content -->

<!-- Start footer -->
<div id='<?php echo $footer; ?>' <?php echo $width_content_jeu; ?>>
			<p>Personne ne va lire ce texte</p>
			<?php /*
				$fin = getmicrotime();
				$page_time = round($fin-$debut, 3);
				echo "Page générée en ".$page_time." secondes.</p><br />"; */
				//include(SERVER_ROOT.'/jeu/stat_time.php');
			?>
</div>
<?php 

$js->exportLoad();

?>
            
</body>
</html>
