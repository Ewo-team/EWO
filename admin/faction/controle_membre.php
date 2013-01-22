<?php
function Controle_membre($acces, $persos_id, $faction_id){
//-- Paramètres de connexion à la base de données

$inc = 1;
while($inc <= $_SESSION['persos']['inc'] && $_SESSION['persos']['id'][$inc]!=$persos_id && $_SESSION['persos']['faction']['id'][$inc] != $faction_id){
	$inc++;
	}
if($inc > $_SESSION['persos']['inc']){
	return 0;
	}
	else {
	
	if (isset($_SESSION['persos']['faction']['droits'][$inc])){
		$droits  = $_SESSION['persos']['faction']['droits'][$inc];
		}
		else $droits  = array(0,0,0,0,0,0,0,0);

	if ($acces == '0'){		//virer le chef
			if ($droits[0] == 1){
				return 1;
			}
			else return 0;
		}elseif($acces == '1'){	//virer les bras droits
			if ($droits[1] == 1){
				return 1;
			}
			else return 0;
		}elseif($acces == '2'){	//virer les autres gens
			if ($droits[2] == 1){
				return 1;
			}
			else return 0;
		}elseif($acces == '3'){	//gestion des grades
			if ($droits[3] == 1){
				return 1;
			}
			else return 0;
		}elseif($acces == '4'){//inviter des membres
			if ($droits[4] == 1){
				return 1;
			}
			else return 0;
		}elseif($acces == '5'){//baler les membres
			if ($droits[5] == 1){
				return 1;
			}
			else return 0;
		}elseif($acces == '6'){ //GPS
			if ($droits[6] == 1){
				return 1;
			}
			else return 0;
		}elseif($acces == '7'){	//Voir la liste des membres, les infos générales sur la faction
			if ($droits[7] == 1){
				return 1;
			}
			else return 0;
		}
	
	}

}
?>