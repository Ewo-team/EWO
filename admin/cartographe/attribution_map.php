<?php

use \conf\ConnecteurDAO as ConnecteurDAO;

include 'header.php';

$file = $_SESSION['cartographe']['raw'];

$db = ConnecteurDAO::getInstance();

if(isset($_GET['map'])) {
	$id = $_GET['map'];

    $sql = "UPDATE cartes SET nom_decors='$file' WHERE id=$id";
	$db->exec($sql);
} 

$sql = "SELECT * FROM cartes";
$res = $db->query($sql);

$lignes = $res->fetchAll();

echo '<table>
<tr>
	<th>Nom du plan</th>
	<th>Map associée</th>
	<th></th>
</tr>';		
foreach($lignes as $carte) {
	echo '<tr>';
	echo '<td>'.$carte['nom'].'</td>';
	echo '<td>'.$carte['nom_decors'].'</td>';
	echo '<td><a href="attribution_map.php?map='.$carte['id'].'">Attribuer!</a></td>';
}
echo '</table>';  