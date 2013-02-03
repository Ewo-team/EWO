<?php

namespace conf;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Helpers
 *
 * @author Leo
 */
class Helpers {
/**
 *
 */
    public static function getSelectOption($liste, $type, $choix, $defaut){
                    $html 		= array();

                    $html[]	= '<select name="'.$type.'">';
                    $html[] = '<option value="">'.$defaut.'</option>';
                    foreach($liste as $label=>$value){
                            $selected = '';
                            if($value == $choix){
                                    $selected = ' selected="SELECTED" ';
                            }
                            $html[] = '<option value="'.$value.'"'.$selected.'>'.$label.'</option>';
                    }
                    $html[]	= '</select>';

                    return join(PHP_EOL,$html);
    }
    
    public static function Dice($valeur1,$valeur2) {
	$max = mt_getrandmax()+1.0;

	$u = sqrt(-2*log(mt_rand()/$max));
	$v = 2*M_PI*mt_rand()/$max;

	$a = round(sqrt(2.0*$valeur1/3.0)*$u*cos($v)+2.0*$valeur1);
	$b = round(sqrt(2.0*$valeur2/3.0)*$u*sin($v)+2.0*$valeur2);

	$x = min(max($valeur1,$a),3*$valeur1);
	$y = min(max($valeur2,$b),3*$valeur2);

	$dices = array($x,$y);

	return $dices;
    }
}

?>
