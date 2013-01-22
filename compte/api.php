<?php
/**
 * Compte, Options avancées
 *
 *	Affiche la page des options avancées
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package compte
 */
 
//-- Header --
$root_url = "..";
session_start();
include ("CompteDAO.php");

$conn = CompteDAO::getInstance();

$id_utilisateur = $_SESSION['utilisateur']['id'];

if(isset($_REQUEST['action'])) {
	if($_REQUEST['action'] == 'ajouter') {
		if(!empty($_REQUEST['nom']) AND isset($_REQUEST['niveau'])) {
			$conn->InsertKey($id_utilisateur, $_REQUEST['niveau'], $_REQUEST['nom']);
			header("Location: api.php");
		}
	}
	
	if($_REQUEST['action'] == 'supprimer') {
		if(isset($_REQUEST['cle'])) {
			$conn->DeleteKey($id_utilisateur, $_REQUEST['cle']);
			header("Location: api.php");
		}
	}	
	
	if($_REQUEST['action'] == 'recharger') {
		if(isset($_REQUEST['cle'])) {
			$conn->RenewKey($id_utilisateur, $_REQUEST['cle']);
			header("Location: api.php");
		}
	}		
}
require_once($root_url."/template/header_new.php");
$resultat = $conn->SelectKeys($id_utilisateur);		

?>
<link href="../css/popup.css" type="text/css" rel="stylesheet" />
<div align="center">
<h2>Clé d'API du compte</h2>
<div id="popup">  
	<a id="popupClose">x</a>  
	<h1>Cle</h1>  
	<p>
		<h2>Calendrier (iCal)</h2>
		<input type="text" value="" size="50" id="ical_link">
	</p>
	
	<p>
		<h2>Sites externes</h2>
		<span>pas encore disponible</span>
	</p>	
</div>
<table border="0">
<tr>
<th>Nom de la clé (cliquez pour obtenir les liens)</th>
<th>Visibilité</th>
<th></th>
</tr>
<?php
	foreach($resultat as $ligne) {
		
		$cle = $ligne['cle'];
		
		if($ligne['niveau'] == 'full') {
			$visibilite = "clé secrète";
		} elseif ($ligne['niveau'] == 'private') {
			$visibilite = "information privées";
		} else {
			$visibilite = "information publiques";
		}
		
		echo "<tr>
		<td><a href='#' id='$cle' class='popup'>".$ligne['nom']."</a></td>
		<td>$visibilite</td>
		<td><a href='api.php?action=recharger&cle=$cle'>recharger</a> <a href='api.php?action=supprimer&cle=$cle'>supprimer</a></td>
		</tr>";
	}
?>
</table>
<h3>Nouvelle clé:</h3>
<form action="api.php" method="post">
<input type="hidden" name="action" value="ajouter">
Nom de la clé : <input type="text" maxlength="50" size="55" name="nom"><br />
Visibilité : 
<input type="radio" name="niveau" value="public" checked>Publique |
<input type="radio" name="niveau" value="private">Privée |
<input type="radio" name="niveau" value="full">Secrète<br />
<input type="submit">
</div>

<?php

$js->addScript('api');

//-- Footer --
include($root_url."/template/footer_new.php");
//------------
?>