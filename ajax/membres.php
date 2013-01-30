<?php

/**
 * Gérer les changements sur les membres (grade)
 *
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 */
session_start();

/**
 * Les posts utilisés :
 * mat : matricule du perso qui fait l'action
 * id : matricule du perso qui subit l'action
 * value : nouvelle valeure de grade
 */
    if (!isset($_POST['mat']) || !is_numeric($_POST['mat']) ||
            !isset($_POST['id']) || !is_numeric($_POST['id']) ||
            !isset($_POST['value']) || !is_numeric($_POST['value']))
        die();


    require('ref.php.inc');

    include_once($root_url . 'legion/class/LegionConfig.php.inc');
    include_once($root_url . 'legion/class/LegionConfig.php.inc');
    include_once($root_url . 'legion/class/ManagerFactory.php.inc');
    include_once($root_url . 'legion/class/DroitManager.php.inc');
    require_once($root_url . 'legion/class/LegionDAO.php.inc');
    require_once($root_url . 'legion/class/LegionDroits.php.inc');

    use legions\LegionDAO       as LegionDAO;
    use legions\ManagerFactory  as ManagerFactory;
    use legions\LegionConfig    as LegionConfig;
    use legions\DroitManager    as DroitManager;
    use legions\LegionDroits    as LegionDroits;

    $id = array_keys($_SESSION['persos']['id'], $_POST['mat']);
    $id = $id[0];

    $factory = new ManagerFactory();
    $legions = $factory->get(LegionConfig::$bddId[$_SESSION['persos']['camp'][$id]]);
    $legion = $legions->getLegions($_SESSION['persos']['faction']['id'][$id]);

    $droitManager = new DroitManager($_SESSION['persos']['faction']['droits'][$id]);

    $droits = new LegionDroits($droitManager);

    $attr = array();
    //Génération de la liste des grades attribuables
    foreach ($legion->getListGrades() as $g) {
        $d = $g->getDroitsArray();
        $i = $g->getGrade_id();
        if (
                $d[0] == 1 && $i != 1 && $droits->canDo(LegionDroits::GERER_CHEF) ||
                $d[0] == 1 && $i == 1 && $_SESSION['persos']['faction']['grade'][$id] == 1 ||
                $d[0] == 0 && $d[1] == 1 && $droits->canDo(LegionDroits::GERER_BRAS_DROIT) ||
                $d[0] == 0 && $d[1] == 0 && $droits->canDo(LegionDroits::GERER_MEMBRE)
        ) {
            $attr[$i] = $g->getNom();
        }
    }

    //Check : on a le droit de donner ce grade
    if (!array_key_exists($_POST['value'], $attr))
        die();

    //On check si le perso ciblé fait bien partie de la légion
    $t = false;
    foreach ($legion->getListMembres() as $m)
        if ($m['id'] == $_POST['id']) {
            $t = $m;
            break;
        }
    if (!$t)
        die();

    //Plus qu'à faire la modif !
    $query_alter = '
        UPDATE `faction_membres`
        SET
            `faction_grade_id` = '.$_POST['value'].'
        WHERE
            `perso_id`      = '.$_POST['id'].' AND
            `faction_id`    = '.$_SESSION['persos']['faction']['id'][$id].';
    ';

    $sql = LegionDAO::getInstance();
    $sql->exec($query_alter);
?>