<?php
/**
 * Fonction de geston des passwords
 *
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 1.0
 * @package conf
 * @category password
 */
 
/**
 * Génération d'un nouveau mot de passe
 * @param $length Par defaut 9 sinon celui passé en param
 * @return $password Mot de passe retourné par la fonction de la taille demandé
 */
function generatePassword ($length = 9)
{
  $password = "";
  $possible = "0123456789abcdefghjkmnpqrstuvwxyzABCDFGHJKMNOPQRSTVWXYZ"; 
  $i = 0; 
  while ($i < $length) { 
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
    if (!strstr($password, $char)) { 
      $password .= $char;
      $i++;
    }
  }
	return $password;
}

?>
