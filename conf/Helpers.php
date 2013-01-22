<?php

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
}

?>
