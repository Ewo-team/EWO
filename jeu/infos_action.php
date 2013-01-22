<?php
session_start();

$root_url = "..";

include("../conf/master.php");
include("../persos/fonctions.php");
include("./fonctions.php");
// On include la classe processingEvents
include_once('../eventsManager/eventsManager.php');
// On instancie l'eventManager.

ControleAcces('utilisateur',1);

if(isset($_GET['type'])){
	$type=$_GET['type'];

} else $type='';

$info='';//$_SESSION['temp']['info_action'];
$type_action='';
$reussite=0;

if($type='action'){
	if(isset($_SESSION['action']['erreur'])){
		$info=$_SESSION['action']['erreur'];
		unset($_SESSION['action']);
		echo $info; exit;
	}

	if(isset($_SESSION['action']['type'])){
	    $idAction = $_SESSION['action']['id'];
		$type_action=$_SESSION['action']['type'];
		unset($_SESSION['action']);
	}

	if(isset($_SESSION['persos']['current_id'])){
	  $current_perso_id = $_SESSION['persos']['current_id'];
	}


  //$info = $info."type action : |".$type_action."|<br>";


	if(isset($_SESSION['reussite'])){
		$reussite=$_SESSION['reussite'];
		if($type_action!="attaque"){
      // On déclare la réussite ou l'échec de l'action au gestionnaire d'évènement
      if(!$_SESSION['reussite']){
				$info = $info."<b>Votre $type_action a &eacute;chou&eacute;</b><br/><br/>";
			}else{
				$info = $info."<b>Votre $type_action a r&eacute;ussi !</b><br/><br/>";
			}
		}else {
      $info = $info."<b>R&eacute;sultat du combat :</b><br/><br/>";
    }
		unset($_SESSION['reussite']);
	}
	if(isset($_SESSION['score']['att']) && $type_action=="attaque"){

		$info = $info."Votre score d'attaque : ".$_SESSION['score']['att']."<br/>";
		$info = $info."Son score de d&eacute;fense : ".$_SESSION['score']['def']."<br/>";
		$info = $info."Malus inflig&eacute;s : ".$_SESSION['event_effect']['malus']."<br/>";
		if(isset($_SESSION['GD']))
			$info .="Guard Damage inflig&eacute;s : ".$_SESSION['GD']['deg']."<br/>";

		unset($_SESSION['GD']);
	}else{
  	  //if($type_action=="attaque"){$event_Attaque->addEvent('cible','objet');}
  }

	if(isset($_SESSION['esquive']['nb']) && $reussite){
		$esquive=0;
		$nb=0;
		$nb_esq=0;
		$liste_esquive="";
		$liste = "";
		for($inci=1;$inci<=$_SESSION['esquive']['nb'];$inci++){
			if($_SESSION['esquive']['val'][$inci]){
				$nb_esq++;
				($nb_esq==1)?$liste_esquive=$liste_esquive.$_SESSION['esquive']['nom'][$inci]." (Mat.".$_SESSION['esquive']['mat'][$inci].")":$liste_esquive=$liste_esquive.", ".$_SESSION['esquive']['nom'][$inci]." (Mat.".$_SESSION['esquive']['mat'][$inci].")";
			} else {
				$nb++;
				($nb==1)?$liste=$liste.$_SESSION['esquive']['nom'][$inci]." (Mat.".$_SESSION['esquive']['mat'][$inci].")":$liste=$liste.", ".$_SESSION['esquive']['nom'][$inci]." (Mat.".$_SESSION['esquive']['mat'][$inci].")";
			}
			$esquive=max($_SESSION['esquive']['val'][$inci], $esquive);
		}


    if($nb==0 && $type_action!="attaque"){//magie Raté
		$info = $info."<p>Votre $type_action n'a touch&eacute personne";
		$l_sort=-1;
	} elseif($nb!=0 && $type_action!="attaque"){//magie Reussi
		$info = $info."<p>Votre $type_action a touch&eacute; : $liste ";
        $l_sort=$liste;
	}elseif($nb!=0){//Attaque
    	$info = $info."<p>Vous avez touch&eacute; $liste ";
		}

		if($nb!=0 && $type_action=="attaque"){
			$info = $info."<br/>D&eacute;g&acirc;ts : ".$_SESSION['score']['degats'];
		}

		if($type_action=="sort"){
			$type_effet="";

			for($inci=1;$inci<=$_SESSION['effet']['cible']['nb'];$inci++){
				$ok = 0;
				switch($_SESSION['effet']['cible']['nom'][$inci]){
					case "pv" :
						$type_effet="Alt&eacute;ration des points de vie (peut &ecirc;tre d&eacute;gressif avec la distance): ";
						$ok = 1;
						break;

					case "alter_pa" :
						$type_effet="Alt&eacute;ration de la capacit&eacute; d'action : ";
						$ok = 1;
						break;

					case "alter_recup_pv" :
						$type_effet="Alt&eacute;ration de la r&eacute;cup&eacute;ration : ";
						$ok = 1;
						break;

					case "alter_force" :
						$type_effet="Alt&eacute;ration de la force : ";
						$ok = 1;
						break;

					case "alter_perception" :
						$type_effet="Alt&eacute;ration de la perception : ";
						$ok = 1;
						break;

					case "alter_def" :
						$type_effet="Alt&eacute;ration de la d&eacute;fense : ";
						$ok = 1;
						break;

					case "alter_att" :
						$type_effet="Alt&eacute;ration de l'attaque : ";
						$ok = 1;
						break;

					case "alter_mouv" :
						$type_effet="Alt&eacute;ration de la capacit&eacute; de d&eacute;placement : ";
						$ok = 1;
						break;

					case "alter_niv_mag" :
						$type_effet="Alt&eacute;ration du niveau de magie : ";
						$ok = 1;
						break;

					case "dla" :
						$type_effet="Alt&eacute;ration de l'heure d'activation (en minutes) : ";
						$ok = 1;
						break;

					case "alter_effet" :
						$type_effet="Augmentation de la puissance globale : ";
						$ok = 1;
						break;
				}

				if($ok){
					if($inci==1 || !isset($liste_effet_cible)){
						$liste_effet_cible="";
					}
					($inci!=$_SESSION['effet']['cible']['nb'])?$liste_effet_cible=$liste_effet_cible.$type_effet.$_SESSION['effet']['cible']['val'][$inci]."<br/>":$liste_effet_cible=$liste_effet_cible.$type_effet.$_SESSION['effet']['cible']['val'][$inci];
					$ok = 0;
				}
			}
			if(isset($liste_effet_cible)){
				$info = $info."<p>Liste des effets mesurables sur les cibles : <br/> $liste_effet_cible</p>";
			}

			for($inci=1;$inci<=$_SESSION['effet']['lanceur']['nb'];$inci++){
				$ok = 0;
				switch($_SESSION['effet']['lanceur']['nom'][$inci]){
					case "pv" :
						$type_effet="Alt&eacute;ration des points de vie (d&eacute;gressif si sort de zone): ";
						$ok = 1;
						break;

					case "alter_pa" :
						$type_effet="Alt&eacute;ration de la capacit&eacute; d'action : ";
						$ok = 1;
						break;

					case "alter_recup_pv" :
						$type_effet="Alt&eacute;ration de la r&eacute;ration : ";
						$ok = 1;
						break;

					case "alter_force" :
						$type_effet="Alt&eacute;ration de la force : ";
						$ok = 1;
						break;

					case "alter_perception" :
						$type_effet="Alt&eacute;ration de la perception : ";
						$ok = 1;
						break;

					case "alter_def" :
						$type_effet="Alt&eacute;ration de la d&eacute;fense : ";
						$ok = 1;
						break;

					case "alter_att" :
						$type_effet="Alt&eacute;ration de l'attaque : ";
						$ok = 1;
						break;

					case "alter_mouv" :
						$type_effet="Alt&eacute;ration de la capacit&eacute; de d&eacute;placement : ";
						$ok = 1;
						break;

					case "alter_niv_mag" :
						$type_effet="Alt&eacute;ration du niveau de magie : ";
						$ok = 1;
						break;

					case "dla" :
						$type_effet="Alt&eacute;ration de l'heure d'activation (en minutes) : ";
						$ok = 1;
						break;
				}
				if($ok){
					if($inci==1 || !isset($liste_effet_lanceur)){
						$liste_effet_lanceur="";
					}
					($inci!=$_SESSION['effet']['lanceur']['nb'])?$liste_effet_lanceur=$liste_effet_lanceur.$type_effet.$_SESSION['effet']['lanceur']['val'][$inci]."<br/>":$liste_effet_lanceur=$liste_effet_lanceur.$type_effet.$_SESSION['effet']['lanceur']['val'][$inci];
					$ok = 0;
				}
			}

			if(isset($liste_effet_lanceur)){
				$info = $info."<p>Liste des effets mesurables sur le lanceur : <br/> $liste_effet_lanceur</p>";
			}
			$_SESSION['effet']['cible']['nb']=NULL;
			unset($_SESSION['effet']);
		}

		if($esquive){
			$info = $info."<br/>Votre $type_action a &eacute;t&eacute; esquiv&eacute; par : $liste_esquive</p><br/>";
		} else $info = $info."</p>";

	}

//Annonce des meurtres et destruction
if($_SESSION['mort']['nb']>0){
	$info = $info."Vous avez tu&eacute; ".$_SESSION['mort']['nb']." personne(s)<br/>";
	}

if($_SESSION['destruction']['nb']>0){
	  $info = $info."Vous avez d&eacute;truit ".$_SESSION['destruction']['nb']." objet(s)<br/>";
}

	// Gain d'xp
	if(isset($_SESSION['gain_xp']['att'])){
		$xp=$_SESSION['gain_xp']['att'];
		if($xp>=0){
			$gain='gagn&eacute;';
			}else $gain='perdu';
		$info = $info."Vous avez $gain $xp point(s) d'expérience";
	} else $info = $info."Vous n'avez gagn&eacute; aucun point d'expérience";
	$_SESSION['gain_xp']['att']=NULL;
if((isset($_SESSION['mort']['nb']) && $_SESSION['mort']['nb']>0)
		|| (isset($_SESSION['destruction']['nb']) && $_SESSION['destruction']['nb']>0)
		|| (isset($_SESSION['temp']['teleportation']) && $_SESSION['temp']['teleportation'])){
	$info=$info.":erreur";
	}
}

unset($_SESSION['esquive']);
unset($_SESSION['reparation']);
if(isset($_SESSION['score'])){
	unset($_SESSION['score']);
}

// $_SESSION['temp']['info_action']	= '';
// $_SESSION['action']['erreur']		= NULL;
// $_SESSION['esquive']				= NULL;
// $_SESSION['reussite'] 				= NULL;
// $_SESSION['action']['type']			= NULL;
// $_SESSION['score']					= NULL;

$info .= '<script>refresh_carac();</script>';

echo $info;
?>
