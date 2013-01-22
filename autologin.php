<?php 
if (!isset($_SESSION['utilisateur']['id'])) {
	
	if(isset($_SESSION['autologin']['unlogin'])) {

		// Suppression de l'autologin
		
		?>
		<script type="text/javascript">
			removeLogin();
		</script>
		<?php	

		unset($_SESSION['autologin']['unlogin']);

	} else {	
	
		// Appel de l'autologin
		
		?>
		<script type="text/javascript">
			var url = "<?php echo $_URL; ?>";
			var https = <?php echo $_SSL; ?>;
			autologin();
		</script>
		<?php
	}
	
} elseif(isset($_SESSION['autologin']['newticket'])) {

	// Ajout ou renouvellement du ticket
	
	?>
	<script type="text/javascript">
		addLogin("<?php echo $_SESSION['autologin']["ticket"] ?>","<?php echo $_SESSION['autologin']["login"] ?>");
	</script>
	<?php	
	
	unset($_SESSION['autologin']['newticket']);

}
?>