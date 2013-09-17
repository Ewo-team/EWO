<?php

namespace jeu\legion;

/**
 * Liste des actions possible et construction des accès possibles à partir des droits de base
 *
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 */

    class LegionDroits{
        const GERER_MEMBRE          = 0;
        const GERER_BRAS_DROIT      = 1;
        const GERER_CHEF            = 2;
        const GERER_GRADE           = 3;
        const GERER_INVITATION      = 4;
        const VOIR_INFOS            = 5;
        const UTILISER_BAL_LIST     = 6;
        const LISTE_ENNEMIS         = 7;
        const PASSER_TRAITRE        = 8;
        const GERER_INFOS           = 9;
        const LISTE_NOOBS           = 10;

        public static $droits = array('gererChef', 'gererBrasDroit', 'gererMembre', 'gererGrade',
                'gererInvitation', 'gererBal', 'utiliserGPS', 'voirInfos', 'listNoobs');
        public static $droitsAff = array('Gérer les chefs', 'Gérer les bras droit',
            'Gérer les membres', 'Gérer les grades','Gérer les invitations',
            'Gérer les BAL', 'Accès GPS<br /> Voir les ennemis dans le plan<br /> Marquer un traitre', 'Voir les informations', 
        	'Voir les nouveaux');


        public static $actions;


        private $droitManager;
        private $legion;
        private $perso_id;


            public function __construct($droitManager, $legion, $perso_id) {
                $this->droitManager = $droitManager;
                $this->legion       = $legion;
                $this->perso_id     = $perso_id;
            }

            public function canDo($ac){
                //Legions spéciales
                //Défense
                if($ac == LegionDroits::LISTE_ENNEMIS && $this->legion->getType() != 3)
                    return false;
                if($ac == LegionDroits::LISTE_NOOBS && $this->legion->getType() != 5)
                	return false;
                //Justice
                if($ac == LegionDroits::PASSER_TRAITRE &&   		
                	$this->legion->getType() != 4 &&
                	$_SESSION['persos']['grade'][$this->perso_id] < 3 &&
                 	$_SESSION['persos']['galon'][$this->perso_id] <= 2
           		)
                    return false;
                if(array_key_exists($ac,self::$actions)){
                    foreach(self::$actions[$ac] as $r){
                        if($this->droitManager->$r())
                            return true;
                    }
                }
                return false;
            }

            /**
             * Fourni les droits d'un perso via son ID
             * @param type $persoId
             */
            static function droitsFromPersoId($id){
                include_once('LegionConfig.php.inc');
                include_once('ManagerFactory.php.inc');
                include_once('DroitManager.php.inc');

                $factory 	= new ManagerFactory();
                $legions	= $factory->get(LegionConfig::$bddId[$_SESSION['persos']['camp'][$id]]);
                $legion 	= $legions->getLegions($_SESSION['persos']['faction']['id'][$id]);
                $droitManager 	= new DroitManager($_SESSION['persos']['faction']['droits'][$id]);

                return new LegionDroits($droitManager, $legion, $id);
            }
    }

    //Init var
    LegionDroits::$actions = array(
            LegionDroits :: GERER_MEMBRE
                => array(
                    LegionDroits :: $droits[0],
                    LegionDroits :: $droits[1],
                    LegionDroits :: $droits[2]),
            LegionDroits :: GERER_BRAS_DROIT
                => array(
                    LegionDroits :: $droits[0],
                    LegionDroits :: $droits[1]
                ),
            LegionDroits :: GERER_CHEF
                => array(
                    LegionDroits :: $droits[0]
                ),
            LegionDroits :: GERER_GRADE
                => array(
                    LegionDroits :: $droits[3]
                ),
            LegionDroits :: GERER_INVITATION
                => array(
                    LegionDroits :: $droits[4]
                ),
            LegionDroits :: VOIR_INFOS
                => array(
                    LegionDroits :: $droits[7]
                ),
            LegionDroits :: UTILISER_BAL_LIST
                => array(
                    LegionDroits :: $droits[5]
                ),
            LegionDroits :: LISTE_ENNEMIS
                => array(
                    LegionDroits :: $droits[7]
                ),
            LegionDroits :: PASSER_TRAITRE
                => array(
                    LegionDroits :: $droits[7]
                ),
            LegionDroits :: GERER_INFOS
                => array(
                    LegionDroits :: $droits[0]
                ),
    		LegionDroits :: LISTE_NOOBS   
	    		=> array(
	    				LegionDroits :: $droits[7]
	    		)
    );


?>
