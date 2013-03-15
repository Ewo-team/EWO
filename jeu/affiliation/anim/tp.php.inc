<?php

namespace jeu\affiliation;

use \persos\eventManager\eventManager as eventManager;
use \persos\eventManager\eventFormatter as eventFormatter;

/**
 * Téléporter un affilié
 * @author Benjamin Herbomez <benjamin.herbomez@gmail.com>
 */

require_once(SERVER_ROOT.'/persos/fonctions.php');


$caracs = calcul_caracs($_GET['mat']);

$caracs_max = caracs_base_max($_GET['mat'],$_SESSION['persos']['race'][$id],$_SESSION['persos']['grade'][$id]);

$errMsg     = '';
$notifMsg   = '';
if(isset($_POST['mat']) && isset($_POST['x']) && isset($_POST['y']) &&
        is_numeric($_POST['mat']) && is_numeric($_POST['x']) && is_numeric($_POST['y']) &&
        $caracs['pv'] > 0){

    $mat = $_POST['mat'];
    $x = $_POST['x'];
    $y = $_POST['y'];

    if($_SESSION['persos']['camp'][$id] == 3)
        $carte = 3;
    else
        $carte = 2;

    $sql = AffiliationDAO::getInstance();
    if($sql->isSup($_GET['mat'], $_POST['mat'])){
        


        $stmt = $sql->prepare('SELECT * FROM `cartes` WHERE id=:id');
        $stmt->execute(array(':id' => $carte));
        $carte = $stmt->fetch(\PDO::FETCH_OBJ);

        if($x >= $carte->x_min && $x <= $carte->x_max &&
           $y >= $carte->y_min && $y <= $carte->y_max){

            require_once(SERVER_ROOT.'/jeu/fonctions.php');
            //var_dump($caracs);
            $admin_mode = true;
            if(!set_pos($mat, $x, $y, $carte->id)){
               $errMsg = 'Erreur lors de la téléportation (place déjà prise ?)';
            }
            else{
                $notifMsg = 'téléportation effectuée';

                $eventManager = new EventManager();
                    $event = $eventManager->createEvent('tp');
                    $event->setSource($_GET['mat'], 'perso');
                    $event->setAffected($mat, 'perso');

                    $event->infos->addPrivateInfo('x', $x);
                    $event->infos->addPrivateInfo('y', $y);
                    $event->infos->addPrivateInfo('plan', $carte->nom);

                $pv = $caracs['pv'];
                $pv -= ceil($caracs_max['pv']/3);
                if($pv <= 0){
                    $perte = $sql->delSuicidePx($_GET['mat']);
                    maj_carac($_GET['mat'], 'pv', $pv);
                    $sql->desincarnPerso($_GET['mat']);

                    $notifMsg .= ' <strong>vous êtes mort de fatigue</strong>';
                    $event->setState(tp::TP_WITH_DEATH);
                    $event->infos->addPrivateInfo('perte', $perte);
                }
                else{
                    $event->setState(tp::TP);
                    maj_carac($_GET['mat'], 'pv', $pv);
                }

            }
        }
        else
            $errMsg = 'position en hors carte';

     }
    else{
        $errMsg = 'Ce personnage n\'est pas votre affili&eacute;';
    }
}
if($caracs['pv'] > 0){
?>

<h2>Téléportation</h2>

<form method="post"
      action="index.php?mat=<?php echo $_GET['mat']?>&amp;p=<?php echo $_GET['p']?>&amp;a=<?php echo $_GET['a']?>"
      id="form" onsubmit="return tp();">
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
            </th>
        </tr>
        <tr>
            <td style="text-align:center;">
                Nom :
            </td>
            <td >
                <input type="text" name="search" id="search"  />
                <input type="hidden" name="mat" id="mat" value="null"/>
            </td>
        </tr>
        <tr>
            <td colspan="4"><hr class="affiHr" /></td>
        </tr>
        <tr>
            <td>X : <input type="text" name="x" class="mandatory" /></td>
            <td>Y : <input type="text" name="y" class="mandatory" /></td>
            <td>Plan :
            <?php
                if($_SESSION['persos']['camp'][$id] == 3)
                    echo 'Célestia';
                else
                    echo 'Céféris';
            ?>
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><button id="buttonTp" type="submit">téléporter</button></td>
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
<?php
}
else{
    echo '<strong>Vous devez être vivant pour téléporter quelqu\'un</strong>';
}
