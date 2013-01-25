<?php
include_once('class/LegionConfig.php.inc');
include_once('class/ManagerFactory.php.inc');
include_once('class/NameManager.php.inc');
include_once('class/DemandeUtilisateur.php.inc');
include_once('class/DemandeUtilisateur.php.inc');
include_once('fonctions.php.inc');

use legions\ManagerFactory as ManagerFactory;
use legions\LegionConfig as LegionConfig;
use legions\NameManager as NameManager;
use legions\DemandeUtilisateur as DemandeUtilisateur;
use legions\DroitManager as DroitManager;
use legions\LegionDroits as LegionDroits;
use legions\LegionDAO as LegionDAO;

$droitManager = new DroitManager($_SESSION['persos']['faction']['droits'][$id]);
$droits = LegionDroits::droitsFromPersoId($id);

$factory = new ManagerFactory();
$legions = $factory->get(LegionConfig::$bddId[$_SESSION['persos']['camp'][$id]]);
$legion = $legions->getLegions($_SESSION['persos']['faction']['id'][$id]);

if (isset($_POST['descr'])) {
    $query_alter = '
        UPDATE `factions`
        SET description = ?
        WHERE
            id = ' . $legion->getId();
    $sql = LegionDAO::getInstance();
    $sql->prepare($query_alter);
    $sql->executePreparedStatement(null, array($_POST['descr']));

    $legion->setDescr($_POST['descr']);
}
$extensions_valides = array('jpg', 'jpeg', 'gif', 'png');
if (isset($_FILES['new_img']) && $_FILES['new_img']['error'] == 0) {

    $extension_upload = strtolower(substr(strrchr($_FILES['new_img']['name'], '.'), 1));
    if (in_array($extension_upload, $extensions_valides)) {
        $image_sizes = getimagesize($_FILES['new_img']['tmp_name']);
        $coef = 1;
        if ($image_sizes[0] > 300 || $image_sizes[1] > 300) {
            if($image_sizes[0] > $image_sizes[1])
            $coef = 300/$image_sizes[0]; else
            $coef = 300/$image_sizes[1];
        }
        if($extension_upload == 'jpg' || $extension_upload == 'jpeg')
            $source = imagecreatefromjpeg($_FILES['new_img']['tmp_name']);
        else if($extension_upload == 'png')
            $source = imagecreatefrompng($_FILES['new_img']['tmp_name']);
        else if($extension_upload == 'gif')
            $source = imagecreatefromgif($_FILES['new_img']['tmp_name']);

        $destination = imagecreatetruecolor($image_sizes[0]*$coef, $image_sizes[1]*$coef);

        imagecopyresampled($destination, $source,
        0, 0, 0, 0,
        $image_sizes[0]*$coef, $image_sizes[1]*$coef, $image_sizes[0], $image_sizes[1]);
        imagepng($destination, 'imgs/'.$legion->getId().'.png');

        $query_alter = '
                UPDATE `factions`
                SET logo_url = ""
                WHERE
                    id = ' . $legion->getId();
            $sql = LegionDAO::getInstance();
            $sql->prepare($query_alter);
            $sql->executePreparedStatement(null, array());

            $legion->setLogoUrl('');
    }
}
else if(isset($_POST['new_file_dist']) && $_POST['new_file_dist'] != ''){
    $extension_upload = strtolower(substr(strrchr($_POST['new_file_dist'], '.'), 1));
    if (in_array($extension_upload, $extensions_valides)) {
        $size = getimagesize($_POST['new_file_dist']);
        if($size[0] <= 300 && $size[1] <= 300){
            $query_alter = '
                UPDATE `factions`
                SET logo_url = ?
                WHERE
                    id = ' . $legion->getId();
            $sql = LegionDAO::getInstance();
            $sql->prepare($query_alter);
            $sql->executePreparedStatement(null, array($_POST['new_file_dist']));

            $legion->setLogoUrl($_POST['new_file_dist']);
        }
    }
}


if(isset($_POST['new_site']) &&
        preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $_POST['new_site'])){
    $query_alter = '
        UPDATE `factions`
        SET site_url = ?
        WHERE
            id = ' . $legion->getId();
    $sql = LegionDAO::getInstance();
    $sql->prepare($query_alter);
    $sql->executePreparedStatement(null, array($_POST['new_site']));
    $legion->setSiteUrl($_POST['new_site']);
}

    $js->addLib('ckeditor/ckeditor');
    $js->addLib('ckeditor/adapters/jquery');
    $js->addLib('ckeditor/sample');
    $js->addScript('legion/gererInfos');

?>

<link href="../js/ckeditor/sample.css" rel="stylesheet" type="text/css" />

<?php echo '
<fieldset>
    <legend>Description</legend>
    <form method ="post" action="index.php?p=', $_GET['p'], '&amp;mat=', $_GET['mat'], '&amp;s=', $_GET['s'], '">
        <textarea id="descr" name="descr" rows="15" style="width:99%;margin:auto;">', nl2br(stripslashes($legion->getDescr())), '</textarea>
        <input type="submit" value="modifier" />
    </form>
</fieldset>

' ?>
<fieldset>
    <legend>
        Blason
    </legend>
<?php
    if ($legion->getLogoUrl() != '') {
        echo '<br /><img src="', $legion->getLogoUrl(), '" alt="image legion"/>';
    }
    else if (file_exists('imgs/' . $legion->getId() . '.png')) {
        echo '<br /><img src="imgs/' , $legion->getId() , '.png" alt="image legion"/>';
    }
echo '
    <form method ="post" action="index.php?p=', $_GET['p'], '&amp;mat=', $_GET['mat'], '&amp;s=', $_GET['s'], '"
        enctype="multipart/form-data"><br />
        Uploader : <input type="file" name="new_img"/> <br />
        <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
        Distant : <input type="text"  size="50" name="new_file_dist"/> <br /><br />
        <input type="submit" value="modifier" />
    </form>
        ';
?>
</fieldset>
<fieldset>
    <legend>Liens</legend>
<?php

echo '
    <form method ="post" action="index.php?p=', $_GET['p'], '&amp;mat=', $_GET['mat'], '&amp;s=', $_GET['s'], '">
        <label for="link1">Lien 1</label> : <input type="text" name="link1" id="link1" size="50"/> <br />
		<label for="link2">Lien 2</label> : <input type="text" name="link2" id="link2" size="50"/> <br />
		<label for="link3">Lien 3</label> : <input type="text" name="link3" id="link3" size="50"/> <br />
		<label for="link4">Lien 4</label> : <input type="text" name="link4" id="link4" size="50"/> <br />
		<label for="link5">Lien 5</label> : <input type="text" name="link5" id="link5" size="50"/> <br />
		<br />
		<input type="submit" value="modifier"/>
    </form>
        ';
?>
</fieldset>
