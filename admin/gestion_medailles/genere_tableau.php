<?php
session_start(); 
$root_url = "./../..";
//-- Header --
require_once("../AdminDAO.php");
/*-- Connexion at ou admin requise --*/
//ControleAcces('admin',1);

$conn = AdminDAO::getInstance();
$result = $conn->SelectMedailleListe();

$content = '<?php ' . PHP_EOL . PHP_EOL;

foreach($result as $ligne) {
	$nom = strtoupper(preg_replace("/[^[:alnum:]]/","",$ligne['image']));
	$content .= 'define("MEDAILLE_'.$nom.'",'.$ligne['id'].');' . PHP_EOL;
}

$content .= PHP_EOL . '?>';

$filename = $root_url."/conf/tableau_medaille.php";

//if (is_writable($filename)) {

    // Dans notre exemple, nous ouvrons le fichier $filename en mode d'ajout
    // Le pointeur de fichier est placé à la fin du fichier
    // c'est là que $somecontent sera placé
    if (!$handle = fopen($filename, 'w+')) {
         echo "Impossible d'ouvrir le fichier ($filename)";
         exit;
    }

    // Ecrivons quelque chose dans notre fichier.
    if (fwrite($handle, $content) === FALSE) {
        echo "Impossible d'écrire dans le fichier ($filename)";
        exit;
    }

    echo "L'écriture du contenu dans le fichier ($filename) a réussi";

    fclose($handle);

//} else {
//	echo "Le fichier $filename n'est pas accessible en écriture";
//}