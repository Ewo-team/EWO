<?php
/**
 * Gestion des demandes d'affiliation
 *
 * @author Benjamin Herbomez <benjamin.herbomez@gmail.com>
 */

require_once($root_url.'/affiliation/class/Superieurs.php.inc');
require_once($root_url.'/affiliation/class/DemandeSup.php.inc');

$sups   =   Superieurs::getAvailableSup($_SESSION['persos']['camp'][$id]);
$sup    =   $sups[$_GET['mat']];

$demandeSup = new DemandeSup($sup->id);
$demandes   = $demandeSup->getDemandes();
$action     = null;


if (isset($_GET['acc'])){
    $action = 'acc';
}
else if (isset($_GET['ref'])){
    $action = 'ref';
}

if($action != null){
    foreach($demandes as $key => $demande){
        if($demande->getPersoId() == $_GET[$action]){
            $sql = AffiliationDAO::getInstance();
            if($action == 'acc'){
                 $sql->acceptAffiliationQuery($_GET['mat'], $demande->getSupNom(),$demande->getPersoId(),
                         $_SESSION['persos']['camp'][$id]);
                 if(in_array($demande->getPersoId(), $_SESSION['persos']['id'])){
                     $persoId = array_keys($_SESSION['persos']['id'], $demande->getPersoId());
                     $persoId = $persoId[0];
                     $_SESSION['persos']['superieur'][$persoId] =  $_GET['mat'];
                 }
            }
            else{
                $sql->refuseAffiliationQuery($_GET['mat'], $demande->getSupNom(),$demande->getPersoId());
            }
            //clean and break;
            unset($demandes[$key]);
            break;
        }
    }
    /*$sup = $sups[$_POST['aff']];
    AffiliationDAO::getInstance()->setSup($_GET['mat'],$sup->id);
    $_SESSION['persos']['superieur'][$id] =  $sup->id;
    BonusAffiManager::process($sup->camp);
    header('location: index.php');
    die();*/
}
?>
<table>
    <thead>
        <tr>
            <th></th>
            <th>Nom</th>
            <th>mat</th>
            <th>baler</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($demandes as $demande){
            echo '
        <tr>
            <td>
                <img src="'.$root_url.'/images/',icone_persos($demande->getPersoId()),'" alt="avatar"
                    title="Avatar de ',$demande->getPersoNom(),'" /></td>
            <td>',$demande->getPersoNom(),'</td>
            <td>',$demande->getPersoId(),'</td>
            <td><a href="../messagerie/index.php?id=',$_GET['mat'],'&dest=',$demande->getPersoId(),'">baler</a></td>
            <td><a href="index.php?mat=',$_GET['mat'],'&amp;p=',$_GET['p'],'&amp;a=',
                    $a,'&amp;acc=',$demande->getPersoId(),'">accepter</a></td>
            <td><a href="index.php?mat=',$_GET['mat'],'&amp;p=',$_GET['p'],'&amp;a=',
                    $a,'&amp;ref=',$demande->getPersoId(),'">refuser</a></td>
        </tr>';
        }
        ?>
    </tbody>
</table>
