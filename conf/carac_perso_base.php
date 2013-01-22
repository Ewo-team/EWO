<?php
/**
 * Configuration
 *
 *	Carac de base
 *
 * @author Simonet Fabrice <aigleblanc@ewo.fr>
 * @version 1.0
 * @package conf
 */

// Un tableau du type $carac['carac'] ou meme $carac['race']['carac'] ne serait pas mieux ?

if ($perso_race == 3){
//-- Ange
$px = 0;
$pi = 0;
$pv = 200;
$pv_max = 200;
$recup_pv = 5;
$malus_def = 0;
$recup_malus_def = 80;
$po = 500;
$niv = 0;
$mouv = 6;
$mouv_max = 6;
$pa = 2;
$pa_max = 2;
$des_attaque = 4;
$des_max = 9;
$force = 20;
$perception = 5;
$res_mag=0;

}elseif($perso_race == 4){
//-- Demon
$px = 0;
$pi = 0;
$pv = 200;
$pv_max = 200;
$recup_pv = 5;
$malus_def = 0;
$recup_malus_def = 80;
$po = 500;
$niv = 0;
$mouv = 6;
$mouv_max = 6;
$pa = 2;
$pa_max = 2;
$des_attaque = 4;
$des_max = 9;
$force = 20;
$perception = 5;
$res_mag=0;

}elseif($perso_race == 1){
//-- Humain
$px = 0;
$pi = 0;
$pv = 80;
$pv_max = 80;
$recup_pv = 20;
$malus_def = 0;
$recup_malus_def = 40;
$po = 80;
$niv = 0;
$mouv = 6;
$mouv_max = 6;
$pa = 2;
$pa_max = 2;
$des_attaque = 4;
$des_max = 7;
$force = 10;
$perception = 4;
$res_mag=0;

}

?>
