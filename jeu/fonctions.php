<?php

//use \persos\event\eventManager as eventManager;

// On include la classe processingEvents


include_once(SERVER_ROOT.'/persos/eventManager/special.php');
include_once(SERVER_ROOT.'/persos/medailles.php');
include_once(SERVER_ROOT.'/lib/forum/ewo_forum.php');

require_once(SERVER_ROOT.'/conf/grille_gainsxp.php');

//Inlcude des fonction de controle des interactions
include_once(SERVER_ROOT.'/admin/antitriche/class/InterGeoLogger.php.inc');

// Fonction de recuperation des infos de plan
function recup_carte_info($plan) {
	$sql="SELECT * FROM cartes
		WHERE id=$plan";
	$resultat	= mysql_query ($sql) or die (mysql_error());
	$plan		= mysql_fetch_array ($resultat);
	return $plan;
}
//fonction de calcule de distance entre deux objets d'un même plan
function distance($objet1, $objet2, $plan) {
	$plan	= recup_carte_info($plan);
	$x_min	= $plan['x_min'];
	$x_max	= $plan['x_max'];
	$y_min	= $plan['y_min'];
	$y_max	= $plan['y_max'];
	$circ	= $plan['circ'];

	if ($circ[0]) {
		if (abs($objet1['pos_x']-$objet2['pos_x']) >= ($x_max-$x_min)/2) {
			$dist_x=$x_max-max($objet1['pos_x'],$objet2['pos_x']) + min($objet1['pos_x'],$objet2['pos_x']) - $x_min;
		} else $dist_x = abs($objet1['pos_x']-$objet2['pos_x']);
	}
	else $dist_x = abs($objet1['pos_x']-$objet2['pos_x']);

	if ($circ[1]) {
		if (abs($objet1['pos_y']-$objet2['pos_y']) >= ($y_max-$y_min)/2) {
			$dist_y=$y_max-max($objet1['pos_y'],$objet2['pos_y']) + min($objet1['pos_y'],$objet2['pos_y']) - $y_min;
		} else $dist_y = abs($objet1['pos_y']-$objet2['pos_y']);
	}
	else $dist_y = abs($objet1['pos_y']-$objet2['pos_y']);

	return max($dist_x, $dist_y);
}


/* Fonction qui été utilisée pour les sorts de zone
 * Désactivée jusqu'à explication du dev qui a mis ça dedant !
 */
function moyenneStable(Array $valeurs, $ecart_max = 1) {
	$table_ecart = array();
	$table_finale = array();
	$variance;
	$ecart_type;
	$moyenne = array_sum($valeurs) / count($valeurs);

	foreach($valeurs as $val) {
		$table_ecart[] = pow($val - $moyenne, 2);
	}

	$variance = array_sum($table_ecart) / count($table_ecart);

	$ecart_type = sqrt($variance);

	$max = $moyenne+($ecart_type*$ecart_max);
	$min = $moyenne-($ecart_type*$ecart_max);

	foreach ($valeurs as $k => $val) {

		if ($val < $max && $val > $min) {
			$table_finale[] = $val;
		}
	}

	return round(array_sum($table_finale) / count($table_finale));
}

//Lancé de dé
function lance_ndp($nb_d, $val_d) {
	$resultat=0;
	while ($nb_d>0) {
		$resultat+=rand(1,$val_d);
		$nb_d--;
	}
	return $resultat;
}

/* Efficiently generating two random dice rolls with $def and $att three-faced dices.
* Return an array of two integers numbers : the attack and defense result, in this order.
*/
function ewo_dice($att,$def) {
	$max = mt_getrandmax()+1.0;

	$u = sqrt(-2*log(mt_rand()/$max));
	$v = 2*M_PI*mt_rand()/$max;

	$a = round(sqrt(2.0*$att/3.0)*$u*cos($v)+2.0*$att);
	$b = round(sqrt(2.0*$def/3.0)*$u*sin($v)+2.0*$def);

	$x = min(max($att,$a),3*$att);
	$y = min(max($def,$b),3*$def);

	$dices = array($x,$y);

	return $dices;
}

//Calcul de la réussite d'un sort
//retourne 1 si reussi
function reussite_sort($grade, $niveau, $up_percept, &$chance=null) {
	$chance = min(999,ceil((900 * ( max(0.5,$grade) / max(0.5,$niveau) )) + $up_percept * 30));

	$jet = lance_ndp(1, 1000);

	return $chance > $jet;
}

//Calcul de l'esquive d'un sort
// retourne 1 si esquivé
function esquive_sort($perso_xp, $perso_grade, $cible_xp, $cible_grade, $esquive_auto=0, $correcteur=0) {
	if (!$esquive_auto) {
		$perso_rang = calcul_rang($perso_xp);
		$cible_rang = calcul_rang($cible_xp);

		$perso_rang += ajuste_rang($perso_grade);
		$cible_rang += ajuste_rang($cible_grade);

		// %tage d'esquive du sort par la victime.

		$seuil = ((($cible_rang+$cible_grade*1.1)-($perso_rang+$perso_grade*1.1)) + 5)*10 + $correcteur;

		$score = lance_ndp(1, 100);

		return $seuil>= $score;
	} else return false;
}


// Fonction de calcul et mise à jour d'xp
// calcul_xp(cibleur, ciblé, type, réussite ou non)
function calcul_xp($perso_id, $cible_id, $type, $reussite, $esquive, $cout=0) {

	$gain_att 	= 0;
	$gain_def 	= 0;
	$base_xp	= 7;
	$coeff = ($esquive==2)?2:1;
	$bonus_base_xp = 0;

	// Attaquant
	$inc=$_SESSION['persos']['id'][0];

	$perso_grade = $_SESSION['persos']['grade'][$inc];
	$perso_race = $_SESSION['persos']['race'][$inc];
	$perso_type = recup_type($perso_race);
	$perso_camp = recup_camp($perso_race);
	$camp_plan = recup_camp_plan($perso_camp);
	$perso_carac = recup_carac($perso_id, array('px', 'pi'));
	$perso_rang = calcul_rang($perso_carac['px']);
	$perso_rang += ajuste_rang($perso_grade);
	$plan = $_SESSION['persos']['carte'][$inc];
	$perso_faction = $_SESSION['persos']['faction']['id'][$inc];
	$nb_suicide = $_SESSION['persos']['nb_suicide'][$inc];

	$caracs_max	= caracs_base_max($perso_id, $perso_race, $perso_grade);
	$nbpa = $caracs_max['pa'] + ($caracs_max['pa_dec'] / 10);

	$famille=false;
	if (in_array($cible_id, $_SESSION['persos']['id'])) {
		$famille=true;
	}

	$def=false;
	if ($perso_faction>0) {
		//Recup d'info sur la faction
		$sql        ="SELECT type FROM factions WHERE id='$perso_faction'";
		$resultat    = mysql_query($sql)or die (mysql_error());
		$perso_faction_info    = mysql_fetch_array ($resultat);
		$def= ($perso_faction_info['type']==2);
		$just= ($perso_faction_info['type']==1);
	}
	else {
		$def = false;
		$just = false;
	}

	// Gain si c'est un echec
	$noneApply = array('repare_bouclier_1','repare_bouclier_2','repare_bouclier_3','repare_bouclier_4','repare_porte','repare_porte_mauve');
	if (!$reussite && !in_array($type,$noneApply)) {
		$gain=gainxp($nbpa,'sort_rate') * max($cout,1);

		$perso_carac['px']+=$gain;
		$perso_carac['pi']+=$gain;

		maj_carac($perso_id, 'px', $perso_carac['px']);
		maj_carac($perso_id, 'pi', $perso_carac['pi']);

		$_SESSION['gain_xp']['att']	= $gain;
	}

	// Recupération d'infos basiques sur la race de l'attaquant
	$sql        ="SELECT camp_id FROM races WHERE race_id='$perso_race' AND grade_id='0'";
	$resultat    = mysql_query($sql)or die (mysql_error());
	$perso_race_info    = mysql_fetch_array ($resultat);

	$cible_type = 'none';
	// Cible
	if ($cible_id!=NULL && ($type=='sort' || $type=='attaque' || $type=='entrainement' || $type=='mort_att' || $type=='mort_sort' || $type=='reussite_sort')) {
		$cible_type = 'perso';
		$cible_carac = recup_carac($cible_id, array('px', 'pi'));

		// Recupération d'infos basiques sur la cible
		$sql        ="SELECT * FROM persos WHERE id='$cible_id'";
		$resultat    = mysql_query($sql)or die (mysql_error());
		$cible_info    = mysql_fetch_array ($resultat);

		$cible_grade = $cible_info['grade_id'];
		$cible_race	 = $cible_info['race_id'];

		$cible_type = recup_type($cible_race);
		$cible_camp = recup_camp($cible_race);

		$cible_rang = calcul_rang($cible_carac['px']);
		$cible_rang += ajuste_rang($cible_grade);
	}

	switch($type) {
		//Calcul d'xp en cas d'attaque au cac
		case 'attaque' :
			if (!$esquive || $esquive==2) {
				//Calcul du gain du perso
				if (!$famille) {
					//Attaque sur un ailé de son propre camp dans son propre plan
					if ($camp_plan!=1 && $plan == $camp_plan && $perso_camp==$cible_camp) {
						$diff_rang = ( $perso_rang - $cible_rang );

						// Si c'est une faction de justice ou de défense,
						if ($def || $just) {
							$diff_rang = min(0, $diff_rang);
						}

						$gain_att = gainxp($nbpa,'attaque_plan_allie', $diff_rang);

						$perso_carac['px']+=$gain_att;
						maj_carac($perso_id, 'px', $perso_carac['px']);
						$perso_carac['pi']+=$gain_att;
						maj_carac($perso_id, 'pi', $perso_carac['pi']);
						$_SESSION['gain_xp']['att']+=$gain_att;

					} else { /// Dans tous les autres cas
						$diff_rang = ( $perso_rang - $cible_rang );

						// Adversaire frappé dans son camp
						if ($camp_plan!=1 && $plan == $camp_plan) {
							$diff_rang = min(0, $diff_rang);
						}

						$gain_att = gainxp($nbpa,'attaque', $diff_rang);

						$perso_carac['px']+=$gain_att;
						maj_carac($perso_id, 'px', $perso_carac['px']);
						$perso_carac['pi']+=$gain_att;
						maj_carac($perso_id, 'pi', $perso_carac['pi']);
						$_SESSION['gain_xp']['att']+=$gain_att;
					}
				} else {
					$gain_att = gainxp($nbpa,'famille');

					$perso_carac['px']+=$gain_att;
					maj_carac($perso_id, 'px', $perso_carac['px']);
					$perso_carac['pi']+=$gain_att;
					maj_carac($perso_id, 'pi', $perso_carac['pi']);
					$_SESSION['gain_xp']['att']+=$gain_att;
				}

				if ($esquive==2) {

					$gain_def = gainxp($nbpa,'esquiver_cible', ($perso_rang - $cible_rang));

					$cible_carac['px']+=$gain_def;
					maj_carac($cible_id, 'px', $cible_carac['px']);
					$cible_carac['pi']+=$gain_def;
					maj_carac($cible_id, 'pi', $cible_carac['pi']);
					$_SESSION['gain_xp']['def'][$cible_id]+=$gain_def;
					//$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>Gain esquive : ".$gain;
				}

				$gain_def = gainxp($nbpa,'attaque_recu', $perso_rang - $cible_rang);

				$cible_carac['px']+=$gain_def;
				maj_carac($cible_id, 'px', $cible_carac['px']);
				$cible_carac['pi']+=$gain_def;
				maj_carac($cible_id, 'pi', $cible_carac['pi']);
				$_SESSION['gain_xp']['def'][$cible_id]+=$gain_def;
			} else {
			
				$gain_att = gainxp($nbpa,'attaque_esquive', ($perso_rang - $cible_rang));

				$perso_carac['px']+=$gain_att;
				maj_carac($perso_id, 'px', $perso_carac['px']);
				$perso_carac['pi']+=$gain_att;
				maj_carac($perso_id, 'pi', $perso_carac['pi']);
				$_SESSION['gain_xp']['att']+=$gain_att;

				$gain_def = gainxp($nbpa,'esquiver_cible', ($perso_rang - $cible_rang));

				$cible_carac['px']+=$gain_def;
				maj_carac($cible_id, 'px', $cible_carac['px']);
				$cible_carac['pi']+=$gain_def;
				maj_carac($cible_id, 'pi', $cible_carac['pi']);
				$_SESSION['gain_xp']['def'][$cible_id]+=$gain_def;
			}
			break;

			//Calcul d'xp en cas de mort par une attaque au cac
		case 'mort_att' :
			if ($reussite && (!$esquive || $esquive==2)) {
			
				if ($cible_grade == -1) {
					$gain_att = gainxp($nbpa,'tueur_cafard');
				} elseif ($famille) {
					$gain_att = gainxp($nbpa,'famille');
				} elseif ($cible_type!=4) {
					$gain_att = gainxp($nbpa,'tueur_tue_t3', ($cible_rang - $perso_rang));
				} elseif ($cible_type==4) {
					$gain_att = gainxp($nbpa,'tueur_tue_t4', ($cible_rang - $perso_rang));
				} else {
					// Normalement ne passe jamais ici
					$gain_att = gainxp($nbpa,'tueur_tue_t3', ($cible_rang - $perso_rang));
				}


				$perso_carac['px']+=$gain_att;
				maj_carac($perso_id, 'px', $perso_carac['px']);
				$perso_carac['pi']+=$gain_att;
				maj_carac($perso_id, 'pi', $perso_carac['pi']);
				$_SESSION['gain_xp']['att']+=$gain_att;

	
				$gain_def = gainxp($nbpa,'tue', ($cible_rang - $perso_rang));
				

				$cible_carac['px']+=$gain_def;
				maj_carac($cible_id, 'px', $cible_carac['px']);
				$cible_carac['pi']+=$gain_def;
				maj_carac($cible_id, 'pi', $cible_carac['pi']);
				$_SESSION['gain_xp']['def'][$cible_id]+=$gain_def;
			}
			break;

			//Calcul d'xp en cas de sort
		case 'sort' : // Non utilisé pour les sorts de zone pour le gain du lanceur ni pour les objets
			if ($reussite) {
				if (!$_SESSION['zone']) {


					// Attaque d'un non terrien dans son plan sur les membres de son camp hors membre de faction de def
					if ($camp_plan!=1 && $plan == $camp_plan && $perso_camp==$cible_camp && !$def && !$famille) {

						$gain_att = gainxp($nbpa,'magie_plan_allie');

						$perso_carac['px']+=$gain_att;
						maj_carac($perso_id, 'px', $perso_carac['px']);
						$perso_carac['pi']+=$gain_att;
						maj_carac($perso_id, 'pi', $perso_carac['pi']);
						$_SESSION['gain_xp']['att']+=$gain_att;


					} else {
						// Dans tous les autres cas

						if ($famille) {

							$gain_att = gainxp($nbpa,'famille');
							
							$perso_carac['px']+=$gain_att;
							maj_carac($perso_id, 'px', $perso_carac['px']);
							$perso_carac['pi']+=$gain_att;
							maj_carac($perso_id, 'pi', $perso_carac['pi']);
							$_SESSION['gain_xp']['att']+=$gain_att;
						} else {

							$etiquette = 'sort_unique';

							if ($esquive) {
								$etiquette.='_esquive';
							}

							/*if ($cible_type!=4) {
								$etiquette.='t3';
							} else {
								$etiquette.='t4';
							}*/

							$gain_att = gainxp($nbpa, $etiquette, ($perso_rang - $cible_rang)) * $cout;


							$perso_carac['px']+=$gain_att;
							maj_carac($perso_id, 'px', $perso_carac['px']);
							$perso_carac['pi']+=$gain_att;
							maj_carac($perso_id, 'pi', $perso_carac['pi']);
							$_SESSION['gain_xp']['att']+=$gain_att;
						}
					}
				}

				if ($perso_id!=$cible_id) {
					//calcul du gain potentiel de la cible
					if (!$esquive) {


						$gain_def = gainxp($nbpa, 'attaque_recu', ($perso_rang - $cible_rang)) * $cout;


					} else {
						if ($_SESSION['zone']) {
							$gain_def = gainxp($nbpa, 'esquiver_zone', ($perso_rang - $cible_rang)) * $cout;
						} else {
							$gain_def = gainxp($nbpa, 'esquiver_cible', ($perso_rang - $cible_rang)) * $cout;
						}
					}


					$cible_carac['px']+=$gain_def;
					maj_carac($cible_id, 'px', $cible_carac['px']);
					$cible_carac['pi']+=$gain_def;
					maj_carac($cible_id, 'pi', $cible_carac['pi']);
					$_SESSION['gain_xp']['def'][$cible_id]+=$gain_def;
				}
				//$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>Gain sort cible : ".$gain;
			}
			break;

			//Calcul d'xp en cas de mort par magie
		case 'mort_sort' :
			$gain_def = 0;
			if ($reussite) {

				$gain_def = gainxp($nbpa,'tue', ($cible_rang - $perso_rang));

				$cible_carac['px']+=$gain_def;
				maj_carac($cible_id, 'px', $cible_carac['px']);
				$cible_carac['pi']+=$gain_def;
				maj_carac($cible_id, 'pi', $cible_carac['pi']);
				$_SESSION['gain_xp']['def'][$cible_id]+=$gain_def;

				//$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>Gain cible : ".$gain;
			}
			break;

		case 'reussite_sort' : //utilisé uniquement pour les sorts de zone
			//Calcul du gain du perso
			if ($_SESSION['zone']) {

				if ($camp_plan!=1 && $plan == $camp_plan && !$def) {
					$gain_att = gainxp($nbpa,'magie_plan_allie');

					$perso_carac['px']+=$gain_att;
					maj_carac($perso_id, 'px', $perso_carac['px']);
					$perso_carac['pi']+=$gain_att;
					maj_carac($perso_id, 'pi', $perso_carac['pi']);
					$_SESSION['gain_xp']['att']+=$gain_att;
					//$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>Gain sort : ".$gain;
				} else {
					if ($_SESSION['esquive']['nb']) {

						//Kamule le 14/01 : je ne sais pas trop pourquoi il y avait une moyenne stable, mais ça tue les sorts sur les gros
						// des gros donc je met une moyenne simple
						// Ganesh le 08/03/2013 : je la remet, na! et je prend la moyenne la plus élevé des deux, on est généreux chez EWO
						$rang_moy_stable = moyenneStable($_SESSION['esquive']['table_rang']);
						$rang_moy_normale = round($_SESSION['esquive']['somme_rang']/$_SESSION['esquive']['nb']);
						$rang_moy = max($rang_moy_stable, $rang_moy_normale);
					} else {
						$rang_moy = 0;
					}

					$gain_att = gainxp($nbpa,'sort_zone', $rang_moy - $perso_rang)*$cout;


					$perso_carac['px']+=$gain_att;
					maj_carac($perso_id, 'px', $perso_carac['px']);
					$perso_carac['pi']+=$gain_att;
					maj_carac($perso_id, 'pi', $perso_carac['pi']);
					$_SESSION['gain_xp']['att']+=$gain_att;
					//$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>Gain sort : ".$gain;

				}
			}
			break;

		case 'entrainement':
			//Calcul du gain du perso
			if ($perso_id != $cible_id) {

				if ($cible_info['utilisateur_id'] == $_SESSION['utilisateur']['id']) {

					// Entrainement inter-compte
					if ($cible_type == 4 && $perso_type == 4) {
						$gain_att = gainxp($nbpa,'famille');
					} else {
						// Il y a triche!
						$gain_att = gainxp($nbpa,'triche');
					}

				} else {
					$gain_att = gainxp($nbpa,'entrainement_attaquant');
				}

				$perso_carac['px']+=$gain_att;
				maj_carac($perso_id, 'px', $perso_carac['px']);
				$perso_carac['pi']+=$gain_att;
				maj_carac($perso_id, 'pi', $perso_carac['pi']);
				$_SESSION['gain_xp']['att']+=$gain_att;
				//$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>Gain entrainement : ".$gain;

				$gain_def= gainxp($nbpa,'entrainement_defenseur');

				$cible_carac['px']+=$gain_def;
				maj_carac($cible_id, 'px', $cible_carac['px']);
				$cible_carac['pi']+=$gain_def;
				maj_carac($cible_id, 'pi', $cible_carac['pi']);
				$_SESSION['gain_xp']['def'][$cible_id]+=$gain_def;
				//$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>Gain entrainement allier : ".$gain;

			}
			else {
				$gain_att = gainxp($nbpa,'entrainement_solo');

				$perso_carac['px']+=$gain_att;
				maj_carac($perso_id, 'px', $perso_carac['px']);
				$perso_carac['pi']+=$gain_att;
				maj_carac($perso_id, 'pi', $perso_carac['pi']);
				$_SESSION['gain_xp']['att']+=$gain_att;
				//$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>Gain entrainement seul : ".$gain;
			}
			break;

		case 'suicide':
			if ($reussite) {
				$_SESSION['reussite'] = 1;
				if ($perso_id==$cible_id) {
					$gain_att = gainxp($nbpa,'suicide', $nb_suicide);
					$perso_carac['px']+=$gain_att;
					maj_carac($perso_id, 'px', $perso_carac['px']);
					$perso_carac['pi']+=$gain_att;
					maj_carac($perso_id, 'pi', $perso_carac['pi']);
					$_SESSION['gain_xp']['att']+=$gain_att;
					$_SESSION['persos']['nb_suicide'][$inc]++;
					//$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>Perte suicide : ".$gain;
				} else {
					$sql = "SELECT nb_suicide AS nb FROM persos WHERE id=$cible_id";
					$res = mysql_query($sql)or die (mysql_error());
					$nb  = mysql_fetch_array ($res);
					$nb_suicide = $nb['nb'];
					$gain_def = gainxp($nbpa,'suicide', $nb_suicide);
					$cible_carac['px']+=$gain_def;
					maj_carac($cible_id, 'px', $perso_carac['px']);
					$cible_carac['pi']+=$gain_def;
					maj_carac($cible_id, 'pi', $cible_carac['pi']);
					$_SESSION['gain_xp']['def'][$cible_id]+=$gain_def;
				}
			} else {
				$_SESSION['reussite'] = 0;
			}
			break;

		case 'destruction_porte':
			if ($reussite) {
				$gain_att = gainxp($nbpa,'destruction_porte_aile');

				$perso_carac['px']+=$gain_att;
				maj_carac($perso_id, 'px', $perso_carac['px']);
				$perso_carac['pi']+=$gain_att;
				maj_carac($perso_id, 'pi', $perso_carac['pi']);
				$_SESSION['gain_xp']['att']+=$gain_att;
				//$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>Gain objet : ".$gain;
			}
			break;

		case 'destruction_porte_mauve':
			if ($reussite) {
				$gain_att = gainxp($nbpa,'destruction_porte_mauve');

				$perso_carac['px']+=$gain_att;
				maj_carac($perso_id, 'px', $perso_carac['px']);
				$perso_carac['pi']+=$gain_att;
				maj_carac($perso_id, 'pi', $perso_carac['pi']);
				$_SESSION['gain_xp']['att']+=$gain_att;
				//$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>Gain objet : ".$gain;
			}
			break;

		case 'repare_bouclier_1':
		case 'repare_bouclier_2':
		case 'repare_bouclier_3':
		case 'repare_bouclier_4':
		case 'repare_porte':
		case 'repare_porte_mauve':

			if (!$_SESSION['zone']) {

				if (isset($_SESSION['reparation']['dif']) && $_SESSION['reparation']['dif'] > 0)
					$gain_att = gainxp($nbpa,'repare_batiment',$reussite);
				else
					$gain_att = $gain['repare_batiment']['fullpv'];
				//	$_SESSION['gain_xp']['reparation']=1;
				$perso_carac['px'] += $gain_att;
				maj_carac($perso_id, 'px', $perso_carac['px']);
				$perso_carac['pi']+=$gain_att;
				maj_carac($perso_id, 'pi', $perso_carac['pi']);
				$_SESSION['gain_xp']['att'] = $gain_att;
			}
			break;


		case 'attaque_objet_complexe':
		case 'attaque_objet_simple':
			if ($reussite && !$_SESSION['zone']) {
				$gain_att = gainxp($nbpa,'frappe_objet');

				$perso_carac['px']+=$gain_att;
				maj_carac($perso_id, 'px', $perso_carac['px']);
				$perso_carac['pi']+=$gain_att;
				maj_carac($perso_id, 'pi', $perso_carac['pi']);
				$_SESSION['gain_xp']['att']+=$gain_att;
				//$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>Gain objet : ".$gain;
			}
			break;

		case 'destruction_objet_complexe':
		case 'destruction_objet_simple':
			$gain_att = gainxp($nbpa,'destruction_objet');

			$perso_carac['px']+=$gain_att;
			maj_carac($perso_id, 'px', $perso_carac['px']);
			$perso_carac['pi']+=$gain_att;
			maj_carac($perso_id, 'pi', $perso_carac['pi']);
			$_SESSION['gain_xp']['att']+=$gain_att;
			break;

		case 'attaque_porte':
		case 'attaque_porte_mauve':
			if ($reussite && !$_SESSION['zone']) {
				$gain_att = gainxp($nbpa,'frappe_porte');

				$perso_carac['px']+=$gain_att;
				maj_carac($perso_id, 'px', $perso_carac['px']);
				$perso_carac['pi']+=$gain_att;
				maj_carac($perso_id, 'pi', $perso_carac['pi']);
				$_SESSION['gain_xp']['att']+=$gain_att;
				//$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>Gain objet : ".$gain;
			}
			break;

		case 'attaque_bouclier_1':
		case 'attaque_bouclier_2':
		case 'attaque_bouclier_3':
		case 'attaque_bouclier_4':
			if ($reussite && !$_SESSION['zone']) {
				$gain_att = gainxp($nbpa,'frappe_bouclier');

				$perso_carac['px']+=$gain_att;
				maj_carac($perso_id, 'px', $perso_carac['px']);
				$perso_carac['pi']+=$gain_att;
				maj_carac($perso_id, 'pi', $perso_carac['pi']);
				$_SESSION['gain_xp']['att']+=$gain_att;
				//$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>Gain objet : ".$gain;
			}
			break;

		case 'destruction_bouclier_1':
			if ($reussite) {
				$gain_att = gainxp($nbpa,'destruction_t1');

				$perso_carac['px']+=$gain_att;
				maj_carac($perso_id, 'px', $perso_carac['px']);
				$perso_carac['pi']+=$gain_att;
				maj_carac($perso_id, 'pi', $perso_carac['pi']);
				$_SESSION['gain_xp']['att']+=$gain_att;
				//$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>Gain objet : ".$gain;
			}
			break;

		case 'destruction_bouclier_2':
			if ($reussite) {
				$gain_att = gainxp($nbpa,'destruction_t2');

				$perso_carac['px']+=$gain_att;
				maj_carac($perso_id, 'px', $perso_carac['px']);
				$perso_carac['pi']+=$gain_att;
				maj_carac($perso_id, 'pi', $perso_carac['pi']);
				$_SESSION['gain_xp']['att']+=$gain_att;
				//$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>Gain objet : ".$gain;
			}
			break;

		case 'destruction_bouclier_3':
			if ($reussite) {
				$gain_att = gainxp($nbpa,'destruction_t3');

				$perso_carac['px']+=$gain_att;
				maj_carac($perso_id, 'px', $perso_carac['px']);
				$perso_carac['pi']+=$gain_att;
				maj_carac($perso_id, 'pi', $perso_carac['pi']);
				$_SESSION['gain_xp']['att']+=$gain_att;
				//$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>Gain objet : ".$gain;
			}
			break;

		case 'destruction_bouclier_4':
			if ($reussite) {
				$gain_att = gainxp($nbpa,'destruction_t4');

				$perso_carac['px']+=$gain_att;
				maj_carac($perso_id, 'px', $perso_carac['px']);
				$perso_carac['pi']+=$gain_att;
				maj_carac($perso_id, 'pi', $perso_carac['pi']);
				$_SESSION['gain_xp']['att']+=$gain_att;
				//$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>Gain objet : ".$gain;
			}
			break;

		default :
			break;
	}


	//Calcul du gain d'xp pour le mage ayant lancé un sort de zone
	if ($_SESSION['mort']['valid'] && $_SESSION['mort']['nb']>0) {
		//Calcul du gain du perso
		
		$rang_moy_stable = moyenneStable($_SESSION['mort']['table_rang']);
		$rang_moy_normale = round($_SESSION['mort']['somme_rang']/$_SESSION['mort']['nb']);
		$rang_moy = max($rang_moy_stable, $rang_moy_normale);
		
		$gain_att = gainxp($nbpa,'tueur_sort', $rang_moy);
		$perso_carac['px']+=$gain_att;
		maj_carac($perso_id, 'px', $perso_carac['px']);
		$perso_carac['pi']+=$gain_att;
		maj_carac($perso_id, 'pi', $perso_carac['pi']);
		$_SESSION['gain_xp']['att']+=$gain_att;
		$_SESSION['mort']['valid']=false;
		//$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."<br/>Gain mort multiple : ".$gain;
	}

	//MAJ grade du perso si nécéssaire :
	if ($perso_grade < 2) {
		$perso_carac = recup_carac($perso_id, array('px'));

		grade_up_xp($perso_id,$perso_race,$perso_grade,$perso_carac['px']);
	}

	//MAJ grade défenseur si nécessaire :
	if ($cible_type=='perso' && $cible_grade < 2) {
		$cible_carac = recup_carac($cible_id, array('px'));
		grade_up_xp($cible_id,$cible_race,$cible_grade,$cible_carac['px']);
	}
}


//Fonction permettant de mettre un objet dans l'inventaire
function add_inventaire($perso_id, $artefact_id, $artefact_pv) {
	$resultats = mysql_query ("SELECT consom, poid FROM case_artefact WHERE id = ".$artefact_id."");
	$inventaire = mysql_fetch_array ($resultats);

	$poid_courant = poid_courant($perso_id);
	$poid_new = $poid_courant + $inventaire['poid'];
	$poid_portable = poid_portable($perso_id);

	if ($poid_new > $poid_portable) {
		return false;
	} else {
		$sql_query = mysql_query("INSERT INTO inventaire (id, perso_id, case_artefact_id, statut, pv, consom) VALUES ('' ,'$perso_id', '$artefact_id', 'inactif', '$artefact_pv', '".$inventaire['consom']."')") or die (mysql_error());
		return true;
	}
}


// Fonction de désincarnation
// Si le second paramettre n'est pas donné, il s'agit d'un personnage,
// Sinon il peut s'agir d'un objet_simple/artefact etc ...
function desincarne($cible_id, $type='') {
	$type_id='id';

	if ($type=='') {
		$type='persos';
		$type_id='perso_id';
	}

	$sql="DELETE FROM `ewo`.`damier_$type` WHERE `damier_$type`.`$type_id` = $cible_id";

	$resultat = mysql_query ($sql) or die (mysql_error());

}


//meurtre(tueur, tué), désincarne, calcule et maj la perte d'xp du tué, et le gain du tueur
//met à 0 la quantité de pv.
function meurtre($perso_id, $cible_id, $type, $type_esquive=0) {

	//Calcul d'xp
	calcul_xp($perso_id, $cible_id, $type, 1, $type_esquive);

	//MAJ pv
	maj_carac($cible_id, "pv", 0);

	//Maj du grade
	//Rchch grades respectifs
	$race_grade = recup_race_grade($perso_id);
	$perso_grade= $race_grade['grade_id'];
	$perso_race = $race_grade['race_id'];
	$perso_type = $race_grade['type'];
	$perso_galon = $race_grade['galon_id'];


	$race_grade = recup_race_grade($cible_id);
	$cible_grade= $race_grade['grade_id'];
	$cible_race = $race_grade['race_id'];
	$cible_type = $race_grade['type'];
	$cible_galon = $race_grade['galon_id'];

	//récupération des rangs
	$perso_carac = recup_carac($perso_id, array('px', 'pi'));
	$perso_rang = calcul_rang($perso_carac['px']);
	$perso_rang += ajuste_rang($perso_grade);

	$cible_carac = recup_carac($cible_id, array('px', 'pi'));
	$cible_rang = calcul_rang($cible_carac['px']);
	$cible_rang += ajuste_rang($cible_grade);

	//Calcul de l'alterateur de profondeur de spawn
	$alter_spawn = 0 ;
	if ($cible_race==3 || $cible_race == 4) {
		$plan=($cible_race==3)?1:-1;

		if ($cible_rang < $perso_rang) {
			$alter_spawn = round((($perso_rang+1.5*$perso_grade)-($cible_rang+1.5*$cible_grade))*3)*$plan;
		} elseif ($cible_rang > $perso_rang) {
			$alter_spawn = round((($cible_rang+1.5*$cible_grade)-($perso_rang+1.5*$perso_grade))*7)*$plan;
		} else $alter_spawn = 0 ;
	}
	maj_alter_spawn($cible_id, $alter_spawn);

	//Obtention d'essence ailée par les humains

	if ($perso_race==1 && ($cible_race==3 || $cible_race == 4)) {

		if ($cible_grade<5) {
			$dif_rang = max(- $perso_rang + $cible_rang, 0);
		} else $dif_rang = max(- $perso_rang + $cible_rang + 2, 0);;

		switch($cible_grade) {
			case 4 :
				$gain_essence = max($dif_rang*3, 1);
				add_inventaire($perso_id, 1, $gain_essence);
				break;

			case 5 :
				if ($cible_race==3) {
					add_inventaire($perso_id, 2, 1);
				}
				else add_inventaire($perso_id, 3, 1);
				break;

			default :
				$gain_essence = max($dif_rang*2, 0);
				add_inventaire($perso_id, 1, $gain_essence);
		}


	}
	grade_kill($perso_id, $cible_id, $perso_race, $cible_race, $perso_type, $cible_type, $perso_grade, $cible_grade, $perso_galon, $cible_galon);

	//Désincarnation
	desincarne($cible_id);
}


//Fonction détruisant un objet
function destruction($perso_id, $cible) {
	if ($cible[1]=='bouclier_1' || $cible[1]=='bouclier_2' || $cible[1]=='bouclier_3' || $cible[1]=='bouclier_4') {
		$type='bouclier';
	}
	elseif ($cible[1]=='porte_mauve') {
		$type='porte';
	}
	else $type=$cible[1];


	
	if ($type=="porte") {
		//Recupération de la position de la porte
		$sql		="SELECT pos_x, pos_y, carte_id, porte_liee_id, objet_lie FROM damier_porte WHERE id=".$cible[0];
		$resultat 	= mysql_query ($sql) or die (mysql_error());
		$pos		= mysql_fetch_array($resultat);
		desincarne($pos['objet_lie'], "objet_complexe");
		if ($pos) {
			$plan 		= $pos['carte_id'];
			$centre 	= array('pos_x'=>$pos['pos_x'],'pos_y'=>$pos['pos_y']);
			//Récupération de la proportion de portes restantes dans le plan
			$sql="SELECT COUNT(id) AS nb FROM damier_porte WHERE carte_id=$plan";
			$resultat 	= mysql_query ($sql) or die (mysql_error());
			$nb			= mysql_fetch_array($resultat);
			$nb_porte 	= $nb['nb'];
			switch($plan) {
				case 1 :
					if ($nb_porte>= 5) {
						$degat_prct=20;
					}
					elseif ($nb_porte>= 3) {
						$degat_prct=50;
					} else $degat_prct=80;
					break;
				case 2 :
					if ($nb_porte>= 3) {
						$degat_prct=20;
					}
					elseif ($nb_porte>= 2) {
						$degat_prct=50;
					} else $degat_prct=80;
					break;
				case 3 :
					if ($nb_porte>= 3) {
						$degat_prct=20;
					}
					elseif ($nb_porte>= 2) {
						$degat_prct=50;
					} else $degat_prct=80;
					break;
				default :
					$degat_prct=0;
			}

			//Application des effets en fonction de la distance à la porte et de leur nombre
			$sql	= "SELECT perso_id AS perso_id, pos_x AS pos_x, pos_y AS pos_y FROM damier_persos WHERE carte_id=$plan";
			$resultat 	= mysql_query ($sql) or die (mysql_error());
			while ($victime=mysql_fetch_array($resultat)) {
				$victime_id = $victime['perso_id'];

				$victime_caracs	= calcul_caracs($victime_id);
				$sql        	="SELECT * FROM persos WHERE id='$victime_id'";
				$res    	= mysql_query($sql)or die (mysql_error());
				$victime_info   = mysql_fetch_array ($res);
				$victime_grade 	= $victime_info['grade_id'];
				$victime_race 	= $victime_info['race_id'];

				$victime_caracs_max = caracs_base_max ($victime_id, $victime_race, $victime_grade);

				$degat = $victime_caracs_max['pv']*$degat_prct/100;

				$dist = distance($centre, $victime, $plan);

				$sql="SELECT * FROM cartes
					WHERE id=$plan";
				$res     = mysql_query ($sql) or die (mysql_error());
				$infos_plan    = mysql_fetch_array ($res);
				$x_min	=	$infos_plan['x_min'];
				$x_max	=	$infos_plan['x_max'];
				$y_min	=	$infos_plan['y_min'];
				$y_max	=	$infos_plan['y_max'];

				$taille = max($x_max-$x_min, $y_max-$y_min);

				//Calcul des dégâts.
				$degat*=exp(-pow(3/2*$dist/$taille, 2));

				$victime_carac_noalter=recup_carac($victime_id, array('pv'));

				$em = new \persos\eventManager\eventManager();

				$ev1 = $em->createEvent('explosion');		
				$ev1->setSource($cible[0], $type);						
				
				$ev1->setAffected($victime_id,'perso');
				
				if (($victime_carac_noalter['pv']-$degat)<=0) {
					maj_carac($victime_id, "pv", 0);
					
					// Ajout de l'evenement
					$ev1->setState(1);
			
					
					//Désincarnation
					desincarne($victime_id);
				}
				else {
					$ev1->setState(0);
					maj_carac($victime_id, "pv", $victime_carac_noalter['pv']-$degat);
				}
			}
		}
	}

	if ($type=="bouclier") {
		//Récupération de la position de la bouclier
		$sql		="SELECT pos_x, pos_y, carte_id, type_id, objet_lie FROM damier_bouclier WHERE id=".$cible[0];
		$resultat 	= mysql_query ($sql) or die (mysql_error());
		$pos		= mysql_fetch_array($resultat);
		desincarne($pos['objet_lie'], "objet_complexe");
		if ($pos) {
			$plan 		= $pos['carte_id'];
			$centre 	= array('pos_x'=>$pos['pos_x'],'pos_y'=>$pos['pos_y']);
			//Récupération de la proportion de boucliers restantes dans le plan
			$sql="SELECT COUNT(id) AS nb FROM damier_bouclier WHERE carte_id=$plan";
			$resultat 	= mysql_query ($sql) or die (mysql_error());
			$nb			= mysql_fetch_array($resultat);
			$nb_bouclier 	= $nb['nb'];
			switch($pos['type_id']) {
				case 1 :
					$degat_prct=15;
					break;
				case 2 :
					$degat_prct=25;
					break;
				case 3 :
					$degat_prct=50;
					break;
				case 4 :
					$degat_prct=70;
					break;
				default :
					$degat_prct=0;
			}
			//Application des effets en fonction de la distance ? la bouclier et de leur nombre

			$sql	= "SELECT perso_id AS perso_id, pos_x AS pos_x, pos_y AS pos_y FROM damier_persos WHERE carte_id=$plan";
			$resultat 	= mysql_query ($sql) or die (mysql_error());
			while ($victime=mysql_fetch_array($resultat)) {
				$victime_id = $victime['perso_id'];

				$victime_caracs	= calcul_caracs($victime_id);
				$sql        	="SELECT * FROM persos WHERE id='$victime_id'";
				$res    	= mysql_query($sql)or die (mysql_error());
				$victime_info   = mysql_fetch_array ($res);
				$victime_grade 	= $victime_info['grade_id'];
				$victime_race 	= $victime_info['race_id'];

				$victime_caracs_max = caracs_base_max ($victime_id, $victime_race, $victime_grade);

				$degat = $victime_caracs_max['pv']*$degat_prct/100;

				$dist = distance($centre, $victime, $plan);

				$sql="SELECT * FROM cartes
					WHERE id=$plan";
				$res     = mysql_query ($sql) or die (mysql_error());
				$infos_plan    = mysql_fetch_array ($res);
				$x_min	=	$infos_plan['x_min'];
				$x_max	=	$infos_plan['x_max'];
				$y_min	=	$infos_plan['y_min'];
				$y_max	=	$infos_plan['y_max'];

				$taille = max($x_max-$x_min, $y_max-$y_min);

				//Calcul des dégâts.
				$degat*=exp(-pow(3/2*$dist/$taille, 2));

				$victime_carac_noalter=recup_carac($victime_id, array('pv'));

				
				$em = new \persos\eventManager\eventManager();

				$ev1 = $em->createEvent('explosion');		
				$ev1->setSource($cible[0], $type);						
				
				$ev1->setAffected($victime_id,'perso');
				
				if (($victime_carac_noalter['pv']-$degat)<=0) {
					maj_carac($victime_id, "pv", 0);
					//Désincarnation
					$ev1->setState(1);
					
					desincarne($victime_id);
				}
				else {
				
					$ev1->setState(0);
				
					maj_carac($victime_id, "pv", $victime_carac_noalter['pv']-$degat);
				}
			}
		}
	}

	desincarne($cible[0], $type);
	if ($type=='porte') {
		if (isset($pos['porte_liee_id']) && $pos['porte_liee_id']!=0) {
			$sql            ="SELECT COUNT(porte_liee_id) FROM damier_porte WHERE id=".$pos['porte_liee_id'];
			$new_porte=$pos['porte_liee_id'];
			$resultat       = mysql_query ($sql) or die (mysql_error());
			$pos            = mysql_fetch_array($resultat);
			if ($pos) {
				destruction($new_porte, array($new_porte,'porte'));
			}
		}
	}
}


function reparation($perso_id, $cible, $val) {
	if ($cible[1]=='bouclier_1' || $cible[1]=='bouclier_2' || $cible[1]=='bouclier_3' || $cible[1]=='bouclier_4') {
		$type='bouclier';
	}
	else if ($cible_type=='porte_mauve') {
		$type='porte';
	}
	else $type=$cible[1];

	$sql			= "SELECT * FROM damier_objet_".$cible[1]." WHERE id=".$cible[0];
	$resultat		= mysql_query($sql)or die (mysql_error());
	$cible_caracs	= mysql_fetch_array ($resultat);

	$sql = "UPDATE damier_".$cible[1]." SET pv=".min($cible_carac['pv']+$val, $cible_caracs['pv_max'])." WHERE id=".$cible[0];
	$res = mysql_query($sql)or die (mysql_error());

	if ($cible_carac['pv']<$cible_caracs['pv_max']) {
		return 1;
	} else return 0;
}



function pos_is_free($new_pos) {
	$ok=false;

	$sql="SELECT * FROM damier_persos WHERE pos_x='".$new_pos['pos_x']."' AND pos_y='".$new_pos['pos_y']."' AND carte_id='".$new_pos['plan']."'";
	$resultat = mysql_query ($sql) or die (mysql_error());
	$res=mysql_num_rows($resultat);
	if (!$res) {
		$sql="SELECT * FROM damier_porte WHERE (pos_x<='".$new_pos['pos_x']."') AND (pos_x>(".$new_pos['pos_x']."-4)) AND
			(pos_y>='".$new_pos['pos_y']."') AND (pos_y<(".$new_pos['pos_y']."+4)) AND
			carte_id='".$new_pos['plan']."'";
		$resultat = mysql_query ($sql) or die (mysql_error());
		$res=mysql_num_rows($resultat);
		if (!$res) {
			$sql="SELECT * FROM damier_objet_simple
				INNER JOIN case_objet_simple ON case_objet_simple.id=damier_objet_simple.case_objet_simple_id
				WHERE pos_x='".$new_pos['pos_x']."' AND pos_y='".$new_pos['pos_y']."' AND carte_id='".$new_pos['plan']."' AND case_objet_simple.bloquant='1'";
			$resultat = mysql_query ($sql) or die (mysql_error());
			$res=mysql_num_rows($resultat);
			if (!$res) {
				$sql="SELECT * FROM damier_objet_complexe
					INNER JOIN case_objet_complexe ON case_objet_complexe.id=damier_objet_complexe.case_objet_complexe_id
					WHERE (pos_x<='".$new_pos['pos_x']."') AND (pos_x_max>=".$new_pos['pos_x'].") AND
					(pos_y<='".$new_pos['pos_y']."') AND (pos_y_max>=".$new_pos['pos_y'].") AND
					carte_id='".$new_pos['plan']."' AND case_objet_complexe.bloquant='1'";
				$resultat = mysql_query ($sql) or die (mysql_error());
				$res=mysql_num_rows($resultat);
				if (!$res) {
					$sql="SELECT * FROM damier_bouclier
						WHERE type_id = 1 AND pos_x='".$new_pos['pos_x']."' AND pos_y='".$new_pos['pos_y']."' AND carte_id='".$new_pos['plan']."'";
					$resultat = mysql_query ($sql) or die (mysql_error());
					$res=mysql_num_rows($resultat);
					if (!$res)
					{
						$sql="SELECT * FROM damier_bouclier
							WHERE type_id = 2 AND 	(pos_x<='".$new_pos['pos_x']."') AND (pos_x>(".$new_pos['pos_x']."-2)) AND
							(pos_y>='".$new_pos['pos_y']."') AND (pos_y<(".$new_pos['pos_y']."+2)) AND
							carte_id='".$new_pos['plan']."'";
						$resultat = mysql_query ($sql) or die (mysql_error());
						$res=mysql_num_rows($resultat);
						if (!$res)
						{
							$sql="SELECT * FROM damier_bouclier
								WHERE type_id = 3 AND 	(pos_x<='".$new_pos['pos_x']."') AND (pos_x>(".$new_pos['pos_x']."-3)) AND
								(pos_y>='".$new_pos['pos_y']."') AND (pos_y<(".$new_pos['pos_y']."+3)) AND
								carte_id='".$new_pos['plan']."'";
							$resultat = mysql_query ($sql) or die (mysql_error());
							$res=mysql_num_rows($resultat);
							if (!$res) {
								$sql="SELECT * FROM damier_bouclier
									WHERE type_id = 4 AND 	(pos_x<='".$new_pos['pos_x']."') AND (pos_x>(".$new_pos['pos_x']."-4)) AND
									(pos_y>='".$new_pos['pos_y']."') AND (pos_y<(".$new_pos['pos_y']."+4)) AND
									carte_id='".$new_pos['plan']."'";
								$resultat = mysql_query ($sql) or die (mysql_error());
								$res=mysql_num_rows($resultat);
								if (!$res)
								{
									$ok=true;
								}
								else {
									$ok=false;
								}
							}
							else {
								$ok=false;
							}
						}
						else {
							$ok=false;
						}
					}
					else {
						$ok=false;
					}
				}
				else {
					$ok=false;
				}
			}
			else {
				$ok=false;
			}
		}
		else {
			$ok=false;
		}
	}
	return $ok;
}

function spawn($spawn, $alter_spawn=0) {
	if ($spawn) {
		$sql="SELECT * FROM damier_spawn
			WHERE id=$spawn";
		$resultat     = mysql_query ($sql) or die (mysql_error());
		$res_spawn    = mysql_fetch_array ($resultat);

		$x_min=$res_spawn['pos_x'];
		$x_max=$res_spawn['pos_max_x'];
		$y_min=$res_spawn['pos_y'];
		$y_max=$res_spawn['pos_max_y'];
		$plan =$res_spawn['carte_id'];
	} else {
		$sql="SELECT * FROM cartes
			WHERE nom='Althian'";
		$resultat     = mysql_query ($sql) or die (mysql_error());
		$res_spawn    = mysql_fetch_array ($resultat);
		$x_min=$res_spawn['visible_x_min'];
		$x_max=$res_spawn['visible_x_max'];
		$y_min=$res_spawn['visible_y_min'];
		$y_max=$res_spawn['visible_y_max'];
		$circ =$res_spawn['circ'];
		$plan =$res_spawn['id'];

	}
	$nb_essai	= 0;
	$ok = FALSE;
	while (!$ok && $nb_essai<10000) {
		$new_pos['plan']    = $plan;
		$new_pos['pos_x']    = rand($x_min, $x_max);
		$new_pos['pos_y']    = rand($y_min, $y_max)+$alter_spawn;

		$ok = pos_is_free($new_pos);
		$nb_essai++;
	} if ($nb_essai==10000) {
		$new_pos['reussite'] = false;
	} else $new_pos['reussite'] = true;

	return $new_pos;
}


function passage_porte($pos_x_perso, $pos_y_perso, $plan) {
	$pos    = array("pos_x"=>$pos_x_perso, "pos_y"=>$pos_y_perso, "plan"=>$plan, "reussite"=>false);

	$spawn    = -1;

	for($inc=1; $inc<=$_SESSION['damier_porte']['case']['inc']; $inc++) {
		if ($_SESSION['damier_porte']['case']['pos_x'][$inc]==$pos_x_perso && $_SESSION['damier_porte']['case']['pos_y'][$inc]==$pos_y_perso) {
			$spawn=$_SESSION['damier_porte']['case']['spawn'][$inc];
			$open=$_SESSION['damier_porte']['case']['statut'][$inc];
		}
	}

	if ($spawn != -1 && $open) {
		$new_pos=spawn($spawn);
		if ($new_pos['reussite'] == true) {
			return $new_pos;
		} else {
			return $pos;
		}
	}
	return $pos;
}

function getCaseDecors($id, $x, $y) {

	$sql = "SELECT SQL_CACHE nom_decors FROM cartes WHERE id='$id'";
	$resultat = mysql_query ($sql) or die (mysql_error());
	$carte = mysql_fetch_array ($resultat);

	if($carte['nom_decors'] != null) {
			$decors = \jeu\decors\Decors::prepareDecors($carte['nom_decors']);
			
			if($decors) {
					return $decors->getCase($x,$y);
			}            
	}  
	return null;
}

function set_pos($perso_id, $pos_x_perso, $pos_y_perso, $carte_pos, $type='persos') {
	//echo "$perso_id, $pos_x_perso, $pos_y_perso, $carte_pos, $type";
	global $admin_mode;

	$reussite = true;
	$id = 'perso_id';
	if ($type!='persos') {
		$id = 'id';
	}

	$case = getCaseDecors($carte_pos, $pos_x_perso, $pos_y_perso);
	
	if(isset($case['block'])) {
		return false;
	}

	$sql="SELECT $id
		FROM damier_$type
		WHERE $id='$perso_id'";

	$resultat = mysql_query ($sql) or die (mysql_error());
	if ($is_spawn=mysql_fetch_array($resultat)) {
		$sql="UPDATE IGNORE `ewo`.`damier_$type` SET `pos_x` = '$pos_x_perso', `pos_y` = '$pos_y_perso', `carte_id` =$carte_pos
			WHERE `damier_$type`.`$id` =$perso_id LIMIT 1 ;";

		$resultat = mysql_query ($sql) or die (mysql_error());

		// On vérifie si y'a une erreur
		$reussite = (mysql_affected_rows() == 1) ? true : false;

		if (!isset($admin_mode)) {
			$logger = new at\InterGeoLogger();
			$logger->log($perso_id);
		}
	} else {
		$sql="INSERT IGNORE INTO damier_$type (carte_id, pos_x, pos_y, $id) VALUE ($carte_pos,$pos_x_perso,$pos_y_perso,$perso_id)";
		$resultat = mysql_query ($sql) or die (mysql_error());
	}

	$sql="SELECT * FROM damier_artefact WHERE `pos_x` = '$pos_x_perso' AND `pos_y` = '$pos_y_perso' AND `carte_id` ='$carte_pos'";
	$resultat = mysql_query ($sql) or die (mysql_error());
	while ($artefact=mysql_fetch_array($resultat)) {
		$add_inventaire = add_inventaire($perso_id, $artefact['icone_artefact_id'], $artefact['pv']);
		if ($add_inventaire == true) {
			desincarne($artefact['id'], 'artefact');
		}
	}

	return $reussite;
}


function respawn($id, $type='', $cible_spawn='') {
	if ($type=='autre') {
		$sql="SELECT * FROM persos WHERE id='".$id."'";
		$resultat_ = mysql_query ($sql) or die (mysql_error());
		$res=mysql_fetch_array($resultat_);
		$race= $res['race_id'];
		$perso_id=$id;
	} else {
		$race		=	$_SESSION['persos']['race'][$id];
		$perso_id	=	$_SESSION['persos']['id'][$id];
	}

	$alter_spawn = recup_alter_spawn($perso_id);
	$camp = recup_camp($race);
	$type = recup_type($race);
	$nb_essai	= 0;

	switch($camp) {
		//Les humain peuvent choisir de tenter de respawn
		//dans une zone précise sur terre, dans ce cas
		//leur probabilité de le faire augmente.
		/*case 1 :
			$ok=false;
			while (!$ok) {
				$sql = '
					SELECT
					b.id,b.nom, b.pos_x, b.pos_y,b.type_id,
					c.circ, c.x_min, c.x_max, c.y_min, c.y_max, c.id
						FROM
						`damier_bouclier` b
						INNER JOIN `cartes` c
						ON c.id = b.carte_id
						WHERE b.id = '.$cible_spawn;

				$research = mysql_query($sql);
				$data 	  = mysql_fetch_row($research);

				$x = $data[2] + mt_rand(ceil(-2.5*$data[4]),floor(2.5*$data[4]));
				$y = $data[3] + mt_rand(ceil(-2.5*$data[4]),floor(2.5*$data[4]));

				if (substr($data[5],0,1) == 1) {
					if ($x < $data[6])
						$x = $data[7] - ($data[6] - $data[2] + floor(2.5*$data[4]));
					elseif ($x > $data[7])
						$x = $data[6] + (floor(2.5*$data[4]) + $data[2] - $data[7]);
				}
				else {
					if ($x < $data[6])
						$x = $data[6];
					elseif ($x > $data[7])
						$x = $data[7];
				}

				if (substr($data[5],1,1) == 1) {
					if ($y < $data[8])
						$y = $data[9] - ($data[8] - $data[3] + floor(2.5*$data[4]));
					elseif ($y > $data[9])
						$y = $data[8] + (floor(2.5*$data[4]) + $data[3] - $data[9]);
				}
				else {
					if ($y < $data[8])
						$y = $data[8];
					elseif ($y > $data[9])
						$y = $data[9];
				}

				$research = mysql_query('
						SELECT perso_id
						FROM `damier_persos`
						WHERE pos_x = '.$x.'
						AND pos_y = '.$y.'
						AND carte_id = '.$data[10]) or die(mysql_error());
				$data2 = mysql_fetch_row($research);

				if (!isset($data2[0])) {
					set_pos($perso_id, $x, $y, $data[10]);
					$pos['pos_x'] = $x;
					$pos['pos_y'] = $y;
					$pos['carte_id']=$data[10];
					$ok = true;
				}
			}
			//les parias respawnent sur terre
			//n'importe où, mais à plus de 15 case
			//d'un autre de leurs persos.
			break;
*/
		default :
			$sql="SELECT damier_spawn.id AS id FROM damier_spawn
				INNER JOIN camps ON camps.carte_id = damier_spawn.carte_id
				WHERE camps.id=$camp AND damier_spawn.primaire = 1";
			$resultat     = mysql_query ($sql) or die (mysql_error());
			$inc=0;
			$spawn[$inc]=0;
			while ($res_spawn    = mysql_fetch_array ($resultat)) {
				$inc++;
				$spawn[$inc]=$res_spawn['id'];
			}
			$ok=false;
			if ($inc>=1) {
				$dice = lance_ndp(1,$inc);
			} else $dice = 0;
			while (!$ok) {
				if ($nb_essai<20) {
					$new_pos=spawn($spawn[$dice], $alter_spawn);
				} else $new_pos=spawn(0);
				$nb_essai++;
				if ($new_pos['reussite']) {
					$carte_id = $new_pos['plan'];
					$sql="SELECT * FROM cartes WHERE id='$carte_id'";
					$resultat = mysql_query ($sql) or die (mysql_error());
					$carte = mysql_fetch_array ($resultat);

					$x_min_carte = $carte['x_min'];
					$x_max_carte = $carte['x_max'];

					$y_min_carte = $carte['y_min'];
					$y_max_carte = $carte['y_max'];

					$taille = max($y_max_carte-$y_min_carte, $x_max_carte-$x_min_carte);
					$ok = true;
				}
			}
			$pos_x_perso=$new_pos['pos_x'];
			$pos_y_perso=$new_pos['pos_y'];
			$carte_pos=$new_pos['plan'];

			$reussite = set_pos($perso_id, $pos_x_perso, $pos_y_perso, $carte_pos);

			$pos=$new_pos;
			$pos["carte_id"]=$pos["plan"];

			break;
	}
	return $pos;
}


function maj_pos($inc, $caracs) {
    	if ((isset($_SESSION['persos']['mouv_en_cours'][$inc]) && !$_SESSION['persos']['mouv_en_cours'][$inc]) || !isset($_SESSION['persos']['mouv_en_cours'][$inc])) {
            $_SESSION['persos']['mouv_en_cours'][$inc]=true;
		$perso_id = $_SESSION['persos']['id'][$inc];
		$pos_x_perso = $_SESSION['persos']['pos_x'][$inc];
		$pos_y_perso = $_SESSION['persos']['pos_y'][$inc];
		$carte_pos = $_SESSION['persos']['carte'][$inc];

		$mouv = recup_carac($perso_id, array("mouv"));

		$race = $_SESSION['persos']['race'][$inc];

		$sql="SELECT * FROM cartes WHERE id='$carte_pos'";
		$resultat = mysql_query ($sql) or die (mysql_error());
		$carte = mysql_fetch_array ($resultat);

		$x_min_carte = $carte['x_min'];
		$x_max_carte = $carte['x_max'];
		$_SESSION['persos']['carte_x_min'][$inc] = $carte['x_min'];
		$_SESSION['persos']['carte_x_max'][$inc] = $carte['x_max'];

		$y_min_carte = $carte['y_min'];
		$y_max_carte = $carte['y_max'];

		$_SESSION['persos']['carte_y_min'][$inc] = $carte['y_min'];
		$_SESSION['persos']['carte_y_max'][$inc] = $carte['y_max'];

		$_SESSION['persos']['circ'][$inc] 	= $carte['circ'];
		$_SESSION['persos']['infini'][$inc] 	= $carte['infini'];

		$_SESSION['persos']['carte_nom'][$inc] 	= $carte['nom'];

		for($inci=1 ; $inci<=3 ; $inci++) {
			for($incj=1 ; $incj<=3 ; $incj++) {
				if (isset($_REQUEST['dep'.$inci.$incj])) {

					if ($inci==1) {
						if (($pos_y_perso + 1)>($y_max_carte) && $carte['circ'][1]) {
							$pos_y_perso_new    = $y_min_carte+1;
						} else {
							$pos_y_perso_new = $pos_y_perso + 1;
						}
					} elseif ($inci==3) {
						if (($pos_y_perso - 1)<=($y_min_carte) && $carte['circ'][1]) {
							$pos_y_perso_new    = $y_max_carte;
						} else {
							$pos_y_perso_new = $pos_y_perso - 1;
						}
					} else $pos_y_perso_new = $pos_y_perso;
					if ($incj==1) {
						if (($pos_x_perso - 1)<=($x_min_carte) && $carte['circ'][0]) {
							$pos_x_perso_new    = $x_max_carte;
						} else {
							$pos_x_perso_new = $pos_x_perso - 1;
						}
					} elseif ($incj==3) {
						if (($pos_x_perso + 1)>($x_max_carte) && $carte['circ'][0]) {
							$pos_x_perso_new    = $x_min_carte+1;
						}
						else {
							$pos_x_perso_new = $pos_x_perso + 1;
						}
					}
					else $pos_x_perso_new = $pos_x_perso;
                                        
                                        
					// Le personnage est hors carte si la nouvelle position dépasse la taille maximale de la carte
					// et si la carte n'est pas infinie sur le côté concerné.
					$hors_carte = (($pos_x_perso_new<$x_min_carte) && !$carte['infini'][0]);
					$hors_carte = $hors_carte || ($pos_x_perso_new>$x_max_carte && !$carte['infini'][1]);
					$hors_carte = $hors_carte || ($pos_y_perso_new<$y_min_carte && !$carte['infini'][2]);
					$hors_carte = $hors_carte || ($pos_y_perso_new>$y_max_carte && !$carte['infini'][3]);

					if (!$hors_carte) {
						$cout = $_SESSION['cout'][$pos_x_perso_new][$pos_y_perso_new];
					} else $cout=1;

					if ($caracs["immunite"] && $cout!=-2) {
						$cout=1;
					}

					if ($caracs["mouv"]>=$cout && $cout>=0 && !$hors_carte) {
						$pos_x_perso=$pos_x_perso_new;
						$pos_y_perso=$pos_y_perso_new;
						$mouv= $mouv['mouv']-$cout;

						$pos=passage_porte($pos_x_perso,$pos_y_perso, $carte_pos);
						if ($carte_pos!=$pos['plan']) {
							$sql="SELECT * FROM cartes WHERE id='$carte_pos'";
							$resultat = mysql_query ($sql) or die (mysql_error());
							$carte = mysql_fetch_array ($resultat);

							$_SESSION['persos']['carte_x_min'][$inc] = $carte['x_min'];
							$_SESSION['persos']['carte_x_max'][$inc] = $carte['x_max'];

							$_SESSION['persos']['carte_y_min'][$inc] = $carte['y_min'];
							$_SESSION['persos']['carte_y_max'][$inc] = $carte['y_max'];
						}
						$pos_x_perso=$pos['pos_x'];
						$pos_y_perso=$pos['pos_y'];
						$carte_pos=$pos['plan'];
                                                
						if ($pos['reussite'] == true) {

							$ok=false;
							if (($race==3 && $pos['plan']==3)|| ($race==4 && $pos['plan']==2)) {
								$ok=true;
							}
							if ($ok) {
														
								$depth=abs($pos['pos_y']);
								// Bonus +1 (puis +2, +3, etc) dans la caractéristique :
								// Bonus score de défense : P/5
								maj_alter_plan($perso_id, 'alter_def', floor($depth/5));
								// Bonus score d'attaque : P/9
								maj_alter_plan($perso_id, 'alter_att', floor($depth/9));
								// Bonus mouvement : P/9
								maj_alter_plan($perso_id, 'alter_mouv', floor(($depth+10)/9));

								// Bonus +5 % (puis +10 %, +15 %, etc) dans la caractérisque :
								// Bonus dégâts: P/5
								maj_alter_plan($perso_id, 'alter_force', 5*floor($depth/5));
								// Bonus récup' pv : P/5
								maj_alter_plan($perso_id, 'alter_recup_pv', 5*floor($depth/5));
							} else {
								raz_alter_plan($perso_id);
							}
						}

						$deplacement_reussi = set_pos($perso_id, $pos_x_perso, $pos_y_perso, $carte_pos);

						if ($deplacement_reussi) {
							
							//$case = getCaseDecors($carte_pos, $pos_x_perso, $pos_y_perso);
							
							$cout_pv = 0;
							
							if(isset($case['degats'])) {
								
								$cout_pv = $case['degats'];	
								
								if(isset($case['degats_event'])) {		
									$ix = $case['degats_event'];
									
									$events = SPECIAL_EVENT::$INDEX;
									$em = new \persos\eventManager\eventManager();
									$ev1 = $em->createEvent('special');
									$ev1->setSource($perso_id, 'perso');
									$ev1->infos->addPublicInfo('m',$events[$ix]);
								}
							}					

							$sql = "UPDATE caracs SET `mouv`=`mouv` - ".$cout.", `pv`=`pv` - ".$cout_pv."
									WHERE perso_id=".$perso_id;

							$res = mysql_query($sql);
							
							$_SESSION['persos']['pos_x'][$inc] = $pos_x_perso;
							$_SESSION['persos']['pos_y'][$inc] = $pos_y_perso;
							$_SESSION['persos']['carte'][$inc] = $carte_pos;

							// On log le déplacement dans les évènements.

							$em = new \persos\eventManager\eventManager();

							$ev1 = $em->createEvent('mouv');
                                                        
							$ev1->setSource($perso_id, 'perso');
                                                        
							$ev1->infos->addPrivateInfo('x',$pos_x_perso);
							$ev1->infos->addPrivateInfo('y',$pos_y_perso);
							$ev1->infos->addPrivateInfo('p',$carte['nom']);

							$_SESSION['persos']['mouv_en_cours'][$inc]=false;

							if (false) {
								$record = recup_record("Profondeur", $race_grade['race_id'],'plan\\\\|'.$carte_pos, $perso_id);

								$pos_y_perso_test=abs($pos_y_perso);
								$time=time();
								if ($record) {
									$valeur=unseritab($record['valeur']);
									if (abs($valeur['val'])<$pos_y_perso_test || $valeur['plan']!=$carte_pos) {
										maj_record("Profondeur", $perso_id, "plan|$carte_pos|val|$pos_y_perso|date|$time");
									}
								} else {
									maj_record("Profondeur", $perso_id, "plan|$carte_pos|val|$pos_y_perso|date|$time");
								}
							}
							return $info_action ="D&eacute;placement effectu&eacute;";
						}

						$_SESSION['persos']['mouv_en_cours'][$inc]=false;

						return $info_action ="D&eacute;placement &eacute;chou&eacute;";

					}
					elseif ($caracs["mouv"]<$cout) {
						$_SESSION['temp']['info_action'] = "Vous ne disposez pas d'assez de mouvement pour effectuer ce d&eacute;placement";
					}
					$_SESSION['temp']['info_action'] = "D&eacute;placement impossible";
				}
			}
		}
		$_SESSION['persos']['mouv_en_cours'][$inc]=false;
	}
}

// Fonction retournant l'ensemble des id des portes
// présnte dans un plan $plan
function rechch_id_porte($plan) {
	$sql="SELECT COUNT(id) AS nb FROM damier_porte WHERE carte_id=$plan";
	$resultat 	= mysql_query ($sql) or die (mysql_error());
	$nb			= mysql_fetch_array($resultat);
	$id[0]	 	= $nb['nb'];
	$inc=0;
	$sql="SELECT id AS id FROM damier_porte WHERE carte_id=$plan";
	$resultat 	= mysql_query ($sql) or die (mysql_error());
	while ($res = mysql_fetch_array($resultat)) {
		$id[++$inc]=$res['id'];
	}
	return $id;
}

// Fonction retournant le nom d'une porte d'id $porte_id
function rechch_nom_porte($porte_id) {
	$sql="SELECT nom AS nom FROM damier_porte WHERE id=$porte_id";
	$resultat 	= mysql_query ($sql) or die (mysql_error());
	$nom		= mysql_fetch_array($resultat);

	return $nom['nom'];
}

function suicide($perso_id) {
	meurtre($perso_id, $perso_id, 'suicide');
	$sql = "SELECT nb_suicide AS nb FROM persos WHERE id=$perso_id";
	$res = mysql_query($sql)or die (mysql_error());
	$nb  = mysql_fetch_array ($res);
	$nb_suicide = $nb['nb'];
	$nb_suicide++;

	$sql = "UPDATE persos SET `nb_suicide`=$nb_suicide WHERE id=$perso_id";
	$res = mysql_query($sql)or die (mysql_error());
}

// Explose la chaine du nom d'une action, et renvoie le nom correspondant au camp (ou le nom par défaut, le cas échéant)
function explose_nom_action($nom, $camp) {

	$tableau = explode("|",$nom);

	if ($camp > 0 && $camp < 5) {
		$indice = $camp - 1;
	} else {
		$indice = 0;
	}

	if (array_key_exists($indice, $tableau) && strlen($tableau[$indice]) > 0) {
		return $tableau[$indice];
	} else {
		return $tableau[0];
	}
}

// Récupère le tableau des effets d'une action, en appliquant le coefficiant envoyé
function recup_tableau_effets($effets_liste, $coef) {
	$effets = explode(':',$effets_liste); //Liste des effets
	$effets_lanceur = explode(',',$effets[0]);
	$effets_cible	= explode(',',$effets[1]);

	// On regroupe les effets en un seul tableau
	$effets_array = array_merge ( $effets_lanceur , $effets_cible );

	// Récupération des effets, et application du coefficiant
	foreach($effets_array as $effet_ligne) {
		if ($effet_ligne != 0) {
			$sql        ="SELECT * FROM effet WHERE id='$effet_ligne'";
			$resultat    = mysql_query($sql)or die (mysql_error());
			$effet = mysql_fetch_array ($resultat);


			if (is_numeric($effet['effet'])) {
				// Si l'effet est un numérique
				$effet['effet'] = ceil($effet['effet'] * $coef);
			} elseif (substr($effet['effet'], -1)=="%") {
				// Si c'est un pourcentage
				$nombre = substr($effet['effet'], 0, strlen($effet['effet'])-1);
				$valeur = min(99,ceil($nombre + ($nombre/3 * ($coef-1))));
				$effet['effet'] = $valeur.'%';
			}

			$tableau_effets[$effet_ligne] = $effet;
		}
	}

	return $tableau_effets;
}

// Applique un des effets d'un sort
// Les cibles sont des tableau contenant un id et un type
// Le type permet de savoir si on répare une porte ou un objet complexe
// applique_effet(id de l'effet, cible(id cible, type cible), cible2(id cible, type cible))
function applique_effet($tableau, $effet_id, $cible, $cible2=array(0,'',''), $rtm) {


	//recup des infos perso
	$perso_id     		= $_SESSION['persos']['current_id'];
	$id            		= $_SESSION['persos']['id'][0];
	$pos_x_perso     	= $_SESSION['persos']['pos_x'][$id];
	$pos_y_perso     	= $_SESSION['persos']['pos_y'][$id];
	$carte_pos         	= $_SESSION['persos']['carte'][$id];
	$perso_grade 		= $_SESSION['persos']['grade'][$id];
	$perso_race 		= $_SESSION['persos']['race'][$id];
	$perso_galon		= $_SESSION['persos']['galon'][$id];
	$caracs         	= calcul_caracs();
	$caracs_alter      	= calcul_caracs_alter($perso_id);

	$perso_carac_noalter=recup_carac($perso_id, array('pa', 'malus_def'));

	$caracs_max	= caracs_base_max($perso_id, $perso_race, $perso_grade);
	$nbpa = $caracs_max['pa'] + ($caracs_max['pa_dec'] / 10);

	$pos_x_centre 	= $_SESSION['centre_effet']['pos_x'];
	$pos_y_centre 	= $_SESSION['centre_effet']['pos_y'];
	$centre 		= array('pos_x'=>$pos_x_centre,'pos_y'=>$pos_y_centre);

	$bonus_effet_race = bonus_effet($perso_race, $perso_grade, $perso_galon);
	$bonus_effet_mag  = $caracs_alter;
	if ($cible[2]!='aura' && $cible[2]!='attaque') {
		$bonus_global= $bonus_effet_race + $bonus_effet_mag['alter_effet'];
	} else {
		$bonus_global = $bonus_effet_mag['alter_effet'];
	}
	$bonus_global_alt = $bonus_global;
	$caracs['force']= $caracs['force'] + $caracs['force']*$bonus_effet_mag['alter_effet']/100;

	$malus = ($caracs['force'] - $caracs['force']%10)/10;

	$malus=max(1, $malus);

	//recup des infos sur l'effet
	$effet_info    = $tableau[$effet_id];

	// Application de la RTM
	if (is_numeric($effet_info['effet'])) {
		// Si l'effet est un numérique
		$effet_info['effet'] = ceil($effet_info['effet'] * ((100-$rtm)/100));
	} elseif (substr($effet_info['effet'], -1)=="%") {
		// Si c'est un pourcentage
		$nombre = substr($effet_info['effet'], 0, strlen($effet_info['effet'])-1);
		$valeur = ceil($nombre * ((100-$rtm)/100));
		$effet_info['effet'] = $valeur.'%';
	}

	if ($cible[1]=='bouclier_1' || $cible[1]=='bouclier_2' || $cible[1]=='bouclier_3' || $cible[1]=='bouclier_4') {
		$type='bouclier';
	}
	elseif ($cible[1]=='porte_mauve') {
		$type='porte';
	}
	else $type=$cible[1];

	//Recuperation des caracs alterées
	$cible_id = $cible[0];
	if ($cible[1]=='allier' || $cible[1]=='ennemi' || $cible[1]=='both' || $cible[1]=='persos') {
		$cible_caracs	= calcul_caracs($cible[0]);
		$sql        	="SELECT * FROM persos WHERE id='$cible_id'";
		$resultat    	= mysql_query($sql)or die (mysql_error());
		$cible_info    	= mysql_fetch_array ($resultat);
		$cible_grade 	= $cible_info['grade_id'];
		$cible_race 	= $cible_info['race_id'];

		$cible_caracs_max = caracs_base_max ($cible_id, $cible_race, $cible_grade);

		$sql			= "SELECT * FROM damier_persos WHERE perso_id=".$cible[0];
		$resultat		= mysql_query($sql)or die (mysql_error());
		$pos			= mysql_fetch_array ($resultat);
		$cible_pos = array('pos_x'=>$pos['pos_x'],'pos_y'=>$pos['pos_y']);

		$cible_caracs_alter      	= calcul_caracs_alter($cible_id);
		$bonus_global -= min(70,$cible_caracs['res_mag']) ;
		$bonus_global_alt = max(-100, $bonus_global+min(70,$cible_caracs['res_mag']));
		$bonus_global = max(-100, $bonus_global);
	} elseif ($cible[1]!='none') {
		$sql			= "SELECT * FROM damier_".$type." WHERE id=".$cible[0];
		$resultat		= mysql_query($sql)or die (mysql_error());
		$cible_caracs	= mysql_fetch_array ($resultat);
		if ($cible_caracs) {
			$cible_pos = array('pos_x'=>$cible_caracs['pos_x'],'pos_y'=>$cible_caracs['pos_y']);
			$id_obj = $cible[0];
			if ($type!='bouclier' && $type!='porte') {
				$_SESSION['case_id'][$id_obj]	= $cible_caracs['case_'.$type.'_id'];
				$_SESSION['case_type'][$id_obj] = $type;
			} else {
				$_SESSION['case_id'][$id_obj]	= $cible[0];
				$_SESSION['case_type'][$id_obj] = $type;
			}
			if ($type=='objet_simple' || $type=='objet_complexe') {
				$sql			= "SELECT * FROM case_".$type." WHERE id=".$cible_caracs['case_'.$type.'_id'];
				$resultat		= mysql_query($sql)or die (mysql_error());
				$resultat		= mysql_fetch_array ($resultat);
				$cible_caracs['pv_max']=$resultat['pv_max'];
			}
		} else {
			$cible_caracs['pv']=0;
			$cible_caracs['pv_max']=0;
		}
		$cible_caracs_alter['alter_res_mag']=0;
	}

	$type_effet=$effet_info['type_effet'];
	$nb=$_SESSION['event_effect']['nb'];
	$_SESSION['event_effect']['type'][$nb]=$type_effet;
	$_SESSION['event_effect']['val'][$nb]=0;
	$_SESSION['event_effect']['id'][$nb]=$effet_id;
	switch ($type_effet) {
		case 'pv':
			if ($cible[1]=='allie' || $cible[1]=='ennemi' || $cible[1]=='both' || $cible[1]=='persos') {
				//Recupération de caracs non altérées
				$cible_carac_noalter=recup_carac($cible[0], array('pv', 'malus_def'));
			}
			else {
				$cible_carac_noalter=$cible_caracs;
			}
			if ($cible[1]=='allier' || $cible[1]=='ennemi' || $cible[1]=='both' || $cible[1]=='persos') {
				$pv_max = calcul_caracs_alter($cible_id);
				$cible_caracs_max['pv']+=$pv_max['alter_pv'];
			} else $cible_caracs_max['pv']=$cible_carac_noalter['pv_max'];

			//L'effet a pour valeur 0, dans le cas d'un effet sur les pv c'est une attaque au cac
			if ($effet_info['effet']==0) {
				if (isset($cible[2]) && $cible[2]=='entrainement') {
					maj_carac($cible[0], "malus_def", $cible_carac_noalter['malus_def']+1);
					if ($cible[0]!=$perso_id) {
						maj_carac($perso_id, "malus_def", $perso_carac_noalter['malus_def']+1);
					}
				} else {
					if ($cible_caracs['pv']>0) {
						if (isset($cible[2]) && ($cible[2]=='attaque' || $cible[2]=='attaque_objet')) {
							$type_esquive=0;

							//On mémorise les dégâts faits
							if ($_SESSION['demi_esquive']) {
								$caracs['force']=$caracs['force']/2;
								$malus=$malus/2;
								$type_esquive=2;
							}

							if ($caracs['force']%10!=0) {
								$reste = 10*($caracs['force']%10);
								$test = lance_ndp(1,100);

								if ($test<=$reste) {
									$malus = max(1,($caracs['force'] - $caracs['force']%10)/10 + 1);
								} else $malus = max(1,($caracs['force'] - $caracs['force']%10)/10);
							}
							$_SESSION['event_effect']['val'][$nb]=$caracs['force'];
							$_SESSION['event_effect']['malus']=$malus;

							if (($cible_caracs['pv']-$caracs['force'])<=0 && $cible_caracs['pv']>0) {
								//meurtre(tueur, tué), désincarne, calcule et maj la perte d'xp du tué, et le gain du tueur
								if ($cible[1]=='allier' || $cible[1]=='ennemi' || $cible[1]=='both' || $cible[1]=='persos') {
									$nb_mort = ++$_SESSION['mort']['nb'];
									meurtre($perso_id, $cible[0], 'mort_att', $type_esquive);
									$_SESSION['mort']['nom'][$nb_mort] = $cible_info['nom'];
									$_SESSION['mort']['id'][$nb_mort] = $cible_info['id'];
								} else {
									destruction($perso_id, $cible);
									$_SESSION['destruction']['nb'] += 1 ;
									$nb_dest = $_SESSION['destruction']['nb'];
									$_SESSION['destruction']['type'][$nb_dest] = $cible[1] ;
									$_SESSION['destruction']['id'][$nb_dest] = $cible[0] ;
									calcul_xp($perso_id, $cible[0], 'destruction_'.$cible[1], 1, $type_esquive);
								}

								$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."L'est mort !";
							} else {
								if ($cible[1]=='allier' || $cible[1]=='ennemi' || $cible[1]=='both' || $cible[1]=='persos') {
									maj_carac($cible[0], "pv", max($cible_caracs['pv']-$caracs['force'],0));
									maj_carac($cible[0], "malus_def", $cible_carac_noalter['malus_def']+$malus);
								} else {
									$cible_caracs['pv']-=$caracs['force'];
									$sql = "UPDATE damier_".$type." SET pv=".$cible_caracs['pv']." WHERE id=".$cible[0];
									$res = mysql_query($sql)or die (mysql_error());
									calcul_xp($perso_id, $cible[0], 'attaque_'.$cible[1], 1, $type_esquive);
								}
							}
							$_SESSION['score']['degats'] = $caracs['force'];
						} else {
							$_SESSION['event_effect']['val'][$nb]=$caracs['force'];
							if (!($cible[1]=='allier' || $cible[1]=='ennemi' || $cible[1]=='both' || $cible[1]=='persos')) {
								$_SESSION['reparation']['dif'] =  $cible_caracs_max['pv'] - $cible_caracs['pv'];
								$_SESSION['reparation']['cible'] = $cible[0];
								$_SESSION['reparation']['type'] = $type;
								$cible_caracs['pv']+=$caracs['force'];
								$sql = "UPDATE damier_".$type." SET pv=".min($cible_caracs['pv'],$cible_caracs_max['pv'])." WHERE id=".$cible[0];
								$res = mysql_query($sql)or die (mysql_error());
								calcul_xp($perso_id, $cible[0], 'repare_'.$cible[1], ($cible_caracs['pv']<$cible_caracs_max['pv']), 0);
							}
						}
					}
				}
			} else {
				if ($cible_caracs['pv']>0) {
					$prct=0;

					if (strpos($effet_info['effet'], '%')) {
						$effet_info['effet']=substr_replace($effet_info['effet'],'',strpos($effet_info['effet'], '%'));
						$prct=$effet_info['effet']*(1+$bonus_global/100);
						if ($cible[1]=='allier' || $cible[1]=='ennemi' || $cible[1]=='both' || $cible[1]=='persos') {
							$effet_info['effet'] = $cible_caracs_max['pv']*$effet_info['effet']/100;
						} else {
							$effet_info['effet'] = $cible_caracs_max['pv']*$effet_info['effet']/1000;
						}

					}
					$dist = distance($centre, $cible_pos, $carte_pos);
					//Calcul des dégâts.
					if ($effet_info['effet']<=0) {
						$effet_info['effet']+=$effet_info['effet']*$bonus_global/100;
					} else {
						$effet_info['effet']+=$effet_info['effet']*($bonus_global_alt)/100;
					}

					$effet_info['effet']*=exp(-pow(3/2*$dist/$caracs['perception'], 2));
					$effet_info['effet']=round($effet_info['effet']);
					if ($prct) {
						$_SESSION['event_effect']['val'][$nb]=round($prct).'%';
					} else $_SESSION['event_effect']['val'][$nb]=$effet_info['effet'];

					$effet_info['effet']=round($effet_info['effet']);
					if (($cible_caracs['pv']+$effet_info['effet'])<=0 && $cible_caracs['pv']>0) {
						//meurtre(tueur, tué), désincarne, calcule et maj la perte d'xp du tué, et le gain du tueur
						if ($cible[1]=='allier' || $cible[1]=='ennemi' || $cible[1]=='both' || $cible[1]=='persos') {
							if ($perso_id==$cible[0]) {
								suicide($perso_id);
							} else {
								$nb_mort = ++$_SESSION['mort']['nb'];
								meurtre($perso_id, $cible[0], 'mort_sort');
								$_SESSION['mort']['somme_rang']+=calcul_rang($cible_caracs['px']);
								$_SESSION['mort']['table_rang'][] = calcul_rang($cible_caracs['px']);								
								$_SESSION['mort']['nom'][$nb_mort] = $cible_info['nom'];
								$_SESSION['mort']['id'][$nb_mort] = $cible_info['id'];
							}
						} else {
							destruction($perso_id, $cible);
							$_SESSION['destruction']['nb'] += 1 ;
							$nb_dest = $_SESSION['destruction']['nb'];
							$_SESSION['destruction']['type'][$nb_dest] = $cible[1] ;
							$_SESSION['destruction']['id'][$nb_dest] = $cible[0] ;
							calcul_xp($perso_id, $cible[0], 'destruction_'.$cible[1], 1, 0);
						}
						$_SESSION['temp']['info_action'] = $_SESSION['temp']['info_action']."L'est mort !";
					} else {
						if ($cible[1]=='allier' || $cible[1]=='ennemi' || $cible[1]=='both' || $cible[1]=='persos') {

							maj_carac($cible[0], "pv", min($cible_caracs['pv']+$effet_info['effet'], $cible_caracs_max['pv']));
						} else {

							$sql = "UPDATE damier_".$type." SET pv=".min($cible_caracs['pv']+$effet_info['effet'], $cible_caracs_max['pv'])." WHERE id=".$cible[0];
							$res = mysql_query($sql)or die (mysql_error());
							if (($cible_caracs['pv']+$effet_info['effet'])>=$cible_caracs['pv']) {
								calcul_xp($perso_id, $cible[0], 'repare_'.$cible[1], ($cible_caracs['pv']<$cible_caracs_max['pv']), 0);
							} else calcul_xp($perso_id, $cible[0], 'attaque_'.$cible[1], 1, 0);
						}
					}
				}
			}
			break;

		case 'teleportation':
			if ($cible2[1]!='porte') {
				$cible_pos = array('pos_x'=>$cible['cible_x'],'pos_y'=>$cible['cible_y'], 'plan'=>$carte_pos);
				$dist = distance($centre, $cible_pos, $carte_pos);
				$sql="SELECT * FROM cartes WHERE id='$carte_pos'";
				$resultat = mysql_query ($sql) or die (mysql_error());
				$carte = mysql_fetch_array ($resultat);

				if ($dist>$caracs['perception']) {
					if ($cible['cible_x']>$carte['visible_x_max']) {
						$cible['cible_x']=$carte['visible_x_max'];
					}
					if ($cible['cible_x']<=$carte['visible_x_min']) {
						$cible['cible_x']=$carte['visible_x_min']+1;
					}
					if ($cible['cible_y']>$carte['visible_y_max']) {
						$cible['cible_y']=$carte['visible_y_max'];
					}
					if ($cible['cible_y']<=$carte['visible_y_min']) {
						$cible['cible_y']=$carte['visible_y_min']+1;
					}
				} else {
					if ($carte['circ'][0]==1) {
						if ($cible['cible_x']>$carte['x_max']) {
							$cible['cible_x']=$carte['x_max'];
						}
						if ($cible['cible_x']<=$carte['x_min']) {
							$cible['cible_x']=$carte['x_min']+1;
						}
					} else {
						if (!$carte['infini'][0] && $cible['cible_x']<=$carte['x_min']) {
							$cible['cible_x']=$carte['x_min']+1;
						}
						if (!$carte['infini'][1] && $cible['cible_x']>$carte['x_max']) {
							$cible['cible_x']=$carte['x_max'];
						}
					}
					if ($carte['circ'][1]==1) {
						if ($cible['cible_y']>$carte['y_max']) {
							$cible['cible_y']=$carte['y_max'];
						}
						if ($cible['cible_y']<=$carte['y_min']) {
							$cible['cible_y']=$carte['y_min']+1;
						}
					} else {
						if (!$carte['infini'][2] && $cible['cible_y']<=$carte['y_min']) {
							$cible['cible_y']=$carte['y_min']+1;
						}
						if (!$carte['infini'][3] && $cible['cible_y']>$carte['y_max']) {
							$cible['cible_y']=$carte['y_max'];
						}
					}
				}
				if ($cible[0]==$perso_id) {
					$_SESSION['centre_effet']['pos_x'] = $cible['cible_x'];
					$_SESSION['centre_effet']['pos_y'] = $cible['cible_y'];
				}
				$cible_pos = array('pos_x'=>$cible['cible_x'],'pos_y'=>$cible['cible_y'], 'plan'=>$carte_pos);
				$cible_pos['reussite'] = true;
			} else {
				$sql = "SELECT porte_liee_id AS id
					FROM damier_porte
					WHERE id=".$cible[0];
				$reponse = mysql_query($sql)or die (mysql_error());
				$porte_liee_id = mysql_fetch_array($reponse);
				$porte_liee_id = $porte_liee_id['id'];

				$sql = "SELECT spawn_id AS id
					FROM damier_porte
					WHERE id=".$porte_liee_id;
				$reponse = mysql_query($sql)or die (mysql_error());
				$spawn_id = mysql_fetch_array($reponse);
				$spawn_id = $spawn_id['id'];


				$cible_pos = spawn($spawn_id);
			}
			if (pos_is_free($cible_pos) && $cible_pos['reussite']) {
				set_pos($perso_id, $cible_pos['pos_x'], $cible_pos['pos_y'], $cible_pos['plan']);
				$_SESSION['temp']['teleportation']=true;
			}
			break;

		case 'dla':
			if ($cible[1]=='allie' || $cible[1]=='ennemi' || $cible[1]=='both' || $cible[1]=='persos') {
				$sql = "SELECT date_tour FROM persos WHERE id = '".$cible[0]."'";
				$resultat = mysql_query ($sql) or die (mysql_error());
				$persos = mysql_fetch_array ($resultat);
				$datetour = $persos['date_tour'];
				$datetour = strtotime($datetour);
				$nouveautour = $datetour+60*$effet_info['effet'];
				$nouveautour = date('Y-m-d H:i:s',$nouveautour);
				mysql_query("UPDATE persos SET date_tour = '$nouveautour' WHERE id = '".$cible[0]."'") or die (mysql_error());
			}
			break;

		case 'permutation':
			$ok=false;
			if ($cible[1]=='allie' || $cible[1]=='ennemi' || $cible[1]=='both' || $cible[1]=='persos' || $cible[1]=='objet_simple') {
				$id = 'id';
				switch($cible[1]) {
					case 'allie' :
					case 'ennemi' :
					case 'choix' :
					case 'persos' :
						$cible[1]='persos';
						$id= 'perso_id';
				}
				$sql = "SELECT * FROM damier_".$cible[1]." WHERE $id=".$cible[0]." AND carte_id=$carte_pos";
				$reponse = mysql_query($sql)or die (mysql_error());
				if ($dest = mysql_fetch_array($reponse)) {
					$dest['plan']=$dest['carte_id'];
					$ok=true;
				}
			}
			if ($ok) {
				if ($cible2[1]=='allie' || $cible2[1]=='ennemi' || $cible2[1]=='both' || $cible2[1]=='persos' || $cible2[1]=='objet_simple') {
					$id = 'id';
					switch($cible[1]) {
						case 'allie' :
						case 'ennemi' :
						case 'choix' :
						case 'persos' :
							$cible[1]='persos';
							$id= 'perso_id';
					}
					$org = $dest;
					$sql = "SELECT * FROM damier_".$cible2[1]." WHERE $id=".$cible2[0]." AND carte_id=$carte_pos";
					$reponse = mysql_query($sql)or die (mysql_error());
					if ($dest = mysql_fetch_array($reponse)) {
						$dest['plan']=$dest['carte_id'];
					}
				} else {
					$org = $dest;
					$cible2[0]=$perso_id;
					$dest = array('pos_x'=>$pos_x_perso,'pos_y'=>$pos_y_perso, 'plan'=>$carte_pos);
				}
			}
			if ($ok) {
				set_pos($cible[0], $dest['pos_x'], $dest['pos_y'], 255);
				set_pos($cible2[0], $org['pos_x'], $org['pos_y'], $org['plan'], $cible[1]);
				set_pos($cible[0], $dest['pos_x'], $dest['pos_y'], $dest['plan']);
				$_SESSION['temp']['teleportation']=true;
			}
			break;

		case 'invocation':
			if ($cible[1]=='allie' || $cible[1]=='ennemi' || $cible[1]=='both' || $cible[1]=='persos') {
				$x_min=$pos_x_perso - 2;
				$x_max=$pos_x_perso + 2;
				$y_min=$pos_y_perso - 2;
				$y_max=$pos_y_perso + 2;
				$plan =$carte_pos;

				$nb_essai	= 0;
				$ok = FALSE;
				while (!$ok && $nb_essai<100) {

					$new_pos['plan']    = $plan;
					$new_pos['pos_x']    = rand($x_min, $x_max);
					$new_pos['pos_y']    = rand($y_min, $y_max);

					$ok = pos_is_free($new_pos);
					$nb_essai ++;
				}
				if ($nb_essai==100) {
					$new_pos['reussite'] = false;
				} else $new_pos['reussite'] = true;
			}
			if ($new_pos['reussite']) {
				set_pos($cible[0], $new_pos['pos_x'], $new_pos['pos_y'], $new_pos['plan']);
				$_SESSION['temp']['teleportation']=true;
			}
			break;

		case 'xp':
			if ($cible[1]=='allie' || $cible[1]=='ennemi' || $cible[1]=='both' || $cible[1]=='persos') {
				$cible_pexe=recup_carac($cible[0], array('px', 'pi'));
				maj_carac($cible[0], "px", $cible_pexe['px']+$effet_info['effet']);
				maj_carac($cible[0], "pi", $cible_pexe['pi']+$effet_info['effet']);
			}
			break;

		case 'event_mouv' :
			if ($cible[1]=='allie' || $cible[1]=='ennemi' || $cible[1]=='both' || $cible[1]=='persos') {
			
				$em = new \persos\event\eventManager();
				$ev1 = $em->createEvent('mouv');
				$ev1->setSource($cible[0], 'perso');
				$ev1->infos->addPrivateInfo('x',0);
				$ev1->infos->addPrivateInfo('y',0);
				$ev1->infos->addPrivateInfo('p','Feinte de mouvement');				
				
				
			}
			break;

		case 'immunite':
			maj_carac_alter_mag($cible[0], $type_effet, $effet);
			break;

		case 'home':
			respawn($cible[0], 'autre');
			$_SESSION['temp']['teleportation']=true;
			break;

		case 'retour':
			if ($cible[1]=='allie' || $cible[1]=='ennemi' || $cible[1]=='both' || $cible[1]=='persos') {
			}
			break;

		case 'reincarnum':
			break;

		case 'brulessence':
			if ($cible[1]=='allie' || $cible[1]=='ennemi' || $cible[1]=='both' || $cible[1]=='persos') {
				$cible_carac_noalter=recup_carac($cible[0], array('malus_def'));

				$sql = "SELECT inventaire.id AS id_invntaire
					FROM inventaire
					INNER JOIN case_artefact ON case_artefact.id=inventaire.case_artefact_id
					WHERE (case_artefact.nom='Essence' OR case_artefact.nom ='essence') AND inventaire.perso_id=".$cible[0];
				$resultat = mysql_query($sql)or die (mysql_error());
				$resultat = mysql_fetch_array($resultat);

				if ($resultat) {
					$carac_max=carac_max ($cible_race, $cible_grade, 'pv', recup_carac($cible[0], array("niv_pv")), $cible[0]);

					$sql_inventaire = "DELETE FROM inventaire WHERE id = '".$resultat['id_inventaire']."'";
					mysql_query($sql_inventaire);
					$effet=$cible_caracs['pv']-60;
					maj_carac($cible[0], 'pv', $effet);
					$effet-=$cible_max*10/100;
					maj_carac($cible[0], 'pv', $effet);

					if ($effet<= 0 ) {
						meurtre($perso_id, $cible[0], 'mort_sort');
						$nb_mort = ++$_SESSION['mort']['nb'];
						$_SESSION['mort']['nom'][$nb_mort] = $cible_info['nom'];
						$_SESSION['mort']['id'][$nb_mort] = $cible_info['id'];
					}
					maj_carac($cible[0], "malus_def", $cible_carac_noalter['malus_def']+5);
				}
			}
			break;

		case 'trans_pv':
		case 'trans_pa':
		case 'trans_force':
		case 'trans_mouv':
		case 'trans_recup_pv':
		case 'trans_res_mag':
		case 'trans_perception':
			if ($cible[1]=='allie' || $cible[1]=='ennemi' || $cible[1]=='both' || $cible[1]=='persos') {
				$type_carac = str_replace("trans_","",$type_effet);
				$niv = recup_carac($perso_id, array(str_replace("trans_","niv_",$type_effet)));
				$perso_caracs_max=carac_max ($perso_race, $perso_grade, $type_carac , $niv[str_replace("trans_","niv_",$type_effet)], $perso_id);
				$_SESSION['event_effect']['val'][$nb]=$effet_info['effet'];
				if (strpos($effet_info['effet'], '%')) {
					$effet_info['effet']=substr_replace($effet_info['effet'],'',strpos($effet_info['effet'], '%'));
					$effet_info['effet']*=$perso_caracs_max/100;
				}

				if ($effet_info['effet']<=0 || $type_effet!='trans_pv') {
					$effet_info['effet']+=$effet_info['effet']*$bonus_global/100;
				} else {
					$effet_info['effet']+=$effet_info['effet']*($bonus_global_alt)/100;
				}

				$dist = distance($centre, $cible_pos, $carte_pos);
				$effet_info['effet']*=exp(-pow(3/2*$dist/$caracs['perception'], 2));

				$effet_info['effet']=round($effet_info['effet']);
				if ($effet_info['effet']==0) {
					$effet_info['effet']-= $caracs[str_replace("trans_","",$type_effet)];
				}
				$effet=$cible_caracs[str_replace("trans_","",$type_effet)]+$effet_info['effet'];
				maj_carac($cible[0], str_replace("trans_","",$type_effet), $effet);

				$effet=$caracs[str_replace("trans_","",$type_effet)]-$effet_info['effet'];
				maj_carac($perso_id, str_replace("trans_","",$type_effet), $effet);
				if ($type_effet=='trans_pv') {
					if (($caracs['pv']-$effet_info['effet'])<= 0 ) {
						suicide($perso_id);
					}
					if (($cible_caracs['pv']+$effet_info['effet'])<= 0 ) {
						meurtre($perso_id, $cible[0], 'mort_sort');
						$nb_mort = ++$_SESSION['mort']['nb'];
						$_SESSION['mort']['nom'][$nb_mort] = $cible_info['nom'];
						$_SESSION['mort']['id'][$nb_mort] = $cible_info['id'];
					}
				}
			} elseif ($type_effet=='trans_pv') {
				if ($cible_carac_noalter['pv']>0) {
					if (strpos($effet_info['effet'], '%')) {
						$effet_info['effet']=substr_replace($effet_info['effet'],'',strpos($effet_info['effet'], '%'));
						$effet_info['effet']*=$cible_carac_noalter['pv_max']/1000;
					}
					$effet_info['effet']+=$effet_info['effet']*$bonus_global/100;

					$dist = distance($centre, $cible_pos, $carte_pos);
					$effet_info['effet']*=exp(-pow(3/2*$dist/$caracs['perception'], 2));

					$effet_info['effet']=round($effet_info['effet']);
					if (($cible_carac_noalter['pv']+$effet_info['effet'])<= 0 ) {
						$effet=$caracs[str_replace("trans_","",$type_effet)]-$effet_info['effet'];
						maj_carac($perso_id, str_replace("trans_","",$type_effet), $effet);
						if (($caracs['pv']-$effet_info['effet'])<= 0 ) {
							suicide($perso_id);
						}
						destruction($perso_id, $cible);
						$_SESSION['destruction']['nb'] += 1 ;
						$nb_dest = $_SESSION['destruction']['nb'];
						$_SESSION['destruction']['type'][$nb_dest] = $cible[1] ;
						$_SESSION['destruction']['id'][$nb_dest] = $cible[0] ;

						calcul_xp($perso_id, $cible[0], 'destruction_'.$cible[1], 1, 0);
					} else {
						$effet=$caracs[str_replace("trans_","",$type_effet)]-$effet_info['effet'];
						maj_carac($perso_id, str_replace("trans_","",$type_effet), $effet);
						if (($caracs['pv']-$effet_info['effet'])<= 0 ) {
							suicide($perso_id);
						}

						$sql = "UPDATE damier_".$type." SET pv=".min($perso_carac_noalter['pv']+$effet_info['effet'], $perso_caracs_max['pv'])." WHERE id=".$cible[0];
						$res = mysql_query($sql)or die (mysql_error());
						if (($perso_carac_noalter['pv']+$effet_info['effet'])>=$perso_carac_noalter['pv']) {
							calcul_xp($perso_id, $cible[0], 'repare_'.$cible[1], ($perso_carac_noalter['pv']<$perso_caracs_max['pv']), 0);
						} else calcul_xp($perso_id, $cible[0], 'attaque_'.$cible[1], 1, 0);
					}
				}
			}
			break;

		case 'trans_att':
		case 'trans_def':
			if ($cible[1]=='allie' || $cible[1]=='ennemi' || $cible[1]=='both' || $cible[1]=='persos') {

				$cible_carac_alter_mag=recup_carac_alter_mag($cible[0], str_replace("trans_","alter_",$type_effet));
				$perso_carac_alter_mag=recup_carac_alter_mag($perso_id, str_replace("trans_","alter_",$type_effet));

				$effet_info['effet']+=$effet_info['effet']*$bonus_global/100;

				$dist = distance($centre, $cible_pos, $carte_pos);
				$effet_info['effet']*=exp(-pow(3/2*$dist/$caracs['perception'], 2));

				$_SESSION['event_effect']['val'][$nb]=$effet_info['effet'];

				$effet=$cible_carac_alter_mag[str_replace("trans_","alter_",$type_effet)]+$effet_info['effet'];
				maj_carac_alter_mag($cible[0], str_replace("trans_","alter_",$type_effet), $effet);

				$effet=$perso_carac_alter_mag[str_replace("trans_","alter_",$type_effet)]-$effet_info['effet'];
				maj_carac_alter_mag($perso_id, str_replace("trans_","alter_",$type_effet), $effet);
			}
			break;

		case 'aspire_pv':
		case 'aspire_pa':
		case 'aspire_force':
		case 'aspire_mouv':
		case 'aspire_recup_pv':
		case 'aspire_res_mag':
		case 'aspire_perception':
			if ($cible[1]=='allie' || $cible[1]=='ennemi' || $cible[1]=='both' || $cible[1]=='persos') {
				$type_carac = str_replace("aspire_","",$type_effet);
				$niv = recup_carac($cible[0], array(str_replace("aspire_","niv_",$type_effet)));
				$carac_max=carac_max ($cible_race, $cible_grade, $type_carac, $niv[str_replace("aspire_","niv_",$type_effet)], $cible[0]);

				$_SESSION['event_effect']['val'][$nb]=$effet_info['effet'];
				if (strpos($effet_info['effet'], '%')) {
					$effet_info['effet']=substr_replace($effet_info['effet'],'',strpos($effet_info['effet'], '%'));
					$effet_info['effet']*=$carac_max/100;
				}
				if ($effet_info['effet']>=0 || $type_effet!='aspire_pv') {
					$effet_info['effet']+=$effet_info['effet']*$bonus_global/100;
				} else {
					$effet_info['effet']+=$effet_info['effet']*($bonus_global_alt)/100;
				}

				$dist = distance($centre, $cible_pos, $carte_pos);
				$effet_info['effet']*=exp(-pow(3/2*$dist/$caracs['perception'], 2));

				$effet_info['effet']=round($effet_info['effet']);
				if ($effet_info['effet']==0) {
					$effet_info['effet']-= $cible_caracs[str_replace("aspire_","",$type_effet)];
				}
				$effet=$cible_caracs[str_replace("aspire_","",$type_effet)]-$effet_info['effet'];
				maj_carac($cible[0], str_replace("aspire_","",$type_effet), $effet);

				$effet=$caracs[str_replace("aspire_","",$type_effet)]+$effet_info['effet'];
				maj_carac($perso_id, str_replace("aspire_","",$type_effet), $effet);
				if ($type_effet=='aspire_pv') {
					if (($caracs['pv']+$effet_info['effet'])<= 0 ) {
						suicide($perso_id);
					}
					if (($cible_caracs['pv']-$effet_info['effet'])<= 0 ) {
						meurtre($perso_id, $cible[0], 'mort_sort');
						$nb_mort = ++$_SESSION['mort']['nb'];
						$_SESSION['mort']['nom'][$nb_mort] = $cible_info['nom'];
						$_SESSION['mort']['id'][$nb_mort] = $cible_info['id'];
					}
				}
			} elseif ($type_effet=='aspire_pv') {
				if ($cible_carac_noalter['pv']>0) {
					if (strpos($effet_info['effet'], '%')) {
						$effet_info['effet']=substr_replace($effet_info['effet'],'',strpos($effet_info['effet'], '%'));
						$effet_info['effet']*=$cible_carac_noalter['pv_max']/1000;
					}

					$effet_info['effet']+=$effet_info['effet']*$bonus_global/100;

					$dist = distance($centre, $cible_pos, $carte_pos);
					$effet_info['effet']*=exp(-pow(3/2*$dist/$caracs['perception'], 2));

					$effet_info['effet']=round($effet_info['effet']);
					if (($cible_carac_noalter['pv']-$effet_info['effet'])<= 0 ) {
						$effet=$caracs[str_replace("aspire_","",$type_effet)]+$effet_info['effet'];
						maj_carac($perso_id, str_replace("aspire_","",$type_effet), $effet);
						if (($caracs['pv']+$effet_info['effet'])<= 0 ) {
							suicide($perso_id);
						}

						destruction($perso_id, $cible);
						$_SESSION['destruction']['nb'] += 1 ;
						$nb_dest = $_SESSION['destruction']['nb'];
						$_SESSION['destruction']['type'][$nb_dest] = $cible[1] ;
						$_SESSION['destruction']['id'][$nb_dest] = $cible[0] ;
						calcul_xp($perso_id, $cible[0], 'destruction_'.$cible[1], 1, 0);
					} else {
						$effet=$perso_caracs[str_replace("aspire_","",$type_effet)]+$effet_info['effet'];
						maj_carac($perso_id, str_replace("aspire_","",$type_effet), $effet);

						if (($caracs['pv']+$effet_info['effet'])<= 0 ) {
							suicide($perso_id);
						}

						$sql = "UPDATE damier_".$type." SET pv=".min($cible_carac_noalter['pv']-$effet_info['effet'], $cible_caracs_max['pv'])." WHERE id=".$cible[0];
						$res = mysql_query($sql)or die (mysql_error());
						if (($cible_carac_noalter['pv']+$effet_info['effet'])>=$cible_carac_noalter['pv']) {
							calcul_xp($perso_id, $cible[0], 'repare_'.$cible[1], ($cible_carac_noalter['pv']<$cible_caracs_max['pv']), 0);
						} else calcul_xp($perso_id, $cible[0], 'attaque_'.$cible[1], 1, 0);
					}
				}
			}
			break;
		case 'aspire_att':
		case 'aspire_def':
			if ($cible[1]=='allie' || $cible[1]=='ennemi' || $cible[1]=='both' || $cible[1]=='persos') {

				$cible_carac_alter_mag=recup_carac_alter_mag($cible[0], str_replace("aspire_","alter_",$type_effet));
				$perso_carac_alter_mag=recup_carac_alter_mag($perso_id, str_replace("aspire_","alter_",$type_effet));

				$effet_info['effet']+=$effet_info['effet']*$bonus_global/100;

				$dist = distance($centre, $cible_pos, $carte_pos);
				$effet_info['effet']*=exp(-pow(3/2*$dist/$caracs['perception'], 2));

				$_SESSION['event_effect']['val'][$nb]=$effet_info['effet'];

				$effet=$cible_carac_alter_mag[str_replace("aspire_","alter_",$type_effet)]-$effet_info['effet'];
				maj_carac_alter_mag($cible[0], str_replace("aspire_","alter_",$type_effet), $effet);

				$effet=$perso_carac_alter_mag[str_replace("aspire_","alter_",$type_effet)]+$effet_info['effet'];
				maj_carac_alter_mag($perso_id, str_replace("aspire_","alter_",$type_effet), $effet);

			}
			break;

		case 'suicide':
			$cible_carac_noalter=recup_carac($cible[0], array('pa'));
			if (($cible_caracs['pa']+$cible_caracs['pa_dec']/10)>0) {
				maj_carac($cible[0], 'pa',$cible_carac_noalter['pa']-$cible_caracs['pa']);
				$new_pv=$caracs['pv']-abs($caracs['recup_pv'])*4*lance_ndp(1,3);
				maj_carac($cible[0], 'pv', $new_pv);
				if ($new_pv<=0) {
					meurtre($perso_id, $cible[0], 'suicide');
					$sql = "SELECT nb_suicide AS nb FROM persos WHERE id=$cible_id";
					$res = mysql_query($sql)or die (mysql_error());
					$nb  = mysql_fetch_array ($res);
					$nb_suicide = $nb['nb'];
					$nb_suicide++;

					$sql = "UPDATE persos SET `nb_suicide`=$nb_suicide WHERE id=$cible_id";
					$res = mysql_query($sql)or die (mysql_error());
					$_SESSION['reussite']=1;
					$nb_mort = ++$_SESSION['mort']['nb'];
				} else $_SESSION['reussite']=0;
			}
			break;

		case 'sprint':
			$carac_mouv = recup_carac($cible[0], array('mouv') );
			if (($caracs['pa']+$caracs['pa_dec']/10)>0) {
				$gain = gainxp($nbpa,'sprint');
				maj_carac($cible[0], 'pa', $perso_carac_noalter['pa']-$caracs['pa']);
				maj_carac($cible[0], 'mouv', $carac_mouv['mouv']+floor($caracs['pa']+$caracs['pa_dec']/10));
				maj_carac($perso_id, 'px', $caracs['px']+$gain);
				maj_carac($perso_id, 'pi', $caracs['pi']+$gain);
				$_SESSION['gain_xp']['att']+=$gain;
			}
			break;
                case 'dissipation': // Dissipe tout les effets magiques
			if ($cible[1]=='allie' || $cible[1]=='ennemi' || $cible[1]=='both' || $cible[1]=='persos') {
                                mysql_query("DELETE FROM caracs_alter_mag   WHERE perso_id = '".$cible[0]."' AND cassable = 1") or die (mysql_error());
			}
			break;                            
		default :
			if ($cible[1]=='allie' || $cible[1]=='ennemi' || $cible[1]=='both' || $cible[1]=='persos') {
				$cible_carac_alter_mag=recup_carac_alter_mag($cible[0], $type_effet);
				$cible_carac = calcul_caracs($cible[0]);
				$_SESSION['event_effect']['val'][$nb]=$effet_info['effet'];

				if (strpos($effet_info['effet'], '%')) {
					$effet_info['effet']=substr_replace($effet_info['effet'],'',strpos($effet_info['effet'], '%'));
					if ($type_effet=="alter_att" || $type_effet=="alter_def") {
						$cible_caracs_max[str_replace("alter_","",$type_effet)] = $cible_caracs_max["des"]*2;
					}
					$effet_info['effet']=$effet_info['effet']*$cible_caracs_max[str_replace("alter_","",$type_effet)]/100;
				}

				$dist = distance($centre, $cible_pos, $carte_pos);

				if ($effet_info['effet']==0) {
					$effet_info['effet']-= $cible_carac[str_replace("alter_","",$type_effet)];
				}

				$effet_info['effet']+=$effet_info['effet']*$bonus_global/100;

				$effet_info['effet']*=exp(-pow(3/2*$dist/$caracs['perception'], 2));

				$effet_info['effet']=round($effet_info['effet']);
				$effet=$cible_carac_alter_mag[$type_effet]+$effet_info['effet'];
				maj_carac_alter_mag($cible[0], $type_effet, $effet);
			}
			break;

	}

}

function search_pos($liste, $id, $type) {
	$inc=1;
	while (isset($liste['id'][$inc])) {
		if ($liste['id'][$inc] == $id && $liste['type'][$inc] == $type) {
			return $inc;
		}
		$inc++;
	}
	return 0;
}

// Fonction d'activation du tour
function activ_tour($id, $force_activ=false) {
	$perso_id= $_SESSION['persos']['id'][$id];

	$sql = "SELECT date_tour, superieur_id, grade_id, race_id, galon_id FROM persos WHERE id = '".$perso_id."'";
	$resultat = mysql_query ($sql) or die (mysql_error());
	$persos = mysql_fetch_array ($resultat);
	$race =$persos['race_id'];
	$grade =$persos['grade_id'];
	$galon_id =$persos['galon_id'];
	$sup_id = $persos['superieur_id'];
	$datetour = $persos['date_tour'];
	$datetour = strtotime($datetour);
	$camp = recup_camp($race);
	$type = recup_type($race);
	$camp_plan = recup_camp_plan($camp);

	$time = time();

	if ($time >= $datetour || $force_activ) {

		maj_esq_mag($perso_id, 2, 0);

		//On log l'activation
		$sqlAt = "INSERT INTO at_triche (utilisateur_id,perso_id,type,date)
			VALUES
			('".$_SESSION['utilisateur']['id']."','".$perso_id."',3,CURRENT_TIMESTAMP());";
		mysql_query($sqlAt);

		// Recupération des infos sur le plan
		$plan = 0; // Valeur par défaut
		$nv_tour = 23; // Valeur par défaut
		$sql="SELECT * FROM damier_persos
			INNER join cartes ON cartes.id=damier_persos.carte_id
			WHERE perso_id='$perso_id'";
		$resultat = mysql_query ($sql) or die (mysql_error());
		if ($pos = mysql_fetch_array ($resultat)) {
			$plan = $pos['carte_id'];
			$nv_tour = $pos['dla'];
		}

		//-- Tour en 47heures par defaut (pour la beta 2, tour à 23h)
		//$nouveautour = time()+(47*3600);
		$nouveautour = $time + 3600*$nv_tour;
		$nouveautour = date('Y-m-d H:i:s',$nouveautour);

		//------------ Information a collecter pour les caracs

		$caracs_pures = calcul_caracs_no_alter($perso_id);

		$caracs		 = calcul_caracs($perso_id);

		$sql = "SELECT*FROM caracs_alter WHERE perso_id = '".$perso_id."'";
		$resultat = mysql_query ($sql) or die (mysql_error());
		$caracs_alter_affil = mysql_fetch_array ($resultat);

		$caracs_alter = calcul_caracs_alter($perso_id);

		$recup_pv			= $caracs_pures['recup_pv'];
		$niv_recup_pv		= $caracs_pures['niv_recup_pv'];
		$max_recup_pv		= carac_max ($race, $grade, 'recup_pv', $niv_recup_pv, $perso_id, $galon_id);
		$malus_def			= $caracs_pures['malus_def'];
		$niv_pv				= $caracs_pures['niv_pv'];
		$max_pv				= carac_max ($race, $grade, 'pv', $niv_pv, $perso_id, $galon_id);
		$niv_pa				= $caracs_pures['niv_pa'];
		$max_pa				= carac_max ($race, $grade, 'pa', $niv_pa, $perso_id, $galon_id);
		$niv_mouv			= $caracs_pures['niv_mouv'];
		$max_mouv			= carac_max ($race, $grade, 'mouv', $niv_mouv, $perso_id, $galon_id);
		$current_pv			= $caracs['pv'];
		$px					= $caracs_pures['px'];
		$pi					= $caracs_pures['pi'];
		$pa_dec				= $caracs_pures['pa_dec'];
		$pa_dec_max			= carac_max ($race, $grade, 'pa_dec', $niv_pa, $perso_id, $galon_id);
		$niv_force			= $caracs_pures['niv_force'];
		$max_force			= carac_max ($race, $grade, 'force', $niv_force, $perso_id, $galon_id);
		$niv_perception		= $caracs_pures['niv_perception'];
		$max_perception		= carac_max ($race, $grade, 'perception', $niv_perception, $perso_id, $galon_id);
		$maj_des			= $caracs_pures['maj_des'];
		$res_mag			= carac_max ($race, $grade, 'res_mag', 0, $perso_id, $galon_id);

		$nbpa				= $max_pa + ($pa_dec_max/10);

		$rang 				= calcul_rang($px);

		if ($grade < 2) {
			grade_up_xp($perso_id,$race,$grade,$px);
		}

                if($galon_id == 0) {
                    // Si le galon vaut 0, on attribue le galon 1
                    change_galon($perso_id, 1);
                }

                //@TODO
                //$ef = new EwoForum($_SESSION['utilisateur']['id']);
                //$ef->setRank($perso_id);
                //$ef->setRaceGrade($pseudo,$race,$grade,$galon_id);

		//-- Calcule de la recup des pv / tour
		$recup_pv = $caracs['recup_pv'] ;
		$pv = $current_pv + floor($max_pv*$recup_pv/100);
		if ($pv > $max_pv) {
			$pv = $max_pv;
		} elseif ($pv <=0) {
			desincarne($perso_id);
			//ajouter un event de mort
			$current_pv=0;
		} elseif ($current_pv <= 5 && $current_pv > 0) {
			ajouteMedaille(MEDAILLE_SURVIVANT, $perso_id);
		}

		if ($grade == 5) {
			$gain = gainxp($nbpa,'activation_g5');
		} elseif ($grade == 4) {
			$gain = gainxp($nbpa,'activation_g4');
		} else {
			$gain = gainxp($nbpa,'activation');
		}
		$px+=$gain;
		$pi+=$gain;

		//-- Calcul de la recup de malus /tour
		$tab_recup_malus = recup_malus($recup_pv, $max_pv);

		$malus_def = $malus_def - ($tab_recup_malus["recup_fixe"] + $tab_recup_malus["recup_bonus"]);
		if ($malus_def < 0) {
			$malus_def = 0;
		}

		//Ajout de la décimale pour les PA
		$pa_dec += $pa_dec_max;
		if ($pa_dec>=10) {
			$max_pa += 1;
			$pa_dec %= 10 ;
		}

		//Mise en place des bonus de plan
		raz_alter_plan($perso_id);
		if ($camp==3 || $camp==4) {
			$sql="SELECT * FROM damier_persos WHERE perso_id='$perso_id'";
			$resultat = mysql_query ($sql) or die (mysql_error());
			if ($pos = mysql_fetch_array ($resultat)) {
				$ok=false;
				if ($pos['carte_id']==$camp_plan) {
					$ok=true;
				}
				if ($ok) {
					$depth=abs($pos['pos_y']);
					// Bonus +1 (puis +2, +3, etc) dans la caractéristique :
					// Bonus score de défense : P/5
					maj_alter_plan($perso_id, 'alter_def', floor($depth/5));
					// Bonus score d'attaque : P/9
					maj_alter_plan($perso_id, 'alter_att', floor($depth/9));
					// Bonus mouvement : P/9
					maj_alter_plan($perso_id, 'alter_mouv', floor(($depth+10)/9));

					// Bonus +5 % (puis +10 %, +15 %, etc) dans la caractérisque :
					// Bonus dégâts: P/5
					maj_alter_plan($perso_id, 'alter_force', 5*floor($depth/5));
					// Bonus récup' pv : P/5
					maj_alter_plan($perso_id, 'alter_recup_pv', 5*floor($depth/5));
				}
			}
		}

		//---------------------------------------------------------

		// Mise à jour des Bene/Male
		$alter_pa = $caracs_alter_affil['alter_pa'] ;
		if ($alter_pa < 0)
			$alter_pa = $alter_pa + 1;
		elseif ($alter_pa > 0)
			$alter_pa = $alter_pa - 1;

		$alter_pv = $caracs_alter_affil['alter_pv'] ;
		if ($alter_pv < 0)
			$alter_pv = $alter_pv + 10;
		elseif ($alter_pv > 0)
			$alter_pv = $alter_pv - 10;

		$alter_recup_pv = $caracs_alter_affil['alter_recup_pv'] ;
		if ($sup_id==0) {
			if ($alter_recup_pv < 0) {
				if ($alter_recup_pv == -5) {
					$alter_recup_pv = 0;
				} else	$alter_recup_pv = $alter_recup_pv + 10;
			} elseif ($alter_recup_pv > 0) {
				if ($alter_recup_pv == 5) {
					$alter_recup_pv = 0;
				} else	$alter_recup_pv = $alter_recup_pv - 10;
			}
		} else {
			if ($alter_recup_pv < 5) {
				if ($alter_recup_pv == -10) {
					$alter_recup_pv = -5;
				} elseif ($alter_recup_pv == 0) {
					$alter_recup_pv = 5;
				} else $alter_recup_pv = $alter_recup_pv + 10;
			} elseif ($alter_recup_pv > 5)
				if ($alter_recup_pv == 10) {
					$alter_recup_pv = 5;
				} else	$alter_recup_pv = $alter_recup_pv - 10;
		}
		$alter_niv_mag = $caracs_alter_affil['alter_niv_mag'] ;
		if ($alter_niv_mag < 0)
			$alter_niv_mag = $alter_niv_mag + 1;
		elseif ($alter_niv_mag > 0)
			$alter_niv_mag = $alter_niv_mag - 1;

		$alter_mouv = $caracs_alter_affil['alter_mouv'] ;
		if ($alter_mouv < 0)
			$alter_mouv = $alter_mouv + 1;
		elseif ($alter_mouv > 0)
			$alter_mouv = $alter_mouv - 1;

		$alter_def = $caracs_alter_affil['alter_def'] ;
		if ($alter_def < 0)
			$alter_def = $alter_def + 1;
		elseif ($alter_def > 0)
			$alter_def = $alter_def - 1;

		$alter_att = $caracs_alter_affil['alter_att'] ;
		if ($alter_att < 0)
			$alter_att = $alter_att + 1;
		elseif ($alter_att > 0)
			$alter_att = $alter_att - 1;

		$alter_force = $caracs_alter_affil['alter_force'] ;
		if ($alter_force < 0)
			$alter_force = $alter_force + 10;
		elseif ($alter_force > 0)
			$alter_force = $alter_force - 10;

		$alter_perception = $caracs_alter_affil['alter_perception'] ;
		if ($alter_perception < 0)
			$alter_perception = $alter_perception + 1;
		elseif ($alter_perception > 0)
			$alter_perception = $alter_perception - 1;

		mysql_query("UPDATE caracs_alter SET alter_pa = $alter_pa,
				alter_recup_pv = $alter_pv,
				alter_recup_pv = $alter_recup_pv,
				alter_niv_mag = $alter_niv_mag,
				alter_mouv = $alter_mouv,
				alter_def = $alter_def,
				alter_att = $alter_att,
				alter_force = $alter_force,
				alter_perception = $alter_perception,
				alter_effet = 0,
				alter_esq_mag = 0,
				alter_res_mag = 0
				WHERE perso_id = '".$perso_id."'") or die (mysql_error());

                // Décrémentation des effets de sorts
                mysql_query("UPDATE caracs_alter_mag SET nb_tour = (nb_tour - 1) WHERE perso_id = '".$perso_id."' AND nb_tour > 0");

		//Annulation de tous les effets de sorts de temps 1
		mysql_query("DELETE FROM caracs_alter_mag   WHERE perso_id = '".$perso_id."' AND nb_tour = 0") or die (mysql_error());

		//--------------------------------------------------------------

		$sql="SELECT * FROM damier_persos WHERE perso_id='$perso_id'";
		$resultat = mysql_query ($sql) or die (mysql_error());
		if ($pos = mysql_fetch_array ($resultat)) {
			$perso['pos_x']=$pos['pos_x'];
			$perso['pos_y']=$pos['pos_y'];
			$perso['plan']=$pos['carte_id'];

			//Gain d'xp en cas d'activation dans un plan ennemi
			switch($camp) {
				case 1 :
					if ($perso['plan']==2 || $perso['plan'] == 3) {
						$gain = gainxp($nbpa,'activation_plan', $perso['pos_y']);
						$px+=$gain;
						$pi+=$gain;
					}
					break;
				case 2 :
					if ($perso['plan']==2 || $perso['plan'] == 3) {
						$gain = gainxp($nbpa,'activation_plan', $perso['pos_y']);
						$px+=$gain;
						$pi+=$gain;
					}
					break;
				case 3 :
					if ($perso['plan']==2) {
						$gain = gainxp($nbpa,'activation_plan', $perso['pos_y']);
						$px+=$gain;
						$pi+=$gain;
					}
					break;
				case 4 :
					if ($perso['plan']==3) {
						$gain = gainxp($nbpa,'activation_plan', $perso['pos_y']);
						$px+=$gain;
						$pi+=$gain;
					}
					break;
			}

			//Malus Bouclier Humain pour les parias et ailés
			if ($camp != 1) {
				//Vérification de la présence de boucliers
				//zone de recherche
				$sql="SELECT * FROM cartes WHERE id='".$perso['plan']."'";
				$resultat = mysql_query ($sql) or die (mysql_error());
				$carte = mysql_fetch_array ($resultat);

				$x_min_carte = $carte['x_min'];
				$x_max_carte = $carte['x_max'];

				$y_min_carte = $carte['y_min'];
				$y_max_carte = $carte['y_max'];

				$y_min 	= $perso['pos_y']-18;
				$y_max  = $perso['pos_y']+21;
				$x_min  = $perso['pos_x']-21;
				$x_max  = $perso['pos_x']+18;

				$rchch_x ="(pos_x>='$x_min' AND pos_x<='$x_max')";
				$rchch_y ="(pos_y>='$y_min' AND pos_y<='$y_max')";

				$malus_mouv = 0;

				$sql = "SELECT*FROM damier_bouclier WHERE (carte_id=".$perso['plan']." AND $rchch_x AND $rchch_y)";
				$resultat = mysql_query ($sql) or die (mysql_error());
				while ($bouclier=mysql_fetch_array($resultat)) {
					$malus_base=0;
					$dist_max=1;
					switch($bouclier['type_id']) {
						case 1 :
							if ($perso['pos_x']>=($bouclier['pos_x']-5) AND $perso['pos_x']<=($bouclier['pos_x']+5) AND $perso['pos_y']>=($bouclier['pos_y']-5) AND $perso['pos_y']<=($bouclier['pos_y']+5)) {
								$malus_base=20;
								$dist_max=5;
							}
							break;
						case 2 :
							if ($perso['pos_x']>=($bouclier['pos_x']-9) AND $perso['pos_x']<=($bouclier['pos_x']+10) AND $perso['pos_y']>=($bouclier['pos_y']-10) AND $perso['pos_y']<=($bouclier['pos_y']+9)) {
								$malus_base=25;
								$dist_max=11;
							}
							break;
						case 3 :
							if ($perso['pos_x']>=($bouclier['pos_x']-14) AND $perso['pos_x']<=($bouclier['pos_x']+16) AND $perso['pos_y']>=($bouclier['pos_y']-16) AND $perso['pos_y']<=($bouclier['pos_y']+14)) {
								$malus_base=30;
								$bouclier['pos_x']++;
								$bouclier['pos_y']--;
								$dist_max=15;
							}
							break;
						case 4 :
							$malus_base=40;
							$bouclier['pos_x']++;
							$bouclier['pos_y']--;
							$dist_max=21;
							break;
					}
					$dist=distance($perso, $bouclier, $perso['plan']);
					$malus_base*=exp(-pow(6/5*$dist/$dist_max, 4));
					$malus_mouv-=round($malus_base);
				}
				$malus_mouv= max($malus_mouv, -50); //Valeur max de mouvs pouvant être retirés

				$max_mouv=$malus_mouv*$max_mouv/100+$max_mouv;
			}
		}
		//-- MORT
		//-- Si le personnage est mort on update la table carac du perso mort pour le remettre sur pied.
		if ($current_pv <= 0) {
			mysql_query("UPDATE caracs SET pv = '$max_pv' /2, `force` = '$max_force' /2, perception = '$max_perception' /2 WHERE perso_id = '".$perso_id."'") or die (mysql_error());
			//-- Spawn géré sur le damier.
                        
                        //Annulation de tous les effets de sorts dissipé à la mort
                        mysql_query("DELETE FROM caracs_alter_mag WHERE perso_id = '".$perso_id."' AND dissipe_mort = 1") or die (mysql_error());                        
                        
		} else {
			// "Fonction" définissant les malus de mouv suite aux blessures après activation mais AVANT régénération. Kaz le 31/10/2011
			// Si moins de 10 % de PV, -75% de mouvs.
			// Si moins de 25 % de PV, -50% de mouvs.
			if ($current_pv <= 0.10*$max_pv) {
				$max_mouv -= floor(0.75*$max_mouv);
			} elseif ($current_pv <= 0.25*$max_pv) {
				$max_mouv -= floor(0.50*$max_mouv);
			}
			//-- NOUVEAU TOUR
			//-- Mise a jour de la date du nouveau tour
			mysql_query("UPDATE persos SET date_tour = '$nouveautour' WHERE id = '".$perso_id."'") or die (mysql_error());
			//-- Mise a jour des caracs du personnage lors d'un nouveau tour.
			mysql_query("UPDATE caracs SET  pv = '$pv', recup_pv = '$max_recup_pv', pa = '$max_pa', pa_dec='$pa_dec', mouv = '$max_mouv', px = '$px', pi = '$pi', malus_def = '$malus_def', `force` = '$max_force', perception = '$max_perception', maj_des=0, res_mag='$res_mag'
					WHERE perso_id = '".$perso_id."'") or die (mysql_error());

			grade_up_xp($perso_id,$race,$grade,$px);

			$_SESSION['persos']['date_tour'][$id]=$nouveautour;

		}
		return true;
	}
	else return false;
}

function gainxp($nbpa,$action,$param = null) {	
	global $gain;
	

	// Calculs
	$coef = 1; // Le coeficiant est multiplié au gain
	$modificateur = 0; // le modificateur est ajouté au gain
	
	if(isset($gain[$action]['param'])) {
		$nom_param = $gain[$action]['param'];
		switch($nom_param) {
			case 'plan':
				// $param = coordonné Y
				$modificateur = ceil(abs($param) / $gain[$action][$nom_param]);
				break;

			case 'esquiver': 
				// $param = différence de rang
				$modificateur = $param + $gain[$action][$nom_param];
				$coef = 0;
				break;			

			case 'frappe_sort': 
				$modificateur = $param * $gain[$action][$nom_param];
				break;		

			case 'attaque':
				$modificateur = $param * $gain[$action][$nom_param];
				break;
				
			case 'kill':
				//gain pour le tué
				//param = rang cible - rang tueur
				if($param < 0) { // la cible est de rang inférieure
					$modificateur = ceil(($param * $param)*1.5 + $gain[$action][$nom_param]);
				} else { // la cible est de rang supérieur ou égale !
					$modificateur = $gain[$action]['val'] - $param * $gain[$action][$nom_param];
				}
				$coef = 0;
				break;		

			case 'fullpv':
				$coef = 1;
				$modificateur = 0;
				break;				

			case 'vacance':
				$coef = $param;
				break;
		}	
		
	}
	
	// Les bornes définissent les limites de l'aléatoire
	// Exemple: borne_min = 5
	//			borne_max = 8
	//			coef = 1, modificateur = -2
	//			le gain sera entre 3 et 6
	if(isset($gain[$action]['borne_min'])) {
		$calculxp = rand($gain[$action]['borne_min']*$coef+$modificateur,$gain[$action]['borne_max']*$coef+$modificateur);
	} else {
		$calculxp = $gain[$action]['val']*$coef+$modificateur;
	}
	
	// Les cap définissent les valeurs minimal et maximal d'un gain
	// Le cap est calculé avant la modulation par PA
	if(isset($gain[$action]['cap_min'])) {
		$cap_min = $gain[$action]['cap_min'];
	} else {
		$cap_min = $gain['cap_min']['val'];
	}
	if($calculxp < $cap_min) {
		$calculxp = $cap_min;
	}
	
	if(isset($gain[$action]['cap_max']) && $calculxp > $gain[$action]['cap_max']) {
		$calculxp = $gain[$action]['cap_max'];
	}

	if(isset($gain[$action]['modulation_pa'])) {
		
		$calculxp = ($calculxp / $nbpa) * 2;
		
		// Si la modulation donne un resultat à virgule (exemple: 2.666...7), il y 
		//   aura un rand entre la valeur entière supérieur et inférieur
		$gain_min = max(floor($calculxp),1);
		$gain_max = max(ceil($calculxp),1);
		$calculxp = rand($gain_min,$gain_max);
	}
	
		
	return $calculxp;
}
?>
