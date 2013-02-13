<?php
/**
 * Connexion - Controle des droits sur ewo
 *
 * Fonction de gestion des droits autorisé pour les utilisateurs de ewo
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package connexion
 */

/**
 * Controle des droits
 *  @param redirec = 0 return false or true; 1 redirection au root
 */
function ControleAcces($acces,$redirec){
global $template_on;

// Param�tres de connexion � la base de donn�es
$ewo_bdd = bdd_connect('ewo');

	$acces = explode(';',$acces);
	
	if (isset($_SESSION['utilisateur']['droits']) && isset($_SESSION['utilisateur']['droits_date'])){
		$time=time();
		if($_SESSION['utilisateur']['droits_date']>($time-3600)){
			$droits = $_SESSION['utilisateur']['droits'];
			$droits = str_split($droits);
			}else{
				$utilisateur_id = $_SESSION['utilisateur']['id'];
				$sql="SELECT droits FROM utilisateurs WHERE id = '$utilisateur_id'";
				$resultat = mysql_query ($sql) or die (mysql_error());
				$connexion = mysql_fetch_array ($resultat);
				$_SESSION['utilisateur']['droits'] = $connexion['droits'];
				$droits = $_SESSION['utilisateur']['droits'];
				$droits = str_split($droits);
				//-- Verfification dans la table de bannissement
				$sql1="SELECT*FROM utilisateurs_ban WHERE utilisateur_id = '$utilisateur_id'";
				$resultat1 = mysql_query ($sql1) or die (mysql_error());
				$ban_controle = mysql_fetch_array ($resultat1);
				
				if(!empty($ban_controle['utilisateur_id'])){
				
					$date         = $ban_controle['date'];
					$date_fin     = $ban_controle['date_fin'];
					$date_courant = time();
					
					$date = date("d-m-Y", $date);
					$date_fin_t = date("d-m-Y à H:i", $date_fin);
				
					if($date_fin < $date_courant){
						mysql_query("DELETE FROM utilisateurs_ban WHERE utilisateur_id='$utilisateur_id'") or die (mysql_error());
					}else{
						$titre = "Personnage banni";
						$text = "Cet utilisateur est banni ! <br /> 
											Depuis le : ".$date.".<br /> Le ban prendra fin le : ".$date_fin_t."<br />
											Motif : ".$ban_controle['motif'];
						$lien = "..";
						gestion_erreur($titre, $text, $lien);
					}
				}
			
				}
	}else{
		//echo "Aucune session utilisateur n'existe";exit;
		$droits = array (0,0,0,0);
	}
	
	$logueurs = array ('utilisateur', 'admin', 'anim', 'at');
	
	$longueur_droit = count($droits);
	$longueur_logueurs = count($logueurs);

	if ($longueur_droit < $longueur_logueurs){
		$longueur_diff = $longueur_logueurs - $longueur_droit;
		for($i=0;$i<$longueur_diff;$i++){
			$droits[] = '0';
		}
	}elseif($longueur_droit > $longueur_logueurs){
		$longueur_diff = $longueur_droit - $longueur_logueurs;
		for($i=0;$i<$longueur_diff;$i++){
			$logueurs[] = 'unknow';
		}		
	}

	foreach ($logueurs as $key => $logueur) {
		if($logueur != 'unknow'){
			$valide[$logueur] = $droits[$key];
		}else{
			$valide[$logueur] = '0';
		}
	}
	
	//print_r($valide);
	
	foreach ($valide as $key => $val) {
		foreach ($acces as $acce) {
			if($key == $acce && $val == 1){
				$validation[] = 'true';
			}else{
				$validation[] = 'false';
			}
		}
	}
	
	//print_r($validation);

	if(in_array('true', $validation) == 'true'){
		$validite = "true";
	}else{
		$validite = "false";
	}

	//echo $validite;exit;
	
	if($redirec == 0){
		if($validite == 'true'){
			return true;
		}else{
			return false;
		}
	}else{
		if($validite == 'true'){
			return true;
		}else{
			if(isset($template_on)){
				echo "<script language='javascript' type='text/javascript' >document.location='".SERVER_URL."'</script>";
				exit;
			}else{
				header("location: ".SERVER_URL);
				exit;
			}
		}
	}
}
?>
