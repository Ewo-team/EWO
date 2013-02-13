<?php
/**
 * Inventaire - Drop
 *
 * Permet de lacher un objet sur le damier
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package jeu/inventaire
 */
/*
session_start();
$root_url = "../..";
include ($root_url."/conf/master.php");
include ($root_url."/jeu/fonctions.php");
/*-- Connexion requise --*/
if (ControleAcces('utilisateur',0) == false){
	echo "null";exit;
}*/
/*-----------------------*/

// Paramètres de connexion à la base de données
/*bdd_connect('ewo');

	$cenPos['pos_x'] = $_SESSION['inventaire']['pos_x'];
	$cenPos['pos_y'] = $_SESSION['inventaire']['pos_y'];
	$cenPos['carte_id'] = $_SESSION['inventaire']['carte_id'];
	
$cenPos = array ('pos_x'=> $cenPos['pos_x'], 'pos_y'=> $cenPos['pos_y'], 'carte_id' => $cenPos['carte_id']);
$position = dropPos($cenPos);
if($position != NULL){

	$cenPos['perso_id'] = $_SESSION['inventaire']['perso_id'];
	
 	$sqlinvent="SELECT * FROM inventaire WHERE id='".$_GET['id_inventaire']."' AND perso_id='".$cenPos['perso_id']."'";
	$resultat = mysql_query ($sqlinvent) or die (mysql_error());
	$inventaire = mysql_fetch_array ($resultat);
	
	if($inventaire == true){
	
	//-- supression de l'artefact de l'inventaire
	$sql_inventaire = "DELETE FROM inventaire WHERE id = '".$_GET['id_inventaire']."'";
 	mysql_query($sql_inventaire);

 	//-- array position X et Y dans $position
 	//-- array information artefact (nom,description,rarete, ...)
 	$sqlcase="SELECT * FROM case_artefact WHERE id='".$inventaire['case_artefact_id']."'";
	$resultat = mysql_query ($sqlcase) or die (mysql_error());
	$arte = mysql_fetch_array ($resultat);

	//-- Incarnation de l'artefact sur le damier
	$sql1="INSERT INTO damier_artefact (id, icone_artefact_id, pos_x, pos_y, pv, carte_id) VALUE ('', '".$inventaire['case_artefact_id']."', '".$position['pos_x']."', '".$position['pos_y']."','".$inventaire['pv']."' ,'".$cenPos['carte_id']."')";
	mysql_query($sql1) or die (mysql_error());
 	
	$arte['position'] = $position;
	
	$infoencode = json_encode($arte);
	
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');
	
	echo $infoencode;
	
	}else{
		echo 'null';
	}
}else{
	echo 'null';
}
*/

/**
 * Fonction renvoyant une position aléatoire libre
 * autour du perso pour lacher un objet
 * cenPos est la position centrale
 * retourne NULL s'il n'y a pas de case libre
 * @param array $censPos Position de l'utilisateur sur un plan
 */
function dropPos ($cenPos){
/*
$nb_valid_pos = 0;

$pos_x_perso = $cenPos['pos_x'];
$pos_y_perso = $cenPos['pos_y'];
$carte_pos 	 = $cenPos['carte_id'];

$sql="SELECT * FROM cartes WHERE id='$carte_pos'";
$resultat = mysql_query ($sql) or die (mysql_error());
$carte = mysql_fetch_array ($resultat);


$x_min_carte = $carte['x_min'];
$x_max_carte = $carte['x_max'];

$y_min_carte = $carte['y_min'];
$y_max_carte = $carte['y_max'];

    for($inci=1 ; $inci<=3 ; $inci++){
        for($incj=1 ; $incj<=3 ; $incj++){
            if($inci==1)
                {
                if(($pos_y_perso + 1)>($y_max_carte) && $carte['circ'][1]){
                    $pos_y_perso_new    = $y_min_carte+1;
                    }
                    else {
                            $pos_y_perso_new = $pos_y_perso + 1;
                        }
                }elseif($inci==3)
                    {
                    if(($pos_y_perso - 1)<=($y_min_carte) && $carte['circ'][1]){
                        $pos_y_perso_new    = $y_max_carte;
                        }
                        else {
                                $pos_y_perso_new = $pos_y_perso - 1;
                            }
                        
                    }
                    else $pos_y_perso_new = $pos_y_perso;
            if($incj==1)
                {
                if(($pos_x_perso - 1)<=($x_min_carte) && $carte['circ'][0]){
                    $pos_x_perso_new    = $x_max_carte;
                    }
                    else {
                            $pos_x_perso_new = $pos_x_perso - 1;
                        }
                }elseif($incj==3)
                    {
                    if(($pos_x_perso + 1)>($x_max_carte) && $carte['circ'][0]){
                        $pos_x_perso_new    = $x_min_carte+1;
                        }
                        else {
                                $pos_x_perso_new = $pos_x_perso + 1;
                            }
                    }
                    else $pos_x_perso_new = $pos_x_perso;
            // Le personnage est hors carte si la nouvelle position d?passe la taille maximale de la carte
            // et si la carte n'est pas infinie sur le c?t? concern?.
            $hors_carte = (($pos_x_perso_new<$x_min_carte) && !$carte['infini'][0]);
            $hors_carte = $hors_carte || ($pos_x_perso_new>$x_max_carte && !$carte['infini'][1]);
            $hors_carte = $hors_carte || ($pos_y_perso_new<$y_min_carte && !$carte['infini'][2]);
            $hors_carte = $hors_carte || ($pos_y_perso_new>$y_max_carte && !$carte['infini'][3]);
		
			if (!$hors_carte){
				$carte_ok=true;
				}
				else $carte_ok=false;
			
			if($carte_ok){

					$new_pos['plan'] 	= $carte_pos;
					$new_pos['pos_x']	= $pos_x_perso_new;
					$new_pos['pos_y']	= $pos_y_perso_new;

					$carte_ok=pos_is_free($new_pos);
					if($carte_ok){
						$nb_valid_pos						+= 1 ;
						$reponse['pos_x'][$nb_valid_pos]	=  $pos_x_perso_new;
						$reponse['pos_y'][$nb_valid_pos]	=  $pos_y_perso_new;
						$reponse['nb_valid_pos']			=  $nb_valid_pos;
						}
				}
			}
		}
	//echo $nb_valid_pos;
	$val_res 	= rand(1, $nb_valid_pos);
	if($nb_valid_pos != 0){
		$retour['pos_x']	= $reponse['pos_x'][$val_res];
		$retour['pos_y']	= $reponse['pos_y'][$val_res];
		//return $retour['pos_x'].":".$retour['pos_y'];
		return $retour;
		}
		else return NULL;*/
}
?>
