<?php

namespace jeu\affiliation;

/**
 *
 * @author Benjamin Herbomez <benjamin.herbomez@gmail.com>
 */


    $sups = Superieurs::getAvailableSup($_SESSION['persos']['camp'][$id]);

    include(SERVER_ROOT . '/persos/fonctions.php');
    
    $xp = recup_carac($_GET ['mat'], array('px'));
    $xp = $xp[0];
    if($xp < 100){
    	header('location: index.php');
    	die();
    }
    
    if(isset($_GET['aff']) && is_numeric($_GET['aff']) && array_key_exists($_GET['aff'], $sups)){
        $sup = $sups[$_GET['aff']];
        AffiliationDAO::getInstance()->addAffiliationQuery($sup->id, $_GET['mat']);
        header('location: index.php');
        die();
    }
?>
<h2>S'affilier avec <?php echo $_SESSION['persos']['nom'][$id]; ?></h2>
<table>
    <thead>
        <tr>
            <td></td>
            <th> Mat </th>
            <th> Nom </th>
            <th> Description </th>
        </tr>
    </thead>
    <tbody>
<?php
    foreach($sups as $sup){
        $sexe = 'ce truc';
        if($sup->sexe == 1)
            $sexe = 'lui';
        else if($sup->sexe == 2)
            $sexe = 'elle';
        echo '
        <tr>
            <td><img src="',SERVER_URL,'/images/',icone_persos($sup->id),'" alt="icon perso"/></td>
            <td><a href="',SERVER_URL,'/persos/event/?id=',$sup->id,'">',$sup->id,'</a></td>
            <td>',$sup->nom,'</td>
            <td>',$sup->descr,'</td>
            <td>
                <a href="index.php?p=',$_GET['p'],'&amp;mat=',$_GET['mat'],'&amp;a=',$_GET['a'],'&amp;aff=',$sup->id,'">
                    S\'affilier à ',$sexe,'
                </a>
            </td>
        </tr>';
    }
?>
    </tbody>
</table>