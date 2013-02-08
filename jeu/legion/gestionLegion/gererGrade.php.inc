<?php

/**
 * Gestion des légions en interne : voir la liste des membres
 *
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 */
include_once('class/LegionConfig.php.inc');
include_once('class/ManagerFactory.php.inc');
include_once('class/LegionDroits.php.inc');
require_once('class/LegionDAO.php.inc');

use legions\LegionDAO as LegionDAO;
use legions\ManagerFactory as ManagerFactory;
use legions\LegionConfig as LegionConfig;
use legions\DroitManager as DroitManager;
use legions\LegionDroits    as LegionDroits;

$droitManager = new DroitManager($_SESSION['persos']['faction']['droits'][$id]);
$droits = LegionDroits::droitsFromPersoId($id);

$factory = new ManagerFactory();
$legions = $factory->get(LegionConfig::$bddId[$_SESSION['persos']['camp'][$id]]);
$legion = $legions->getLegions($_SESSION['persos']['faction']['id'][$id]);

$grades = $legion->getListGrades();

//Ajout d'une nouveau grade
if (isset($_POST['newNom']) && $_POST['newNom'] != '' &&
        isset($_POST['newDescr'])) {
    if (isset($_POST['d']))
        $d = $_POST['d'];
    else
        $d = array();
    $droits = array(0, 0, 0, 0, 0, 0, 0, 0);
    foreach ($d as $k)
        $droits[$k] = 1;
    $d = '';
    foreach ($droits as $k)
        $d .= $k;

    $idGrade = -1;
    foreach ($grades as $g)
        if ($g->getGrade_id() > $idGrade)
            $idGrade = $g->getGrade_id();
    $idGrade++;

    $nom = trim($_POST['newNom']);
    $nom = str_replace('\t', '', $nom);
    $nom = str_replace('\n', '', $nom);
    $nom = str_replace('\r', '', $nom);
    $nom = addslashes($nom);
    $nom = htmlentities($nom);

    $descr = trim($_POST['newDescr']);
    $descr = str_replace('\t', '', $descr);
    $descr = str_replace('\n', '', $descr);
    $descr = str_replace('\r', '', $descr);
    $descr = addslashes($descr);
    $descr = htmlentities($descr);

    $sql = LegionDAO::getInstance();
    $query_alter = '
            INSERT INTO `faction_grades`
            (`grade_id`,`faction_id`,`nom`,`description`,`droits`)
            VALUES
            ("' . $idGrade . '",' . $_SESSION['persos']['faction']['id'][$id] . ',"' . $nom . '","' . $descr . '","' . $d . '");
        ';

    $sql->exec($query_alter);
}

//Suppression d'un grade
//on garde le grade 1 : le chef
if (isset($_GET['d'])) {
    if (is_numeric($_GET['d']) && $_GET['d'] > 1 && array_key_exists($_GET['d'], $grades)) {
        $grade = $grades[$_GET['d']];
        $sql = LegionDAO::getInstance();
        $query_alter = '
            DELETE FROM `faction_grades`
            WHERE
                faction_id = ' . $_SESSION['persos']['faction']['id'][$id] . ' AND
                grade_id = ' . $grade->getGrade_id() . ';
        ';
        $sql->exec($query_alter);
        $query_alter = '
            UPDATE `faction_membres`
            SET
                `faction_grade_id` = NULL
            WHERE
                faction_id = ' . $_SESSION['persos']['faction']['id'][$id] . ' AND
                faction_grade_id = ' . $grade->getGrade_id() . ';
        ';
        $sql->exec($query_alter);
    }
    header('Location: index.php?p=' . $_GET['p'] . '&mat=' . $_GET['mat'] . '&s=' . $_GET['s']);
    die();
}

$grades = $legion->getListGrades();

$js->addLib('jquery-ui');
$js->addScript('legion/grade');
$js->addVariables('matVar', $_GET['mat']);

echo '
    <table id="tableGrade" class="legionPresTable" style="width:90%;margin:auto;" border="1">
        <tr>
            <th rowspan="2" width="30%">Nom</th>
            <th rowspan="2">Description</th>
            <th colspan="8">Droits [<a href="#lex">*</a>]</th>
        </tr>
        <tr>
            <th title="', LegionDroits::$droitsAff[0], '">d1</th>
            <th title="', LegionDroits::$droitsAff[1], '">d2</th>
            <th title="', LegionDroits::$droitsAff[2], '">d3</th>
            <th title="', LegionDroits::$droitsAff[3], '">d4</th>
            <th title="', LegionDroits::$droitsAff[4], '">d5</th>
            <th title="', LegionDroits::$droitsAff[5], '">d6</th>
            <th title="', LegionDroits::$droitsAff[6], '">d7</th>
            <th title="', LegionDroits::$droitsAff[7], '">d8</th>
        </tr>
    ';
$i = 0;
foreach ($grades as $grade) {
    $c = '';
    $dis = false;
    if ($i == 0)
        $dis = true;
    if ($i++ % 2 == 1) {
        $c = 'style="background-color:white;"';
    }
    echo '
        <tr ', $c, '>
            <td style="text-align:left"><img src="img/modif.png" class="cursor nom" alt="modifier"/> <span class="e' . $grade->getGrade_id() . '">' . $grade->getNom() . '</span></td>
            <td style="text-align:left"><img src="img/modif.png" class="cursor descr"  alt="modifier"/> <span class="e' . $grade->getGrade_id() . '">' . $grade->getDescription() . '</span></td>
            ';
    $k = 0;
    foreach ($grade->getDroitsArray() as $r) {
        if ($dis)
            echo '
            <td>
                <input type="checkbox" ', $c, ' class="cursor" checked="checked" disabled="disabled"/>
            </td> ';
        else {
            $c = '';
            if($r == 1)
                $c = 'checked="yes"';
            echo '
            <td  class="e' . $grade->getGrade_id() . '">
                <input type="checkbox" ', $c, ' class="cursor" id="c', $i, $k, '" ', $r, '
                    onchange="valideModif(\'c', $i, $k, '\',', (2 + $k++), ');"/>
            </td>
                        ';
        }
    }
    if (!$dis)
        echo '
                <td>
                    <img src="img/suppr.png" alt="suppr" title="Supprimer"  class="cursor"
                        onclick="sup(', $grade->getGrade_id(), ');"/>
                </td>';
    echo '
        </tr>';
}
echo '
        <tr>
            <form id="addForm" method="post" action="index.php?p=1&amp;mat=', $_GET['mat'], '&amp;s=', $_GET['s'], '">
                <td style="text-align:left"><input type="text" value="" id="newNom" name="newNom"/></td>
                <td style="text-align:left"><input type="text" value="" id="newDescr" name="newDescr"/></td>
                <td><input type="checkbox" id="c1" name="d[]" value="0"/></td>
                <td><input type="checkbox" id="c2" name="d[]" value="1"/></td>
                <td><input type="checkbox" id="c3" name="d[]" value="2"/></td>
                <td><input type="checkbox" id="c4" name="d[]" value="3"/></td>
                <td><input type="checkbox" id="c5" name="d[]" value="4"/></td>
                <td><input type="checkbox" id="c6" name="d[]" value="5"/></td>
                <td><input type="checkbox" id="c7" name="d[]" value="6"/></td>
                <td><input type="checkbox" id="c8" name="d[]" value="7"/></td>
                <td><img src="img/add.png" alt="add" class="cursor" title="Ajouter" onclick="add()"/></td>
            </form>
        </tr>
    </table><br />
    <hr class="legionHr" style="margin-left:45px;"/><br /><br />
    <table id="lex" class="legionPresTable" style="width:90%;margin:auto;" border="1">
        <tr>
            <th>d1</th>
            <th>d2</th>
            <th>d3</th>
            <th>d4</th>
            <th>d5</th>
            <th>d6</th>
            <th>d7</th>
            <th>d8</th>
        </tr>
        <tr>
            <td>', LegionDroits::$droitsAff[0], '</td>
            <td>', LegionDroits::$droitsAff[1], '</td>
            <td>', LegionDroits::$droitsAff[2], '</td>
            <td>', LegionDroits::$droitsAff[3], '</td>
            <td>', LegionDroits::$droitsAff[4], '</td>
            <td>', LegionDroits::$droitsAff[5], '</td>
            <td>', LegionDroits::$droitsAff[6], '</td>
            <td>', LegionDroits::$droitsAff[7], '</td>
        </tr>
    </table>';
?>
