<?php
/**
 * Connexion sur l'api d'EWO
 *
 * 
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 0.1
 * @package api
 */
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");

if($_SERVER["HTTPS"] != "on") {
	 header("Location: https://" . $_URL . $_SERVER["REQUEST_URI"]);
	 exit();
}

if(isset($_REQUEST['login']) && isset($_REQUEST['pass'])){
	echo connect_api($_REQUEST['login'],$_REQUEST['pass']);
}else{
	echo json_encode(array('statut'=>'refuse'));exit;
}

function connect_api($login_api,$pass_api){
	$login = $login_api;
	$pass = $pass_api;
		
	if(isset($login) && isset($pass)){	
		// Paramètres de connexion à la base de données
		$ewo = bdd_connect('ewo');

		$utilisateur = mysql_real_escape_string(ucfirst(htmlspecialchars(strip_tags($login),ENT_COMPAT, 'UTF-8')));
		$pass = mysql_real_escape_string($pass);

		$sql="SELECT id,nom,passwd,droits FROM utilisateurs WHERE nom = '$utilisateur'";
		$resultat = mysql_query ($sql) or die (mysql_error());
		$connexion = mysql_fetch_array ($resultat);
	
		$utilisateur_id = $connexion['id'];
		$date_courant = time();

		//-- Verfification dans la table de bannissement
		$sql1="SELECT*FROM utilisateurs_ban WHERE utilisateur_id = '$utilisateur_id'";
		$resultat1 = mysql_query ($sql1) or die (mysql_error());
		$ban_controle = mysql_fetch_array ($resultat1);
		
		if(!empty($ban_controle['utilisateur_id'])){
			$date         = $ban_controle['date'];
			$date_fin     = $ban_controle['date_fin'];
			$date_courant = time();
			$_SESSION['utilisateur']['droits_date']=$date_courant;
			$date = date("d-m-Y", $date);
			$date_fin_t = date("d-m-Y à H:i", $date_fin);
		
			if($date_fin < $date_courant){
				// On supprime le ban
				mysql_query("DELETE FROM utilisateurs_ban WHERE utilisateur_id='$utilisateur_id'") or die (mysql_error());
				$statut = "ok";
			}else{
				$statut = "bannie";
			}
		}else{
			$statut = "ok";
		}
		
		// Check du pass
		$hash = $connexion['passwd'];
		
		//-- Gestion du hash du mot de passe
		$pass = hash('sha256',$pass);
		
		if ($hash == $pass){
			$passhash = true;
		}else{
			$passhash = false;
		}
		
		if(($passhash == true) && ($statut == "ok")){
			$retour = array('statut'=>$statut,'utilisateur'=>$connexion['nom']);
			$_SESSION['API']['id_utilisateur'] = $utilisateur_id;
		}else{
			$retour = array('statut'=>'refuse');
		}		
		return json_encode($retour);
	}else{
		return json_encode(array('statut'=>'refuse'));
	}
}
?>
