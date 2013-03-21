<?php
//-- Header --
require_once __DIR__ . '/../../conf/master.php';

/*-- Connexion basic requise --*/
ControleAcces('utilisateur',1);

if(isset($admin_mode))	{
	ControleAcces('admin',1);
}

include(SERVER_ROOT . '/persos/fonctions.php');
include(SERVER_ROOT . '/jeu/fonctions.php');


if(!isset($_POST['pvUp']) || !is_numeric($_POST['pvUp']) || !isset($_POST['recupPvUp']) || !is_numeric($_POST['recupPvUp']) || !isset($_POST['paUp']) || !is_numeric($_POST['paUp']) || !isset($_POST['mouvUp']) || !is_numeric($_POST['mouvUp']) || !isset($_POST['desUp']) || !is_numeric($_POST['desUp']) || !isset($_POST['forceUp']) || !is_numeric($_POST['forceUp']) || !isset($_POST['percUp']) || !is_numeric($_POST['percUp']) || !isset($_POST['nvMagUp']) || !is_numeric($_POST['nvMagUp'])){
	$titre = "Erreur d'update";
	$text = "Donn&eacute;es manaquantes";
	$lien = "./../../persos/upgrades/";
	gestion_erreur($titre, $text, $lien);
}

// Paramètres de connexion à la base de données
$ewo_bdd = bdd_connect('ewo');



// récupération du numéro de personnage dans la session
$perso_id = $_SESSION['persos']['current_id'];

if(in_array($perso_id,$_SESSION['persos']['id'])){
		$id = array_search($perso_id,$_SESSION['persos']['id']);
}
else{
	echo "<script language='javascript' type='text/javascript' >document.location='../../jeu/index.php'</script>";
	exit;
}

// Récupération de la race du personnage dans la session
$_SESSION['persos']['race'][$id];
$race = $_SESSION['persos']['race'][$id];

// Récupération du grade du personnage dans la session
$_SESSION['persos']['grade'][$id];
$grade = $_SESSION['persos']['grade'][$id];

$reponse = mysql_query("SELECT * FROM caracs WHERE perso_id='".$_SESSION['persos']['id'][$id]."'")or die(mysql_error());
$perso_carac = mysql_fetch_array($reponse);
//-------------------------------------------------------------------------\\

$new_pi = $perso_carac['pi'];


include('valeurs.php');//coûts de base



if($_POST['pvUp'] >0){
	// récupération du cout du prochain pallier
	$cout_pi = round(($_POST['pvUp'])*((1 +0.1*($perso_carac['niv_pv']))*$coutPvBase
	+ (1 +0.1*($perso_carac['niv_pv']+$_POST['pvUp'] - 1))*$coutPvBase)/2);
	//echo $cout_pi;
	//vérification que le perso a suffisament de pi
	if(($new_pi - $cout_pi) >= 0){
		// si c'est le cas on mets à jour les caracs 
		//nouvelle valeur du niveau de pv :
		$new_niv = $perso_carac['niv_pv'] + $_POST['pvUp'];
		maj_carac($perso_id, "niv_pv", $new_niv);
		// On met à jour les pi :
		$new_pi -= $cout_pi;
		maj_carac($perso_id, "pi", $new_pi);
	}
	else{
					$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>T'as pas assez d'pi, NOOB. Go pexer !";
					echo "<script language='javascript' type='text/javascript' >document.location='../../jeu/index.php?perso_id=".$id."'</script>";exit;
	}
}
	
if($_POST['recupPvUp'] >0 && $perso_carac['niv_recup_pv']+$_POST['recupPvUp'] <= 5){
	// récupération du cout du prochain pallier
	$cout_pi = round(($_POST['recupPvUp'])*((1 +0.1*($perso_carac['niv_recup_pv']))*$coutRecupPvBase
	+ (1 +0.1*($perso_carac['niv_recup_pv']+$_POST['recupPvUp'] - 1))*$coutRecupPvBase)/2);
	//vérification que le perso a suffisament de pi
	if(($new_pi - $cout_pi) >= 0){
		// si c'est le cas on mets à jour les caracs 
		//nouvelle valeur du niveau de pv :
		$new_niv = $perso_carac['niv_recup_pv'] + $_POST['recupPvUp'];
		maj_carac($perso_id, "niv_recup_pv", $new_niv);
		// On met à jour les pi :
		$new_pi -= $cout_pi;
		maj_carac($perso_id, "pi", $new_pi);
	}
	else{
					$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>T'as pas assez d'pi, NOOB. Go pexer !";
					echo "<script language='javascript' type='text/javascript' >document.location='../../jeu/index.php?perso_id=".$id."'</script>";exit;
	}
}
	
if($_POST['mouvUp'] >0){
		
	// récupération du cout du prochain pallier
	$cout_pi = round(($_POST['mouvUp'])*((1 +0.1*($perso_carac['niv_mouv']))*$coutMouvBase
	+ (1 +0.1*($perso_carac['niv_mouv']+$_POST['mouvUp'] - 1))*$coutMouvBase)/2);

		//vérification que le perso a suffisament de pi
		if(($new_pi - $cout_pi) >= 0){
				// si c'est le cas on mets à jour les caracs 
				//nouvelle valeur du niveau de mouv :
				$new_niv = $perso_carac['niv_mouv'] + $_POST['mouvUp'];
				maj_carac($perso_id, "niv_mouv", $new_niv);
				// On met à jour les pi :
				$new_pi = $new_pi - $cout_pi;
				maj_carac($perso_id, "pi", $new_pi);
			}else{
					//echo $perso_carac['niv_mouv'],'/',$new_niv;
					$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>T'as pas assez d'pi, NOOB. Go pexer !";
					echo "<script language='javascript' type='text/javascript' >document.location='../../jeu/index.php?perso_id=$id'</script>";exit;
				}
}
if($_POST['forceUp'] >0){
		
	// récupération du cout du prochain pallier
	$cout_pi = round(($_POST['forceUp'])*((1 +0.1*($perso_carac['niv_force']))*$coutForceBase
	+ (1 +0.1*($perso_carac['niv_force']+$_POST['forceUp'] - 1))*$coutForceBase)/2);
	
			//vérification que le perso a suffisament de pi
			if(($new_pi - $cout_pi) >= 0){
					// si c'est le cas on mets à jour les caracs 
					//nouvelle valeur du niveau de dés :
					$new_niv = $perso_carac['niv_force'] + $_POST['forceUp'];
					maj_carac($perso_id, "niv_force", $new_niv);
					$force=carac_max ($race, $grade, "force", $new_niv, $perso_id);
					maj_carac($perso_id, "force", $force);
					// On met à jour les pi :
					$new_pi = $new_pi - $cout_pi;
					maj_carac($perso_id, "pi", $new_pi);
				}else 
					{
						$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>T'as pas assez d'pi, NOOB. Go pexer !";
						echo "<script language='javascript' type='text/javascript' >document.location='../../jeu/index.php?perso_id=$id'</script>";exit;
					}
}
		
if($_POST['nvMagUp'] >0 && $_POST['nvMagUp']+$perso_carac['niv'] <= 5){
		
	$i = 1;
	$cout_pi = $perso_carac['niv']*$addNvMag+100;
	while($i < $_POST['nvMagUp']){
		$cout_pi += ($perso_carac['niv']+$i)*$addNvMag+100;
		$i++;
	}
		
			//vérification que le perso a suffisament de pi ET que son niveau de magie n'est pas déjà de 5, car c'est le max possible.
			if(($new_pi - $cout_pi) >= 0 && $perso_carac['niv'] < 5 ) {
					// si c'est le cas on mets à jour les caracs 
					//nouvelle valeur du niveau de magie :
					$new_niv = $perso_carac['niv'] + $_POST['nvMagUp'];
					maj_carac($perso_id, "niv", $new_niv);
					// On met à jour les pi :
					$new_pi = $new_pi - $cout_pi;
					maj_carac($perso_id, "pi", $new_pi);
				}else 
					{
						$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>Niveau de magie maximum atteint. ;)";
						echo "<script language='javascript' type='text/javascript' >document.location='../../jeu/index.php?perso_id=$id'</script>";exit;
					}
}

/*
if($_POST['paUp'] >0){
		
			// récupération du cout du prochain pallier
			$i = 1;
			$cout_pi = floor($perso_carac['niv_pa']/10)*50+75;
			while($i < $_POST['paUp']){
				$cout_pi += floor(($perso_carac['niv_pa']+$i)/10)*50+75;
				$i++;
			}

			//vérification que le perso a suffisament de pi
			if(($new_pi - $cout_pi) >= 0)
			{
			// si c'est le cas on mets à jour les caracs 
			//nouvelle valeur du niveau de PA :
			$new_niv = $perso_carac['niv_pa'] + $_POST['paUp'];
			maj_carac($perso_id, "niv_pa", $new_niv);
			// On met à jour les pi :
			$new_pi = $new_pi - $cout_pi;
			maj_carac($perso_id, "pi", $new_pi);
			}
			else
			{
				$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>T'as pas assez d'pi, NOOB. Go pexer !";
				echo "<script language='javascript' type='text/javascript' >document.location='../../jeu/index.php?perso_id=$id'</script>";exit;
					}
} 
*/

if($_POST['paUp'] >0){
		
	// récupération du cout du prochain pallier
	$cout_pi = round(($_POST['paUp'])*((1 +0.1*($perso_carac['niv_pa']))*$coutPaBase
	+ (1 +0.1*($perso_carac['niv_pa']+$_POST['paUp'] - 1))*$coutPaBase)/2);
	
			//vérification que le perso a suffisament de pi
			if(($new_pi - $cout_pi) >= 0){
					// si c'est le cas on mets à jour les caracs 
					//nouvelle valeur du niveau de dés :
					$new_niv = $perso_carac['niv_pa'] + $_POST['paUp'];
					maj_carac($perso_id, "niv_pa", $new_niv);
					// On met à jour les pi :
					$new_pi = $new_pi - $cout_pi;
					maj_carac($perso_id, "pi", $new_pi);
				}else 
					{
						$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>T'as pas assez d'pi, NOOB. Go pexer !";
						echo "<script language='javascript' type='text/javascript' >document.location='../../jeu/index.php?perso_id=$id'</script>";exit;
					}
}

if($_POST['percUp'] >0){
		$cout_pi = round(($_POST['percUp'])*((1 +0.1*($perso_carac['niv_perception']))*$coutPercBase
	+ (1 +0.1*($perso_carac['niv_perception']+$_POST['percUp'] - 1))*$coutPercBase)/2);
		
		//vérification que le perso a suffisament de pi
		if(($new_pi - $cout_pi) >= 0){
			// si c'est le cas on mets à jour les caracs 
			//nouvelle valeur du niveau de perception :
			$new_niv = $perso_carac['niv_perception'] + $_POST['percUp'];
			maj_carac($perso_id, "niv_perception", $new_niv);
			$perception = carac_max ($race, $grade, "perception", $new_niv, $perso_id);
			maj_carac($perso_id, "perception", $perception);
			// On met à jour les pi :
			$new_pi -= $cout_pi;
		}
		else{
			$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>T'as pas assez d'pi, NOOB. Go pexer !";
			echo "<script language='javascript' type='text/javascript' >document.location='../../jeu/index.php?perso_id=$id'</script>";exit;
		}
}
if($_POST['desUp'] >0){	
	$cout_pi = round(($_POST['desUp'])*((1 +0.1*($perso_carac['niv_des']))*$coutDesBase
	+ (1 +0.1*($perso_carac['niv_des']+$_POST['desUp'] - 1))*$coutDesBase)/2);
		//vérification que le perso a suffisament de pi
		if(($new_pi - $cout_pi) >= 0){
			// si c'est le cas on mets à jour les caracs 
			//nouvelle valeur du niveau de dés :
			$new_niv = $perso_carac['niv_des'] + $_POST['desUp'];
			maj_carac($perso_id, "niv_des", $new_niv);
			// On met à jour les pi :
			$new_pi = $new_pi - $cout_pi;
			maj_carac($perso_id, "pi", $new_pi);
		}
		else{
			$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>T'as pas assez d'pi, NOOB. Go pexer !";
			echo "<script language='javascript' type='text/javascript' >document.location='../../jeu/index.php?perso_id=$id'</script>";
			exit;
		}
}

if($new_pi != $perso_carac['pi'])
	maj_carac($perso_id, "pi", $new_pi);
echo "<script language='javascript' type='text/javascript' >document.location='.'</script>";
exit;
?>
