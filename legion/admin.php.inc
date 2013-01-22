<?php
/**
 * Administration des légions
 *
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 */

$admin = $_SESSION['utilisateur']['droits'][1];
$anim  = $_SESSION['utilisateur']['droits'][2];

if(!$admin && !$anim){
    header('Location: index.php');
    exit();
}

$root_url = '../';

include_once('class/NameManager.php.inc');
include_once('class/LegionDAO.php.inc');

use legions\NameManager as NameManager;
use legions\LegionDAO   as LegionDAO;

$nameManager = new NameManager();

//Récupération des races
$query_alter = 'SELECT id, nom FROM `camps`';
$sql = LegionDAO::getInstance();
$stmt = $sql->query($query_alter);
$races = array();
while($race = $sql->fetch($stmt,\PDO::FETCH_OBJ)){
    $races[$race->id] = $race->nom;
}

$modif_message = false;

//Gestion de l'ajout
if(isset($_POST['name']) && isset($_POST['race']) && isset($_POST['type']) && isset($_POST['align']) && isset($_POST['chef_mat'])
    && is_numeric($_POST['race']) && is_numeric($_POST['type']) && is_numeric($_POST['align']) && is_numeric($_POST['chef_mat'])){
    $query_alter = '
        INSERT INTO `factions`
            (`nom`,`race`,`description`,`type`,`alignement`,`creation_date`,`site_url`,`logo_url`,`nature`)
            VALUES(:nom,:race,"",:type,:align,NOW(),"","","LEGION")';
    $stmt = $sql->prepare($query_alter);
    $stmt->execute(array(
        ':nom'      => $_POST['name'],
        ':race'     => $_POST['race'],
        ':type'     => $_POST['type'],
        ':align'    => $_POST['align']));

    $legionId = $sql->lastInsertId();


    $query_alter = '
        INSERT INTO `faction_grades`
            (`grade_id`,`faction_id`,`nom`,`description`,`droits`)
            VALUES
                (1,:legion_id,"Chef","","11111111"),
                (2,:legion_id,"Bras droit","","01111111"),
                (3,:legion_id,"Vieux Membre","","00101111"),
                (1,:legion_id,"Membre &agrave; l\'essai","","00000001");
        INSERT INTO `faction_membres`
            (`perso_id`,`faction_id`,`faction_grade_id`)
            VALUES
                (:chef_id,:legion_id,1);';
    $stmt = $sql->prepare($query_alter);
    $stmt->execute(array(':legion_id' => $legionId, ':chef_id' => $_POST['chef_mat']));
    $modif_message = 'Création effectuée';
}
//Supprimer :D
if(isset($_POST['del']) && isset($_POST['editId']) && is_numeric($_POST['editId']) && $admin){
    $query_alter = '
        DELETE FROM `faction_membres`
            WHERE `faction_id` =  :id;
        DELETE FROM `faction_grades`
            WHERE `faction_id` =  :id;
        DELETE FROM `factions`
            WHERE `id` = :id;';
    $stmt = $sql->prepare($query_alter);
    $stmt->execute(array(':id' => $_POST['editId']));
    $modif_message = 'Légion supprimée';
}
//Gestion de l'édition
else if(isset($_POST['editId']) && isset($_POST['editType']) && isset($_POST['editAlign']) && isset($_POST['editDescr']) &&
    is_numeric($_POST['editId']) &&is_numeric($_POST['editType']) && is_numeric($_POST['editAlign']) &&
    ($admin && isset($_POST['editName']) && trim($_POST['editName']) != '' || !$admin)){
    $query_alter = '
        UPDATE `factions`
        SET
            '.(($admin) ? '`nom`           = :name,' : '').'
            `description`   = :descr,
            `type`          = :type,
            `alignement`    = :align
        WHERE
            id = :id;';
    $stmt = $sql->prepare($query_alter);
    $params = array(
        ':id'       => $_POST['editId'],
        ':descr'    => $_POST['editDescr'],
        ':type'     => $_POST['editType'],
        ':align'    => $_POST['editAlign']);
    if($admin)
        $params[':name'] =  $_POST['editName'];
    $stmt->execute($params);
    $modif_message = 'Modification effectuée';
}
?>


<h2>Administration des légions</h2>

<table style="width:99%;border-collapse: collapse;" id="legionAdminTbl">
    <?php
        if($modif_message){
            echo '
    <tr>
        <td colspan="2" style="color:green;font-weight: bolder;text-align:center;">',$modif_message,'</td>
    </tr>';
        }
    ?>
    <tr>
        <td valign="top">
            <h3>Editer une légion</h3>
            <hr class="legionHr" style="margin-bottom:0px;"/><br />
            Recherche : <input type="text" name="search" id="search" style="margin:0px;font-size:0.9em;"/><br /><br />
            <hr class="legionHr" style="margin-bottom:0px;"/><br />
                <input type="hidden" name="searchId" id="searchId"/>
                <form method="post" action="index.php?p=<?php echo $_GET['p'];?>">
                    <table style="padding-left:10px;padding-right:10px;">
                        <input type="hidden" name="editId" id="editId"/>
                        <tr>
                            <th colspan="3" style="text-align:center;"> Modifier les informations </th>
                        </tr>
                        <tr>
                            <td>Nom</td>
                            <td> : </td>
                            <td>
                                <?php
                                if($admin)
                                    echo '<input type="text" id="editName" name="editName"/>';
                                else
                                    echo '<span id="editName"></span>';
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Race</td>
                            <td> : </td>
                            <td><span id="editRace"></span> </td>
                        </tr>
                        <tr>
                            <td><label for="editType">Type</label></td>
                            <td>:</td>
                            <td>
                                <select name="editType" id ="editType">
                                <?php
                                    foreach($nameManager->getTypes() as $id => $name){
                                        echo '
                                    <option value="',$id,'">',$name,'</option>';
                                    }
                                ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="editAlign">Alignement</label></td>
                            <td>:</td>
                            <td>
                                <select name="editAlign" id="editAlign">
                                <?php
                                    foreach($nameManager->getAlignements() as $id => $name){
                                        echo '
                                    <option value="',$id,'">',$name,'</option>';
                                    }
                                ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td> : </td>
                            <td>
                                <textarea id="editDescr" name="editDescr"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align:center;">
                                <br />
                                <input type="submit" value="Modifier" />
                            </td>
                        </tr>
                        <?php if($admin){ ?>
                        <tr>
                            <td colspan="3" style="text-align:center;">
                                <br />
                                <input type="button" id="del" value="Supprimer" onclick="return false;"/>
                                <div id="confirmDel" style="display:none;background-color: red;color:white;">
                                    <input type="submit" name="del" value="Confirmer" />
                                    <input type="button" id="cancel" value="Annuler" />
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                </table>
                </form>
        </td>
        <td valign="top" style="width:350px;">
            <h3>Ajouter une légion</h3>
            <div id="addDiv" style="display:block;">
                <form method="post" action="index.php?p=<?php echo $_GET['p'];?>">
                    <hr class="legionHr"/><br />
                    <table>
                        <tr>
                            <td> <label for="name">Nom</label> </td>
                            <td> : <input type="text" name="name" size="30"/></td>
                        </tr>
                        <tr>
                            <td><label for="race">Race</label></td>
                            <td>:
                                <select name="race" id="race" onchange="$('#chef').val('');">
                                <?php
                                    foreach($races as $id => $name){
                                        echo '
                                    <option value="',$id,'">',$name,'</option>';
                                    }
                                ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="type">Type</label></td>
                            <td>:
                                <select name="type">
                                <?php
                                    foreach($nameManager->getTypes() as $id => $name){
                                        echo '
                                    <option value="',$id,'">',$name,'</option>';
                                    }
                                ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="align">Alignement</label></td>
                            <td>:
                                <select name="align">
                                <?php
                                    foreach($nameManager->getAlignements() as $id => $name){
                                        echo '
                                    <option value="',$id,'">',$name,'</option>';
                                    }
                                ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td> <label for="chef">Chef</label> </td>
                            <td> :
                                <input type="text" name="chef" id="chef"/>
                                <input type="hidden" name="chef_mat" id="chef_mat" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:center;">
                                <br />
                                <input type="submit" value="Ajouter" />
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </td>
    </tr>
</table>

<?php 

    $js->addLib('jquery-ui');
    $js->addLib('ckeditor/ckeditor');
    $js->addLib('ckeditor/adapters/jquery');
    $js->addLib('ckeditor/sample');
    $js->addScript('legion/admin');
    $liste_races = '';
        foreach($races as $key => $val) {
            $liste_races .= 'races['.$key.'] = "'.$val.'";';    
        }
    
    $js->addVariables('races',$liste_races);
    $js->addVariables('is_admin',$admin);

?>
<link rel="stylesheet" href="js/jquery-ui-1.8.17.custom.css" type="text/css" />
<link href="../js/ckeditor/sample.css" rel="stylesheet" type="text/css" />

