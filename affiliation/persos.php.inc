<?php

/**
 * Affichage de la liste des persos
 *
 * @author Benjamin Herbomez <benjamin.herbomez@gmail.com>
 */

    require($root_url.'/affiliation/class/Superieurs.php.inc');
    require($root_url.'/affiliation/class/DemandePerso.php.inc');
    $sups = Superieurs::getSups($_SESSION['persos']['superieur']);
?>
<h2>Affiliation de mes personnages</h2>
<table>
    <thead>
        <tr>
            <th colspan="5"> Perso </th>
            <th colspan="3"> Supérieur </th>
        </tr>
        <tr>
            <th></th>
            <th> Mat </th>
            <th> Nom </th>
            <th> Grade </th>
            <th> Galon </th>
            <th></th>
            <th> Nom </th>
            <th> Mat </th>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach($_SESSION['persos']['id'] as $k => $v){
            if($k == 'inc') //on échape cette valeur
                continue;

            $demandes   = new DemandePerso($v);
            $demande    = ($demandes->getDemandes());
            if(count($demande) == 0)
                $demande    = null;
            else
                $demande    = $demande[0];

            echo '
        <tr>
            <td><img src="',$root_url,'/images/',icone_persos($v),'" alt="icon perso"/></td>
            <td><a href="',$root_url,'/event/liste_events.php?id=',$v,'">',$v,'</a></td>
            <td>',$_SESSION['persos']['nom'][$k],'</td>
            <td>',$_SESSION['persos']['grade'][$k],'</td>
            <td>',$_SESSION['persos']['galon'][$k],'</td>';

            if($_SESSION['persos']['superieur'][$k] == null){
                if($demande != null){
                    echo '
            <td colspan="3" style="text-align:center;"><strong>vous avez fait une demande à '.$demande->getSupNom().'</strong></td>
            <td> <a href="index.php?p=1&amp;mat=',$v,'&amp;a=',PAGE_PERSO_ANNULER,'">Annuler</a> </td>';
                }
                else{
                    echo '
            <td colspan="3" style="text-align:center;"><strong>pas de supérieur</strong></td>
            <td> <a href="index.php?p=1&amp;mat=',$v,'&amp;a=',PAGE_PERSO_AFFILIER,'">En choisir un</a> </td>';
                }
            }
            else{
                $sup = $sups[$_SESSION['persos']['superieur'][$k]];
                echo '
            <td><img src="',$root_url,'/images/',icone_persos($sup->id),'" alt="icon perso"/></td>
            <td><a href="event/liste_events.php?id=',$sup->id,'">',$sup->id,'</a></td>
            <td>',$sup->nom,'</td>
            <td> <a href="',$root_url,'/messagerie/index.php?id=',$v,'&dest=',$sup->id,'">BAL</a> </td>';
                if($sup->pv <= 0)
                    echo '
            <td> <a href="index.php?p=1&amp;mat=',$v,'&amp;a=',PAGE_PERSO_DESAFFILIER,'">Se désaffilier</a> </td>';
            }
            echo '
        </tr>';
        }
    ?>
    </tbody>
</table>
<?php
//var_dump($_SESSION['persos']);
?>
