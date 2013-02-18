<?php

namespace jeu\legion;

/**
 * Gestion des légions en interne
 *
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 */

use \persos\eventManager\eventManager as eventManager;


	$factory 	= new ManagerFactory();
	$legions	= $factory->get(LegionConfig::$bddId[$_SESSION['persos']['camp'][$id]]);
	$legion 	= $legions->getLegions($_SESSION['persos']['faction']['id'][$id]);

        $droits         = LegionDroits::droitsFromPersoId($id);

        //Quitter la légion
        if(isset($_POST['del']) && $legion->getAlignement() != 5){//On ne peut pas partir si on est traître
            $sql = LegionDAO::getInstance();
            $query_alter = '
                DELETE FROM `faction_membres`
                    WHERE `perso_id` =  :id;';
            $stmt = $sql->prepare($query_alter);
            $stmt->execute(array(':id' => $_GET['mat']));
            $eventManager = new EventManager();
		$event = $eventManager->createEvent('legion');
		$event->setSource($_GET['mat'], 'perso');
                $event->setState(2);
                $event->setAffected($legion->getId(), 'legion');

            header('Location: index.php?mat='.$_GET['mat']);
            exit();
        }

        //Les pages visibles
        $pages = array(
            array('Gérer les informations',  LegionDroits::GERER_INFOS,'gererInfos'),
            array('Gérer les grades',  LegionDroits::GERER_GRADE,'gererGrade'),
            array('Gérer les invitations',  LegionDroits::GERER_INVITATION,'gererInvitation'),
            array('Voir les ennemis dans le plan',  LegionDroits::LISTE_ENNEMIS,'persoPlan'),
            array('Marquer un traitre',  LegionDroits::PASSER_TRAITRE,'marquerTraitre'),
            array('Voir les nouveaux',  LegionDroits::LISTE_NOOBS,'listNoobs'),
            array('Voir la liste des membres',  LegionDroits::VOIR_INFOS,'listeMembres')
        );

	echo '<h2>'.$legion->getNom().'</h2>';

	echo '
		<table  class="legionPresTable" style="width:90%;margin:auto;">
			<tr>
		';
		//On regarde ce qu'on peut faire
		$d = -1;
		foreach($pages as $k => $v){
			if($droits->canDo($v[1])){
				$d = $k;
				echo '
			<td>
				<a href="index.php?p=1&amp;mat='.$_GET['mat'].'&amp;s='.$k.'">'.$v[0].'</a>
			</td>';
			}
		}
		echo '
			</tr>
		</table>
		<hr class="legionHr" style="margin-left:45px;"/><br />';

		if(isset($_GET['s']) AND array_key_exists($_GET['s'],$pages))
			$d = $_GET['s'];

		if($d >= 0){
			require_once('gestionLegion/'.$pages[$d][2].'.php.inc');
		}

            if($legion->getAlignement() != 5) {
                $js->addScript('legion/intern');
                echo '
                <br /><br /> <br />
                <hr class="legionHr" style="margin-left:45px;"/>
                <div style="text-align:center;">
                    <input type="button" id="del" value="Quitter la légion" />
                    <div id="confirmDel" style="display:none;background-color: red;color:white;width:300px;margin:auto;">
                        <form method="post" action="index.php?p=',$_GET['p'],'&amp;mat=',$_GET['mat'],'">
                            <input type="submit" name="del" value="Confirmer" />
                            <input type="button" id="cancel" value="Annuler" />
                        </form>
                    </div>
                </div>';
            }
 ?>
