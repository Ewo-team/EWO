<?php

/*
 @TODO Deprecié
 */

require_once("../../ia/fonctions.php");
/**
      $initial_pos est un tableau d'indexes x,y,y
      la fonction renvoit un objet du type de $initial_pos ou null si pas de place
**/
function trouver_place_dispo($initial_pos,$radius,$damier="persos"){

  // phasee 1 : y'a til de la place ?

 // on cherche si on a radius² enregistrements de pris dans la table

  $min_x = $initial_pos['x']-$radius;
  $max_x = $initial_pos['x']+$radius;

  $min_y = $initial_pos['y']-$radius;
  $max_y = $initial_pos['y']+$radius;
  $map =   $initial_pos['z'];

  
  $sql= "SELECT COUNT(pos_x) AS cnt FROM damier_$damier WHERE carte_id = $map AND pos_x >= $min_x AND pos_x <= $max_x AND pos_x >= $min_y AND pos_y <= $max_y";
  $res=mysql_query($sql) or die(mysql_error()."[$sql]");
  $res = mysql_fetch_array ($res);
  $cnt=$res["cnt"];

  if($cnt>=$radius * $radius){
    // tout est pris.
    return null;
  }
  

  // phase 2 : on prend des positions au pif
  for(;;){	// on est surs de pas boucler a l'infini vu que y'a une place.
    $tx = rand($min_x,$max_x);
    $ty = rand($min_y,$max_y);
    // on teste si c'est disponible.
    $sql = "SELECT pos_x FROM damier_$damier WHERE carte_id = $map AND pos_x = $tx AND pos_y =$ty";
    $res = mysql_query($sql) or die(mysql_error()."[$sql]");
    if(mysql_num_rows($res)==0){
      // disponible
      $resu['x']=$tx;
      $resu['y']=$ty;
      $resu['z']=$map;
      return $resu;
    }else{
    }
  }

}

/**
    Fait poper le pnj a coté de son maitre, et définit sa position initiale comme "maison"
**/
function poper_pnj($pnjid,$creator){
      // phase 1 :récuperer la position du créateur

      $qry="SELECT D.pos_x AS x,D.pos_y AS y, D.carte_id AS z,C.perception AS p FROM damier_persos D, caracs C WHERE C.perso_id=$creator AND D.perso_id=$creator";
      $sql=mysql_query($qry) or die(mysql_error()."[$qry]");
      $res=mysql_fetch_array($sql);

      $tgt = trouver_place_dispo($res,$res['p']);

      if($tgt==null){
	  return false; // pas assez d'espace
      }
      $x=$tgt['x'];
      $y=$tgt['y'];
      $z=$tgt['z'];


    //phase 3: lui mettre des states au max

		$caracs_pures = calcul_caracs_no_alter($pnjid);

            $recup_pv			= $caracs_pures['recup_pv'];
            $niv_recup_pv		= $caracs_pures['niv_recup_pv'];
            $max_recup_pv		= carac_max ($race, $grade, 'recup_pv', $niv_recup_pv, $pnjid);
            $malus_def			= $caracs_pures['malus_def'];
            $niv_pv				= $caracs_pures['niv_pv'];
            $max_pv				= carac_max ($race, $grade, 'pv', $niv_pv, $pnjid);
            $niv_pa				= $caracs_pures['niv_pa'];
            $max_pa				= carac_max ($race, $grade, 'pa', $niv_pa, $pnjid);
            $niv_mouv			= $caracs_pures['niv_mouv'];
            $max_mouv			= carac_max ($race, $grade, 'mouv', $niv_mouv, $pnjid);
            $curent_pv			= $caracs['pv'];
            $px					= $caracs_pures['px'];
            $pi					= $caracs_pures['pi'];
            $pa_dec				= $caracs_pures['pa_dec'];
            $pa_dec_max			= carac_max ($race, $grade, 'pa_dec', $niv_pa, $pnjid);
            $niv_force			= $caracs_pures['niv_force'];
            $max_force			= carac_max ($race, $grade, 'force', $niv_force, $pnjid);
            $niv_perception		= $caracs_pures['niv_perception'];
            $max_perception		= carac_max ($race, $grade, 'perception', $niv_perception, $pnjid);
            $maj_des			= $caracs_pures['maj_des'];
     $sql="UPDATE caracs SET pv = '$max_pv', recup_pv = '$max_recup_pv', pa = '$max_pa', pa_dec='$pa_dec', malus_def = '$malus_def', mouv = '$max_mouv', `force` = '$max_force', perception = '$max_perception', maj_des=0  WHERE perso_id = '".$pnjid."'";
      mysql_query($sql) or die (mysql_error()."[$sql]");

      // phase 2: faire poper le pnj à coté de son maimaitre


//      echo("<h1>RESPAWN! ($pnjid) </h1>");
      respawn($pnjid,'autre');
//       echo("<h1>SETPOS</h1>");
      set_pos($pnjid,$x,$y,$z);
//       echo("<h1>FINLOL</h1>");


	$unit = new IAUnit($pnjid);
	$unit->sendAbsoluteOrder(6,$creator,20,0,$x,$y,$z,0,1);
	unset($unit);

      return true;
}

function create_pnj($perso_nom,$pi,$creator,$typeia,$typearch){

	//-- Recup du mail et pass de l'utilisateur pour l'injecter dans la bdd du forum PHPBB pour créer le compte du personnage.
	/*$sql="SELECT U.passwd_forum,U.email,U.id FROM utilisateurs U, persos P WHERE U.id =  P.utilisateur_id AND P.id = $creator LIMIT 1";
	$resultat = mysql_query ($sql) or die (mysql_error()."[$sql]");
	$user_compte = mysql_fetch_array ($resultat);*/


	$sql="SELECT P.race_id,R.nom FROM persos P, races R where P.id = $creator AND R.id=P.race_id";
	$resultat = mysql_query ($sql) or die (mysql_error()."[$sql]");
	$perso_race = mysql_fetch_array ($resultat);
      

	$utilisateur_mail = $user_compte['email'];
	$utilisateur_pass = $user_compte['passwd_forum'];
	$utilisateur_id= $user_compte['id'];

	//---------------------

	$perso_bg = "ou pas";

		// Vérifier que le nom ne soit pas en bdd
		$verif_nom_existe = mysql_query("SELECT nom FROM persos WHERE nom = '$perso_nom'") or die ("Erro checking name $perso_nom".mysql_error());
		if (mysql_fetch_row($verif_nom_existe))
		{
		      return false;
		}
		if (empty($perso_nom) || !ctype_alpha($perso_nom[0]))
		{
		      return false;
		}


		//-- ID
		$grade_id = 0;
		
		$sexe = 1;
		
		//-- AVATAR ET GROUPE FORUM
		if ($perso_race["nom"] == 'Ange'){
			$avatar = '../images/persos/ange/ang01.gif';
			$groupe_id_forum = 7;
		}elseif ($perso_race["nom"] == 'Demon'){
			$avatar = '../images/persos/demon/dem01.gif';
			$groupe_id_forum = 8;
		}elseif ($perso_race["nom"] == 'Humain'){
			$avatar = '../images/persos/humain/hum01.gif';
			$groupe_id_forum = 9;
		}
		$race_id=$perso_race["race_id"];
	
	$sql_perso = mysql_query("INSERT INTO persos(
												id, 
												background, 
												description_affil, 
												utilisateur_id, 
												nb_suicide,
												race_id,
												superieur_id,
												grade_id,
												faction_id,
												nom,
												creation_date,
												date_tour,
												avatar_url,
												icone_id,
												galon_id,
												options,
												mdj,
												signature,
												sexe)
										VALUES (
											'',
											'$perso_bg',
											'', 
											$utilisateur_id, 
											'', 
											$race_id,
											'', 
											$grade_id,
											'', 
											'$perso_nom', 
											CURRENT_TIMESTAMP(), 
											'', 
											'', 
											'', 
											'',
											'',
											'', 
											'',
											'".$sexe."')
							");
	//-- Recup de l'id du perso
	$id_perso = mysql_insert_id();
	//-- Alteration des caractéristiques de base
	$sql_carac_alter = mysql_query("INSERT INTO `caracs_alter` (`perso_id`, 
																`alter_pa`, 
																`alter_mouv`, 
																`alter_def`, 
																`alter_att`, 
																`alter_recup_pv`, 
																`alter_force`, 
																`alter_perception`, 
																`nb_desaffil`, 
																`alter_niv_mag`) 
														VALUES ('$id_perso', 
																'', 
																'', 
																'', 
																'', 
																'', 
																'', 
																'', 
																'', 
																'')
									");
	//-- Alteration des caractéristiques par la magie
	/*$sql_carac_alter_mag = mysql_query("INSERT INTO `caracs_alter_mag` (`perso_id`, 
																`alter_pa`, 
																`alter_mouv`, 
																`alter_def`, 
																`alter_att`, 
																`alter_recup_pv`, 
																`alter_force`, 
																`alter_perception`, 
																`alter_niv_mag`) 
														VALUES ('$id_perso', 
																'', 
																'', 
																'', 
																'', 
																'', 
																'', 
																'', 
																'')
									");*/
	//Alteration due au plan
	raz_alter_plan($id_perso);
	//-- Caracteristique de base des races
	include("../../conf/carac_perso_base.php");
	$sql_perso_carac = mysql_query("INSERT INTO `caracs` (	`perso_id`, 
															`px`, 
															`pi`, 
															`pv`, 
															`recup_pv`, 
															`malus_def`, 
															`niv`, 
															`cercle`, 
															`mouv`, 
															`pa`, 
															`pa_dec`, 
															`des_attaque`,
															`maj_des`,
															`force`, 
															`perception`) 
											VALUES ('$id_perso', 
													'$px', 
													'$pi', 
													'$pv',
													'$recup_pv', 
													'$malus_def', 
													'$niv', 
													'', 
													'$mouv',  
													'$pa', 
													'', 
													'$des_attaque',
													'',
													'$force', 
													'$perception')
									");
		
		$sql_perso_design = mysql_query("INSERT INTO `blocks` (`unique_id`, `perso_id`, `block_id`, `column_id`, `order_id`) VALUES
																		('', '$id_perso', 'block-1', 'column-1', 0),
																		('', '$id_perso', 'block-3', 'column-1', 1),
																		('', '$id_perso', 'block-2', 'column-1', 2),
																		('', '$id_perso', 'block-4', 'column-1', 3),
																		('', '$id_perso', 'block-5', 'column-2', 2),
																		('', '$id_perso', 'block-6', 'column-2', 1),
																		('', '$id_perso', 'block-7', 'column-2', 0)");
		
		if($sql_carac_alter == FALSE){
		echo 'sql_carac_alter';exit;
		}			
		if($sql_perso_carac == FALSE){
		echo 'sql_perso_carac';exit;
		}
		if($sql_perso == FALSE){
		echo 'sql_perso';exit;
		}
		if($sql_perso_design == FALSE){
		echo 'sql_perso';exit;
		}else{


			// maintenant, raccorder une IA au personnage

			$qr = 
					"INSERT INTO `ia_triggers` 
					( `matricule`,`comportement_id` , `evolution_arch_id`) 
					VALUES 
					( $id_perso ,$typeia ,$typearch)";
			mysql_query($qr			
					
				) or die ("toto [ $qr ] ".mysql_error());

			poper_pnj($id_perso,$creator);

		return true;

		// TODO LATER


			//-- Code phpBB pour la gestion du pass et du login
			define('IN_PHPBB', true);
			$phpEx = 'php';
			$phpbb_root_path = '../forum/';

			require('../forum/common.php');
			require('../forum/includes/functions_user.php');
			//--
			
			$utilisateur_pass = phpbb_hash($utilisateur_pass);
			
		  // set user data
		$user_row = array(
		   'username'       => $perso_nom,
		   'user_password'  => $utilisateur_pass,
		   'user_email'     => $utilisateur_mail,
		   'group_id'       => $groupe_id_forum,
		   'user_type'      => USER_NORMAL,
		);
	 
		
		

		}
	return true;
	}
?>
