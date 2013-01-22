<?php
/**
 * Gestion des légions en interne : gérer les invitations
 *
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 */
	include_once('class/LegionConfig.php.inc');
	include_once('class/ManagerFactory.php.inc');
	include_once('class/DemandeLegion.php.inc');
	include_once('class/DemandeFromUtilisateur.php.inc');
	include_once('class/LegionDAO.php.inc');
        include_once('fonctions.php.inc');

        include_once('../event/eventManager.php');

	use legions\ManagerFactory          as ManagerFactory;
	use legions\LegionConfig            as LegionConfig;
	use legions\DemandeLegion           as DemandeLegion;
	use legions\DemandeFromUtilisateur  as DemandeFromUtilisateur;
	use legions\LegionDAO               as LegionDAO;
	use legions\LegionDroits    as LegionDroits;


        //On veut inviter un personnage
        if(isset($_POST['invitation_mat']) AND is_numeric($_POST['invitation_mat'])){
            $sql = LegionDAO::getInstance();
            try{
                $sql->sendInvitation($_SESSION['persos']['faction']['id'][$id],$_POST['invitation_mat'],0);
            }
            catch(Exception $ex){
                echo $ex;
            }

            //Envoie du message
            $expediteur     = $_SESSION['persos']['id'][$id];
            $mat            = $_POST['invitation_mat'];
            $titre          = 'Invitation dans la légion '.$legion->getNom();
            $corps          = 'Bonjour,

Vous avez reçu une invitation dans la légion '.$legion->getNom();
            sendBal($expediteur, $mat,$titre, $corps);
            echo '<strong>L\'invitation a bien été envoyée.</strong>';
        }
        //S'il y a une demande d'annulation
        if(isset($_GET['an']) AND is_numeric($_GET['an']) AND isset($_GET['t']) AND
                ($_GET['t'] == 0 OR $_GET['t'] == 1)){
            $sql = LegionDAO::getInstance();
            try{
                $sql->removeInvitation($_SESSION['persos']['faction']['id'][$id],$_GET['an'],$_GET['t']);
                //Si c'était l'utilisateur qui avait demandé on va lui dire qu'il est tout pourri et que la faction n'en veut pas !
                //Si erreur de suppression (non existant par exemple), n'enverra pas le bal, merci l'exception
                if($_GET['t'] == 1){
                    $expediteur     = $_SESSION['persos']['id'][$id];
                    $mat            = $_GET['an'];
                    $titre          = '['.$legion->getNom().']';
                    $corps          = 'Bonjour,

        Votre demande pour entrer dans la légion '.$legion->getNom().' a été refusée.';
                    sendBal($expediteur, $mat,$titre, $corps);
                }
            }
            catch(Exception $ex){}
        }

        $factory                = new ManagerFactory();
	$legions                = $factory->get(LegionConfig::$bddId[$_SESSION['persos']['camp'][$id]]);
	$legion                 = $legions->getLegions($_SESSION['persos']['faction']['id'][$id]);

        //acceptation de demande
        if(isset($_GET['ac']) AND is_numeric($_GET['ac'])){
            $sql = LegionDAO::getInstance();
            try{
                $demandesUtilisateurs   = new DemandeFromUtilisateur($_SESSION['persos']['faction']['id'][$id]);

                //Verification de l'existance d'une demande
                $f = false;

                foreach($demandesUtilisateurs->getDemandes() as $d){
                    if($f = ($d->getLegionId() == $_SESSION['persos']['faction']['id'][$id]))
                        break;
                }
                if(!$f)
                    throw new Exception("Ce personnage n'a fait aucune demande");

                //On enlève toutes les invitation
                $sql->removeAllInvitation($_GET['ac']);
                $sql->addMember($_SESSION['persos']['faction']['id'][$id],$_GET['ac']);
                //Si c'était l'utilisateur qui avait demandé on va lui dire qu'il est tout pourri et que la faction n'en veut pas !
                //Si erreur de suppression (non existant par exemple), n'enverra pas le bal, merci l'exception

                $expediteur     = $_SESSION['persos']['id'][$id];
                $mat            = $_GET['ac'];
                $titre          = '['.$legion->getNom().']';
                $corps          = 'Bonjour,

    Votre demande pour entrer dans la légion '.$legion->getNom().' a été acceptée.';
                sendBal($expediteur, $mat,$titre, $corps);

                //Ajout de l'event
                $eventManager = new EventManager();
                    $event = $eventManager->createEvent('legion');
                    $event->setSource($_GET['ac'], 'perso');
                    $event->setState(0);
                    $event->setAffected($legion->getId(), 'legion');
            }
            catch(Exception $ex){
                echo $ex->getMessage();
            }
        }

        $demandesLegion         = new DemandeLegion($_SESSION['persos']['faction']['id'][$id]);
        $demandesUtilisateurs   = new DemandeFromUtilisateur($_SESSION['persos']['faction']['id'][$id]);

        $js->addLib('jquery-ui');
        $js->addScript('legion/gererInvit');
        $js->addVariables('getRace',$legion->getRace());
        
        echo '

        <link rel="stylesheet" href="js/jquery-ui-1.8.17.custom.css" type="text/css" />
        <style>
	.ui-autocomplete {
		max-height: 100px;
		overflow-y: auto;
		/* prevent horizontal scrollbar */
		overflow-x: hidden;
		/* add padding to account for vertical scrollbar */
		padding-right: 20px;
	}
	* html .ui-autocomplete {
		height: 100px;
	}
	</style>

        <table class="legionPresTable" style="width:90%;margin:auto;">
            <tr>
                <td valign="top" style="text-align:left;">
                    <h3>Envoyer une invitation</h3>
                    <form method="post" action="index.php?p=1&mat='.$_GET['mat'].'&s='.$_GET['s'].'"
                        onsubmit="return valide();">
                        <p>
                            <label for="name_input">Nom/matricule</label> : <input type="text" name="name" id="name_input" />
                            <input type="hidden" name="invitation_mat" id="invitation_mat" />
                            <input type="submit" value="Inviter" />
                        </p>
                    </form>
                    <hr class="legionHr"/>
                    <h3>Demandes de la l&eacute;gion</h3>
                    <ul>
                        ';
                    foreach($demandesLegion->getDemandes() as $demande){
                        echo '
                        <li>',$demande->getPersoNom(),' (',$demande->getPersoId(),'):
                        <a href="index.php?p=1&amp;mat='.$_GET['mat'].'&amp;s='.$_GET['s'].'&amp;an=',$demande->getPersoId(),'&amp;t=0">
                            annuler
                        </a>
                        </li>';
                    }

                        echo '
                    </ul>
                </td>
                <td valign="top"  style="text-align:left;">
                    <h3>Demandes externes</h3>
                    <ul>';
                    foreach($demandesUtilisateurs->getDemandes() as $demande){
                        echo '
                        <li>',$demande->getPersoNom(),' (',$demande->getPersoId(),') :
                        <a href="index.php?p=1&amp;mat='.$_GET['mat'].'&amp;s='.$_GET['s'].'&amp;ac=',$demande->getPersoId(),'">
                            accepter
                        </a>
                        |
                        <a href="index.php?p=1&amp;mat='.$_GET['mat'].'&amp;s='.$_GET['s'].'&amp;an=',$demande->getPersoId(),'&amp;t=1">
                            refuser
                        </a>
                        </li>';
                    }
                    echo '
                    </ul>
                </td>
            </tr>
        </table>
        ';
?>
