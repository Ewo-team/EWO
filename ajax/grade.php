<?php

/**
* Traitement des modifications de grade
*
* @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
* @version 1.0
*/

require_once __DIR__ . '/../conf/master.php';

if(!isset($_SESSION['utilisateur']['id'])){
	exit;
}

    require_once(SERVER_ROOT.'/jeu/legion/class/LegionDAO.php.inc');
    require_once(SERVER_ROOT.'/jeu/legion/class/DroitManager.php.inc');
    require_once(SERVER_ROOT.'/jeu/legion/class/LegionDroits.php.inc');
    require_once(SERVER_ROOT.'/jeu/legion/class/LegionConfig.php.inc');
    require_once(SERVER_ROOT.'/jeu/legion/class/ManagerFactory.php.inc');

    use jeu\legion\LegionDAO       as LegionDAO;
    use jeu\legion\DroitManager    as DroitManager;
    use jeu\legion\ManagerFactory  as ManagerFactory;
    use jeu\legion\LegionConfig    as LegionConfig;
    use jeu\legion\LegionDroits    as LegionDroits;


    if( !isset($_POST['c']) || !is_numeric($_POST['c']) ||
        !isset($_POST['va']) ||
        !isset($_POST['i']) || !is_numeric($_POST['i']) ||
        !isset($_SESSION['persos']) || !isset($_POST['mat']) ||
        !in_array($_POST['mat'],$_SESSION['persos']['id']))
            die();

    $id = array_keys($_SESSION['persos']['id'],$_POST['mat']);
    $id = $id[0];

    $factory = new ManagerFactory();
    $legions = $factory->get(LegionConfig::$bddId[$_SESSION['persos']['camp'][$id]]);
    $legion = $legions->getLegions($_SESSION['persos']['faction']['id'][$id]);

    $grades = $legion->getListGrades();



    $droitManager   = new DroitManager($_SESSION['persos']['faction']['droits'][$id]);
    $droits         = new LegionDroits($droitManager, $legion, $_POST['mat']);

    if(!$droits->canDo(LegionDroits::GERER_GRADE))
        die();

    $sql    = LegionDAO::getInstance();
    if($_POST['c'] == 0 || $_POST['c'] == 1){
        $val = trim($_POST['va']);
        $val = str_replace('\t', '', $val);
        $val = str_replace('\n', '', $val);
        $val = str_replace('\r', '', $val);
        $val = addslashes($val);
        $val = htmlentities($val);

        if($_POST['c'] == 0)
            $c = 'nom';
        else
            $c =  'description';
        $query_alter = '
            UPDATE `faction_grades`
            SET   `'.$c.'` = "'.$val.'"
            WHERE
                `grade_id`      = '.$_POST['i'].' AND
                `faction_id`    = '.$_SESSION['persos']['faction']['id'][$id].'
            ;
        ';
        $sql->exec($query_alter);
    }
    else if ($_POST['i'] > 1){//On ne va quand même pas changer les droits du grand chef !

        $droitTbl = $grades[$_POST['i']]->getDroitsArray();
        //var_dump($grades);
        $droitTbl[$_POST['c']-2] = $_POST['va'];

        $newDroits = '';
        foreach($droitTbl as $d){
            $newDroits .= $d;
        }

        $query_alter = '
            UPDATE `faction_grades`
            SET   `droits` = "'.$newDroits.'"
            WHERE
                `grade_id`      = '.$_POST['i'].' AND
                `faction_id`    = '.$_SESSION['persos']['faction']['id'][$id].'
            ;
        ';
        $stmt = $sql->query($query_alter);
    }
?>
