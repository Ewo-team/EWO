<?php
/**
 * Connexion - Index du forumulaire de connexion
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package connexion
 */
 
//-- Header --
$root_url = "..";
$header['title'] = "Connexion au site Ewo";
$header['desc'] = "Pour pouvoir vous connecter sur notre jeu, il faut que vous utilisiez cette page.";
include($root_url."/template/header_new.php");
//------------
?>
<div id="page_connexion">
<h2>Page de connexion</h2>

<!-- Debut du coin -->
<div class="upperleft" id='coin_75'>
	<div class="upperright">
		<div class="lowerleft">
			<div class="lowerright">
			<!-- conteneur -->
			
<?php
	if (!isset($_SESSION['utilisateur']['id'])) { ?>
		<div align='center'>	
			<?php
				if($_SSL == 1){
					echo "<form method='post' action='https://".$_URL."/connexion/connexion.php'>";
				}else{
					echo "<form method='post' action='connexion.php'>";	
				}
			?>
			<table width="50%" border="0">
				<tr align="center">
					<td style="text-align:center;">Nom d'utilisateur</td>
                                        <td ><input type="text" name="login" size="13" value="" /></td>
				</tr>
				<tr>
					<td style="text-align:center;">Mot de Passe </td>
                                        <td style="text-align:center;"><input type="password" name="pass" size="13" value="" /></td>
				</tr>
                                <tr>
					<td style="text-align:center;">Se souvenir de moi ?</td>
                                        <td style="text-align:center;"><input type="checkbox" name="autologin"></td>
				</tr>
                                <tr>
					<td style="text-align:center;"><a href='<?php echo $root_url; ?>/inscription/'>S'inscrire</a></td>
					<td style="text-align:center;"><a href='<?php echo $root_url; ?>/inscription/renvoiActivation.php'>Renvoi du mail d'activation</a></td>
				</tr>  
				<tr>
					<td style="text-align:center;"><a href="recuperation.php">Mot de passe oublié ?</a></td>
					<td style="text-align:center;"><input class="bouton" type="submit" value="Connexion" />
						<img src="<?php echo $root_url; ?>/images/site/ssl.png" alt='certificat ssl'>
					</td>
				</tr>                                
		  </table>
		</form>
		</div>
	<?php }else{ ?>
	
		<div align='center'>
		<table width="300" border="0">
			<tr><td style="text-align:center;">Vous êtes loggué en tant que :</td></tr>
			<tr><td style="text-align:center;"><b><?php echo $_SESSION['utilisateur']['nom']; ?></b></td></tr>
	<?php
		// Test pour la page d'admin...
		if (isset ($_SESSION['utilisateur']['rang']) && ($_SESSION['utilisateur']['rang'] == 'admin')) {
	?>
			<tr><td style="text-align:center;"><a href='?page=admin_admin'>[ Admin ]</a></td></tr>
	<?php
		}
			echo"<tr><td style='text-align:center;'><a href='".$root_url."/session.php'>[ Déconnexion ]</a></td></tr>
					 </table></div>";
	}
?>
</div>

			<!-- fin conteneur -->
			</div>
		</div>
	</div>
</div>
<!-- Fin du coin -->
<?php
//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>
