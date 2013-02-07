<?php
/**
 * 2 ou 3 fonctions pour gérer les taches récurrentes (annuler une demande, virer un utilisateur, ....)
 * 
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0s
 */
 
	include_once($root_url.'jeu/legion/class/DemandeUtilisateur.php.inc');
        require_once($root_url.'messagerie/messagerieDAO.php');
        require_once($root_url.'jeu/legion/class/LegionDAO.php.inc');

	use legions\DemandeUtilisateur  as DemandeUtilisateur;
        use legions\LegionDAO           as LegionDAO;
        
        function checkLegion(){
            $sql = LegionDAO::getInstance();
            try{
                $query = '
                    SELECT
                        p.id                as mat,
                        fm.faction_id       as legion,
                        fm.faction_grade_id as grade,
                        f.type              as type,
                        fg.droits           as droits 
                    FROM
                        `persos` p
                    LEFT JOIN `faction_membres` fm
                        ON fm.perso_id = p.id
                    LEFT JOIN `factions` f
                        ON f.id = fm.faction_id
                    LEFT JOIN `faction_grades` fg
                        ON
                            fg.faction_id   = fm.faction_id AND
                            fg.grade_id     = fm.faction_grade_id
                    WHERE
                    p.utilisateur_id = '.$_SESSION['utilisateur']['id'].';
                ';
                
                $stmt = $sql->query($query);
                while($data = $sql->fetch($stmt,\PDO::FETCH_OBJ)){
                    $id = array_keys($_SESSION['persos']['id'],$data->mat);
                    $id = $id[0];
                    if($data->legion != NULL){
                        $_SESSION['persos']['faction']['id'][$id]        = $data->legion;
                        $_SESSION['persos']['faction']['droits'][$id]    = $data->droits;
                        $_SESSION['persos']['faction']['type'][$id]      = $data->type;
                        $_SESSION['persos']['faction']['grade'][$id]     = $data->grade;
                    }
                    else{;
                        unset($_SESSION['persos']['faction']['droits'][$id]);
                        unset($_SESSION['persos']['faction']['type'][$id]);
                        unset($_SESSION['persos']['faction']['grade'][$id]);
                        $_SESSION['persos']['faction']['id'][$id] = 0;
                    }
                }
            }
            catch(Exception $ex){}
        }
        
	function annulerDemande($idPerso){
		$demandes 	= new DemandeUtilisateur($_SESSION['utilisateur']['id']);
		foreach($demandes->getDemandes() as $v){
			if($v->getPersoId() == $idPerso){//La demande dont on veut se débarrasser !
				$demandes->supDemande($v);
				break;
			}
		}
	}
        
        /**
         * sendBal
         * 
         * Envoyer une bal pour les légions
         * @param type $expediteur
         * @param type $mat
         * @param type $titre
         * @param type $corps 
         */
        function sendBal($expediteur, $mat,$titre, $corps){
            
            $bal = messagerieDAO::getInstance();
            $flag           = '0';
            $type_message   = 'joueur';

            $bal->InsertBalSend($expediteur, $mat,$titre, $corps, $flag, $type_message, NULL);
            $corps_id = $bal->InsertCorpsBal($titre, $corps, $mat, NULL);

            $sql_query = $bal->PrepareInsertBal('bals');
            $corps_id = $bal->InsertCorpsBal($titre, $corps, $mat, NULL);

            $parametres = array($expediteur, $mat, $corps_id, $flag, $type_message, 0);
            //-- Envoie de la bal a son destinataire
            $sql_bal = $bal->InsertBalPrepare($sql_query, $parametres);
        }

?>
