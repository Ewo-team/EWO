<?php

namespace jeu\legion;

/**
 * Affichage d'un perso
 *
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 */

 use \persos\eventManager\eventManager as eventManager;
 use \persos\eventManager\eventFormatter as eventFormatter;

	include_once('fonctions.php.inc');

	if(in_array($_GET['mat'],$_SESSION['persos']['id'])){

		$id = array_keys($_SESSION['persos']['id'],$_GET['mat']);
		$id = $id[0];

		if(!isset($_GET['s']) && isset($_GET['a']) && $_GET['a']=='l')
                    annulerDemande($_SESSION['persos']['id'][$id]);
                else if(!isset($_GET['s']) && isset($_GET['an']) && is_numeric($_GET['an'])){//Annuler une demande de la part d'une légion
                    $sql = LegionDAO::getInstance();
                    try{
                        $sql->removeInvitation($_GET['an'],$_GET['mat'],0);
                    }
                    catch(Exception $ex){}
                }
                else if(!isset($_GET['s']) && isset($_GET['ac']) && is_numeric($_GET['ac'])){//Accepter une demande de la part d'une légion
                    $sql = LegionDAO::getInstance();
                    try{
                        $demandesUtilisateurs   = new DemandeFromLegion($_SESSION['utilisateur']['id']);

                        //Verification de l'existance d'une demande
                        $f = false;
                        foreach($demandesUtilisateurs->getDemandes() as $d){
                            if($f = ($d->getPersoId() == $_GET['mat']))
                                break;

                        }
                        if(!$f)
                            throw new Exception("Ce personnage n'a fait aucune demande !");
                        //On enlève toutes les invitation
                        $sql->removeAllInvitation($_GET['mat']);
                        $sql->addMember($_GET['ac'],$_GET['mat']);

                        //Ajout de l'event
                        $eventManager = new EventManager();
                            $event = $eventManager->createEvent('legion');
                            $event->setSource($_GET['mat'], eventFormatter::convertType('perso'));
                            $event->setState(0);
                            $event->setAffected($_GET['ac'], eventFormatter::convertType('legion'));
                            header('Location: index.php?p='.$_GET['p'].'&mat='.$_GET['mat']);
                            exit();
                        }
                        catch(Exception $ex){
                            echo $ex->getMessage();
                        }
                }
		echo'
		<div class="legionPersoLeft" style="width:110px;height:55px;padding:2px;text-align:center;">
                    <img src="'.SERVER_URL.'/images/'.icone_persos($_SESSION['persos']['id'][$id]).'" alt="avatar" style="margin:auto;"/>
                    ',$_SESSION['persos']['nom'][$id],'
		</div>';


		if($_SESSION['persos']['faction']['id'][$id] != 0){
                    require('legion_intern.php.inc');
		}
		else{ //On vérifie s'il y a une demande en cours pour l'utilisateur

                    $txt = '
                    <h2>Votre personnage ne fait parti d\'aucune faction</h2>
                    <br />
                    <a href="index.php?l_filter='.LegionConfig::raceFromIdBdd($_SESSION['persos']['camp'][$id]).'">en chercher une</a>';

                    $demandes 	= new DemandeUtilisateur($_SESSION['utilisateur']['id']);
                    foreach($demandes->getDemandes() as $v){
                        if($v->getPersoId() == $_SESSION['persos']['id'][$id]){//Il y en a une
                            $factory 	= new ManagerFactory();
                            $legions	= $factory->get(LegionConfig::$bddId[$_SESSION['persos']['camp'][$id]]);
                            $legion 	= $legions->getLegions($v->getLegionId());
                            $txt .= '<p>
                                        Votre personne a fait une demande aupr&egrave;s de la l&eacute;gion : '.$legion->getNom().',
                                        <a href="index.php?p=1&amp;mat='.$_SESSION['persos']['id'][$id].'&amp;a=l">annuler</a>
                            </p>';
                            break;
                        }
                    }

                    $list = '';
                    $demandes   = new jeu\legion\DemandeFromLegion($_SESSION['utilisateur']['id']);
                    foreach($demandes->getDemandes() as $v){
                        if($v->getPersoId() == $_SESSION['persos']['id'][$id]){//Il y en a une
                            $factory 	= new ManagerFactory();
                            $legions	= $factory->get(LegionConfig::$bddId[$_SESSION['persos']['camp'][$id]]);
                            $legion 	= $legions->getLegions($v->getLegionId());
                            $list .= '
                            <li>
                                '.$legion->getNom().' :
                                <a href="index.php?p=1&amp;mat='.$_SESSION['persos']['id'][$id].'&amp;ac='.$legion->getId().'">accepter</a> |
                                <a href="index.php?p=1&amp;mat='.$_SESSION['persos']['id'][$id].'&amp;an='.$legion->getId().'">annuler</a>
                            </li>';
                        }
                    }

                    if($list != '')
                        $list = '<p>Vous avez reçu la/les demande(s) suivantes : <ul>'.$list.'</ul></p>';

                    echo $txt.$list;
		}

	}
	else
		echo '<strong>T\'as rien &agrave; faire l&agrave; oust !</strong>';
?>
