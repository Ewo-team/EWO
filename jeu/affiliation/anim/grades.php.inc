<?php

/**
 * Gérer les grades des affiliés
 * @author Benjamin Herbomez <benjamin.herbomez@gmail.com>
 */

require_once($root_url.'/affiliation/class/AffiliationDAO.php.inc');
require_once($root_url.'/persos/fonctions.php');
require_once($root_url.'/jeu/fonctions.php');

$errMsg     = '';
$notifMsg   = '';


if(isset($_POST['mat']) && isset($_POST['grade_perso']) && isset($_POST['galon_perso']) &&
   is_numeric($_POST['mat']) && is_numeric($_POST['grade_perso']) && is_numeric($_POST['galon_perso'])){
    $sql = AffiliationDAO::getInstance();
    if($sql->isSup($_GET['mat'], $_POST['mat'])){
        $grade = $_POST['grade_perso'];
        $galon = $_POST['galon_perso'];
        if($grade < 0)
            $grade = 0;
        else if($grade > 5)
            $grade = 5;
        if($galon < 0)
            $galon = 0;
        else if($galon > 4)
            $galon = 4;
        
        $query = 'SELECT race_id as race FROM persos WHERE id = '.$_POST['mat'];

	$result = mysql_query($query) or die (mysql_error());
	$race = mysql_fetch_object($result);
        $race = $race->race;
        
	change_race_grade($_POST['mat'], $race, $grade);
        change_galon($_POST['mat'], $galon);
        
	$event_grade_up = new EventsManager($_POST['mat'],"grade_up");
        $event_grade_up->addEvent('grade',$grade);
        $event_grade_up->commitEvent();
        $sql->updateGradeGalon($_POST['mat'], $grade, $galon);
        $notifMsg = 'Vous avez bien chang&eacute; le grade et le galon de ce perso';
    }
    else{
        $errMsg = 'Ce personnage n\'est pas votre affili&eacute;';
    }
}
?>

<h2>Grades</h2>

<form method="post"
      action="index.php?mat=<?php echo $_GET['mat']?>&amp;p=<?php echo $_GET['p']?>&amp;a=<?php echo $_GET['a']?>"
      id="form">
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
                <input type="hidden" name="mat" id="mat" value="null" onchange="grade();"/>
            </td>
        </tr>
        <tr>
            <td colspan="4"><hr class="affiHr" /></td>
        </tr>
        <tr>
            <td>Grade : <input type="text" name="grade_perso" class="mandatory" onchange="controlGrade()"/></td>
            <td>Galon : <input type="text" name="galon_perso" class="mandatory" onchange="controlGalon()" /></td>
            <td><button id="buttonGrade" type="submit">Modifier</button></td>
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
	var autocomplete_url =  "../ajax/affi.php?sup=<?php echo $_GET['mat'];?>&";

    -->
</script>
<link rel="stylesheet" href="js/jquery-ui-1.8.17.custom.css" type="text/css" />
