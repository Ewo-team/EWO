<?php

/**
 *
 * @author Benjamin Herbomez <benjamin.herbomez@gmail.com>
 */

require_once($root_url.'/affiliation/class/AffiliationDAO.php.inc');

$sql = AffiliationDAO::getInstance();
$i = 0;
$j = 5;

echo '

    <h2>Liste des affiliés</h2>
    <table>
        <tr>';

foreach($sql->getAffiList($_GET['mat']) as $perso){
    if($i%$j == 0){
        echo '<tr>';
    }
    echo '<td style="border-right:0;"><img src="',$root_url,'/images/',icone_persos($perso->id),'" alt="icon perso"/></td>
    <td  style="border-left:0;">
    <a href="../event/liste_events.php?id=',$perso->id,'">',$perso->nom,'</a>
    (<a href="../messagerie/index.php?id=',$_GET['mat'],'&dest=',$perso->id,'">',$perso->id,'</a>)</td>';
     if($i%$j == $j-1){
        echo '</tr>';
    }
    ++$i;
}
if($i % $j == 0)
    echo '</tr>';
echo '</table>';