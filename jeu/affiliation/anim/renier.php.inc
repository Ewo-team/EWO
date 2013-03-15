<?php

namespace jeu\affiliation;

/**
 * Renier un affilié
 * @author Benjamin Herbomez <benjamin.herbomez@gmail.com>
 */

$errMsg     = '';
$notifMsg   = '';

if(isset($_POST['mat']) && is_numeric($_POST['mat'])){
    $sql    = AffiliationDAO::getInstance();
    if($sql->isSup($_GET['mat'], $_POST['mat'])){
        //Vérifions si le perso est affilié
        $query_alter  = '
            SELECT
                id,
                nom
            FROM `persos`
            WHERE
                `superieur_id` = :supId AND `id` = :id;';

            $stmt = $sql->prepare($query_alter);
            $stmt->execute(array(
                ':supId' => $_GET['mat'],
                ':id'    => $_POST['mat']));
            $perso = $sql->fetch($stmt,\PDO::FETCH_OBJ);
            if($perso){
                $sql->renier($_GET['mat'], $perso->id, $_SESSION['persos']['camp'][$id]);
                $notifMsg = 'Vous avez renié '.$perso->nom;

                if(in_array($perso->id, $_SESSION['persos']['id'])){
                     $persoId = array_keys($_SESSION['persos']['id'], $perso->id);
                     $persoId = $persoId[0];
                     $_SESSION['persos']['superieur'][$persoId] =  null;
                 }
            }
            else{
                $errMsg = 'Ce personnage n\'est pas votre affilié';
            }
    }
    else{
        $errMsg = 'Ce personnage n\'est pas votre affili&eacute;';
    }
}
?>


<h2>Bénir/maudir</h2>

<form method="post" action="index.php?mat=<?php echo $_GET['mat']?>&amp;p=<?php echo $_GET['p']?>&amp;a=<?php echo $_GET['a']?>" id="form">
    <table class="table-noStyle" >
        <?php
            if($errMsg != '')
                echo '
        <tr>
            <td colspan="4" style="color:red;">',$errMsg,'</td>
        </tr>';
            if($notifMsg != '')
                echo '
        <tr>
            <td colspan="4" style="color:green;">',$notifMsg,'</td>
        </tr>';
        ?>
        <tr>
            <th colspan="4" style="text-align:center;">
                Rechercher
                <hr class="affiHr"/>
            </th>
        </tr>
        <tr>
            <td></td>
            <td>
                Nom
            </td>
            <td>
                : <input type="text" name="search" id="search" />
                <input type="hidden" name="mat" id="mat" value="null"/>
            </td>
            <td><input type="submit" value="Renier"/></td>
        </tr>
    </table>
</form>

<?php
    $js->addLib('jquery');
    $js->addLib('jquery-ui');
    $js->addLib('validation/jquery.validate.min');
    $js->addLib('validation/localization/messages_fr');
    $js->addScript('autocomplete');
?>
<script type="text/javascript">
    <!--
	var autocomplete_url =  "../../ajax/affi.php?sup=<?php echo $_GET['mat'];?>&";
    -->
</script>
<link rel="stylesheet" href="js/jquery-ui-1.8.17.custom.css" type="text/css" />
