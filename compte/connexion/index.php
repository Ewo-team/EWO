<?php
/**
 * Connexion - Index du forumulaire de connexion
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package connexion
 */
 
//-- Header --
require_once __DIR__ . '/../../conf/master.php';
$header['title'] = "Connexion au site Ewo";
$header['desc'] = "Pour pouvoir vous connecter sur notre jeu, il faut que vous utilisiez cette page.";
include(SERVER_ROOT . "/template/header_new.php");
//------------
?>
<div id="page_connexion">
<h2>Page de connexion</h2>

<!-- Debut du coin -->
<div>
			<!-- conteneur -->
			
<?php
	if (!isset($_SESSION['utilisateur']['id'])) { ?>
		<div align='center'>	
			<?php
				echo "<form method='post' action='".SERVER_URL."/compte/connexion/connexion.php'>";
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
					<td style="text-align:center;"><a href='<?php echo SERVER_URL; ?>/compte/inscription/'>S'inscrire</a></td>
					<td style="text-align:center;"><a href='<?php echo SERVER_URL; ?>/compte/inscription/renvoiActivation.php'>Renvoi du mail d'activation</a></td>
				</tr>  
				<tr>
					<td style="text-align:center;"><a href="recuperation.php">Mot de passe oublié ?</a></td>
					<td style="text-align:center;"><input class="bouton" type="submit" value="Connexion" />
						<img src="<?php echo SERVER_URL; ?>/images/site/ssl.png" alt='certificat ssl'>
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
			echo"<tr><td style='text-align:center;'><a href='".SERVER_URL."/session.php'>[ Déconnexion ]</a></td></tr>
					 </table></div>";
	}
?>
</div>

			<!-- fin conteneur -->
</div>
<!-- Fin du coin -->
<?php
//-- Footer --
include(SERVER_ROOT."/template/footer_new.php");
//------------
?>
