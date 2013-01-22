<?php
//-- Header --
$root_url = "./../..";
include($root_url."/template/header_new.php");
/*-- Connexion at ou admin requise --*/
ControleAcces('admin',1);
/*-----------------------------*/
if (isset($_POST['text']) && isset($_POST['titre'])){

$titre = mysql_real_escape_string($_POST['titre']);
$text = mysql_real_escape_string($_POST['text']);

// Paramètres de connexion à la base de données
include("./../../conf/connect.conf.php");
mysql_connect($_SERVEUR,$_USER,$_PASS);
mysql_select_db($_BDD);

$mails = "SELECT email FROM utilisateurs";
	
	$email = '';
	$resultat = mysql_query ($mails) or die (mysql_error());
	while ($mail = mysql_fetch_array ($resultat)){
		$email = $mail['email'].','.$email;
	}
	
	//mail de confirmation
	  $headers ='From: "EwoManager"<ewomanager@ewo.fr>'."\n";
    $headers .='Reply-To: ewomanager@ewo.fr'."\n";
    $headers .='BCC: '.$email."\n";
    $headers .='Content-Type: text/html; charset="iso-8859-1"'."\n"; 
    $headers .='Content-Transfer-Encoding: 8bit';
			
		$message = "<html><head><title>EWO</title></head><body>
<table width='800px'>
	<tr style='background-color:#B0B0B0'>
		<td colspan='3'><img src='http://ewo.linux-experience.fr/images/site/ewo_logo_mini.png'></td>
	</tr>
	<tr>
		<td width='15px' style='background-color:#B0B0B0'></td>
		<td>
			<table width='100%' height='200px'>
				<tr>
					<td style='background: url(http://ewo.linux-experience.fr/images/site/ewo_transparant.png) no-repeat 50% 50%'>
								<span align='center'>$titre</span>
								<p>$text</p>
								<a href='http://ewo.linux-experience.fr/'>Ewo le monde</a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan='3' align='center'  style='background-color:#B0B0B0;font-size:0.8em;'>[Ewo] www.ewo-le-monde.com &copy; </td>
	</tr>
</body></html>";

		$date = date('d-M-Y');

		if(mail('aigleblanc@gmail.com', '[Ewo] Newsletter du '.$date, $message, $headers))
     {
          echo "<div class='page_centre'><h1>Newsletter envoye</h1><p>Votre newsletter a été remis sans probleme</p>";
          echo "<p><a href='./'>[ Retour ]</a></p></div>";
          include("./../../template/footer.php");
     }
}
?>
