<?php
/**
 * Récupérer les grades/galons d'un perso
 * @author benjamin herbomez <benjamin.herbomez@gmail.com>
 */
require('ref.php.inc');
require_once($root_url.'conf/master.php');

bdd_connect('ewo');
    
if(!isset($_GET['mat']) || !is_numeric($_GET['mat']))
    die('{"error":"pas de mat"}');

$query = 'SELECT `grade_id` as grade,`galon_id` as galon FROM `persos` WHERE `id` = '.$_GET['mat'].';';

$result = mysql_query($query);
$value = mysql_fetch_assoc($result);
mysql_close();
echo json_encode($value);