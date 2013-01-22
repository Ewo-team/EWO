<?php
/**
 * Template par defaut - Footer
 *
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 1.0
 * @package template-defaut
 */
detect_sidebar("footer");

// On affiche les CSS supplémentaires liés aux pages

?>
<!-- End Content -->

<!-- Start footer -->
<div class='footer-general'>
	<div class='footer-1'></div>

	<div class="footer-2">
		<div class='footer-content'>
			<p id='footertext'><b>Ewo</b> designé et développé par <b>La Team Ewo</b><br />
			<?php
				$fin = getmicrotime();
				$page_time = round($fin-$debut, 3);
				echo "Page générée en ".$page_time." secondes.</p><br />";
				//include($root_url.'/jeu/stat_time.php');
			?>
		</div>
	</div>
	<!-- Piwik -->
	<script type="text/javascript">
	var pkBaseURL = (("https:" == document.location.protocol) ? "https://piwik.ewo.fr/" : "http://piwik.ewo.fr/");
	document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
	</script><script type="text/javascript">
	try {
	var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 1);
	piwikTracker.trackPageView();
	piwikTracker.enableLinkTracking();
	} catch( err ) {}
	</script><noscript><p><img src="http://piwik.ewo.fr/piwik.php?idsite=1" style="border:0" alt="" /></p></noscript>
	<!-- End Piwik Tag -->
<?php 

$js->exportLoad();

?>
            
</body>
</html>
