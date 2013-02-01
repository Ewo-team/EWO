<?php

namespace messagerie;

/**
 * Messagerie DAO, requete pour construire la messagerie
 * 
 * @author Simonet Fabrice <aigleblanc@gmail.com>
 * @version 2.1
 * @package messagerie
 * @category dao
 */

use \conf\ConnecteurDAO as ConnecteurDAO;

class messagerieDAO extends ConnecteurDAO {

	/**
	 * Récupere les informations sur les bal
	 */	
	public function SelectAllBal($id, $type = 'recu') {
		if($type == 'recu') {
			$flag_archive = 'AND flag_archive = 0';
		} elseif($type == 'archive') {
			$flag_archive = 'AND flag_archive = 1';	
		}
		/*
			, (SELECT GROUP_CONCAT(
					DISTINCT perso_dest_id
					SEPARATOR \"-\") FROM bals l WHERE l.corps_id = bals_corps.id) AS liste_bal
		 */
		$sql = "SELECT SQL_CACHE bals.id as id_bals, bals_corps.titre as titre, DATE_FORMAT(bals_corps.date, '%d-%m-%Y à %kh%i') as date, bals.flag_lu as lu, bals.flag_envoye as flag_exp, bals.flag_favori as flag_fav, 
					liste_mats AS liste_mats, liste AS liste_bal, 
					persos.nom AS expediteur, persos.id AS id_expediteur, persos.signature AS signature, persos.options AS options
				FROM bals
				INNER JOIN bals_corps ON (bals_corps.id = bals.corps_id)
				INNER JOIN persos ON bals.perso_src_id = persos.id 
				WHERE perso_dest_id = ? $flag_archive
				GROUP BY bals.corps_id
				ORDER BY flag_favori DESC, lu ASC, UNIX_TIMESTAMP(bals_corps.date) DESC;";
		
		$this->prepare($sql);
		$this->executePreparedStatement(null, array($id));			
		return $this->fetchAll();	
	}

	/**
	 * Récupere les informations sur les bal recu
	 */			
	public function SelectBalRecu($id) {	
		return $this->SelectAllBal($id);
	}	
	
	/**
	 * Récupere les informations sur les bal archivé
	 */			
	public function SelectBalArchive($id) {
		return $this->SelectAllBal($id, 'archive');
	}	
	
	/**
	 * Récupere les informations sur les bal envoyé
	 */			
	public function SelectBalEnvoye($id) {
		$sql = "SELECT bals_send.id AS id_bals, bals_send.perso_dest_id AS id_expediteur, bals_send.titre AS titre, 
					DATE_FORMAT(bals_send.date, '%d-%m-%Y à %kh%i') AS date, bals_send.flag_lu AS lu, bals_send.flag_exp AS flag_exp, 
					bals_send.flag_fav AS flag_fav, bals_send.liste_bal AS liste_bal, persos.nom AS expediteur
					FROM bals_send
					INNER JOIN persos ON bals_send.perso_src_id = persos.id
					WHERE perso_src_id = ? ORDER BY lu ASC, UNIX_TIMESTAMP(bals_send.date) DESC";
		$this->prepare($sql);
		$this->executePreparedStatement(null, array($id));	
		return $this->fetchAll();
	}		
				
	/**
	 * Récupere les informations sur les messages recus
	 */
	public function SelectBalCorps($id, $exp, $recu = 'perso_dest_id') {
		$sql = "SELECT bals_corps.corps 
							FROM bals_corps 
							INNER JOIN bals ON bals.corps_id = bals_corps.id
							INNER JOIN persos ON bals.perso_src_id = persos.id
							
									 
										WHERE $recu = ? AND bals.id = ? LIMIT 1";
		$this->prepare($sql);
		$this->executePreparedStatement(null, array($exp,$id));	
		//echo $sql;
		return $this->fetch_assoc();
	}
	
	public function SelectBalCorpsEnvoye($id, $exp) {
		$sql = "SELECT bals_send.corps 
							FROM bals_send 
								INNER JOIN persos 
									ON bals_send.perso_dest_id = persos.id 
										WHERE perso_src_id = ? AND bals_send.id = ? LIMIT 1";
		$this->prepare($sql);
		$this->executePreparedStatement(null, array($exp,$id));	
		//echo $sql;
		return $this->fetch_assoc();
	}	

	/**
	 * Récupere les informations sur les bal tagé
	 */			
	public function SelectFlag($id) {
		$sql = "SELECT flag_favori FROM bals WHERE id=?";
		$this->prepare($sql);
		$this->executePreparedStatement(null, array($id));	
		return $this->fetch_assoc();
	}		


	/**
	 * met a jour le statut du tag de la bal
	 */
	public function UpdateFlag($id, $flag) {
		$sql = "UPDATE bals SET flag_favori = ? WHERE id=?";
		$this->prepare($sql);
		return $this->executePreparedStatement(null, array($id,$flag));	
		//return $this->exec($sql);
	}
	
	/**
	 * met a jour le statut de lecture des bals en fonction de la table
	 */
	public function UpdateLu($id, $exp) {
		$sql = "UPDATE bals SET flag_lu = '1' WHERE id=? AND perso_dest_id = ?";
		$this->prepare($sql);
		return $this->executePreparedStatement(null, array($id,$exp));			
		//return $this->exec($sql);
	}	
	
	/**
	 * met a jour le statut de lecture des bals en fonction de la table
	 */	
	public function UpdateArchive($id, $exp) {
		$sql = "UPDATE bals SET flag_archive = '1' WHERE id=? AND perso_dest_id = ?";
		$this->prepare($sql);
		return $this->executePreparedStatement(null, array($id,$exp));					
		//return $this->exec($sql);
	}	

	/**
	 * Supprime une bal en fonction de ca table et son id
	 */
	public function DelBal($id) {
		$sql = "SELECT count(*), corps_id FROM bals WHERE corps_id = (SELECT corps_id FROM bals WHERE id=?) GROUP BY corps_id";
		$this->prepare($sql);
		$this->executePreparedStatement(null, array($id));	
		$res = $this->fetch();	
	
		$sql = "DELETE FROM bals WHERE id=?";
		$this->prepare($sql);
		$result = $this->executePreparedStatement(null, array($id));			
		
		// Si il message est orphelin, on supprime le corps également
		if($res[0] <= 1) {
			$sql = "DELETE FROM bals_corps WHERE id = ? ;";
			//echo $sql;
			$this->prepare($sql);
			$this->executePreparedStatement(null, array($res[1]));				
		}
		
		return $result;

	}
	
	public function DelBalSend($id) {
		$sql = "DELETE FROM bals_send WHERE id=?";
		$this->prepare($sql);
		return $this->executePreparedStatement(null, array($id));				
	}
	
	/**
	 * Prépare une requête d'insertion de bal
	*/
	public function PrepareInsertBal($table) {
		return $this->prepare("INSERT INTO bals (perso_src_id, perso_dest_id, corps_id, flag_lu, flag_envoye, flag_archive) VALUES (?, ?, ?, ?, ?, ?)");
	}
	
	/**
	 * Insertion du corps d'une BAL (retourne l'identifiant)
	*/
	public function InsertCorpsBal($titre, $corps, $mats, $liste) {
		$sql = "INSERT INTO bals_corps(date, titre, corps, liste_mats, liste) VALUES (NOW(), ?, ?, ?, ?)";	
		$this->prepare($sql);
		$this->executePreparedStatement(null, array($titre, $corps, $mats, $liste));				
		return $this->_conn->lastInsertId();
	}
	
	/**
	 * Insertion des bal d'une requête préparée
	*/
	public function InsertBalPrepare($requete, $tableau) {
		return $this->executePreparedStatement($requete, $tableau);
	}
	
	/**
	 * Insertion des bal dans la table bals_send
	*/	
	public function InsertBalSend($expediteur, $liste, $titre, $corps, $flag, $img, $liste_bal) {
		$sql = "INSERT INTO bals_send (perso_src_id, perso_dest_id, titre, corps, date, flag_lu, flag_exp, liste_bal) 
												VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)";
		$this->prepare($sql);
		return $this->executePreparedStatement(null, array($expediteur, $liste, $titre, $corps, $flag, $img, $liste_bal));													
	}	
	
	/**
	 * Test si l'id du destinataire existe
	 */			
	public function VerifPersoId($mat) {
		$sql = "SELECT COUNT(id) AS count FROM persos WHERE id=?";
		$this->prepare($sql);
		$this->executePreparedStatement(null, array($mat));
		return $this->fetch_assoc();
	}
	
	/**
	 * Test si le personnage existe et est lié a l'utilisateur le demandant
	 */			
	public function VerifPersoExisteUtilisateur($id_perso, $utilisateur_id) {
		
		//$sql = "SELECT id, nom FROM persos WHERE id = '$id_perso' AND utilisateur_id = '$utilisateur_id'";
		$sql = "SELECT id, nom FROM persos WHERE id = ? AND utilisateur_id = ?";
		$this->prepare($sql);
		$this->executePreparedStatement(null, array($id_perso, $utilisateur_id));
		return $this->fetch_assoc();
	}
	
	/**
	 * Récupere les informations sur une bal
	 * @id : Id de la bal
	 */			
	public function SelectBal($id) {
		$sql = "SELECT*FROM bals, bals_corps WHERE bals.corps_id = bals_corps.id AND id=?";
		$this->prepare($sql);
		$this->executePreparedStatement(null, array($id));		
		return $this->fetch_assoc();
	}
		
	public function SelectMatriculesBallingNumerique($id, $camp, $mat) {
		$matricules = array();
		$info = $this->SelectInfoListeNumerique($id, $camp, $mat);
		//print_r($info);
		// On a le droit d'accèder à la liste, et d'y poster
		if($info) {
			if($info[0]['ouverture'] != 0 || $this->IsOnListe($id, $mat) || $info[0]['type'] == 'aura') {
				// Si c'est une liste ouverte, on s'y inscrit (pas besoin de gérer les doublons
				$matricules = explode("|",$info[0]['liste']);
				$this->AddMatOnListe($id, $mat, $matricules);
			}
		}
		
		return $matricules;
	}
	
	public function AddMatOnListe($id, $mat, $liste = null) {
		if(!isset($liste)) {
			$info = $this->SelectInfoListeNumerique($id);
			$liste = explode("|",$info[0]['liste']);
				
		}
		
		$liste[] = $mat;
		$liste = array_unique($liste);		
		
		$newliste = implode("|",$liste).'|';
		$sql = "UPDATE bals_listes SET liste=? WHERE id=?";
		$this->prepare($sql);
		$this->executePreparedStatement(null, array($newliste,$id));			
	}
	public function SelectMatriculesBallingFaction($id) {
		$sql = "SELECT perso_id FROM faction_membres WHERE faction_id = ?";
		$this->prepare($sql);
		$this->executePreparedStatement(null, array($id));		
		return $this->fetchAll_row();		
	}

	public function SelectMatriculesBallingPlan($id) {
		$sql = "SELECT perso_id FROM damier_persos WHERE carte_id = ?";
		$this->prepare($sql);
		$this->executePreparedStatement(null, array($id));		
		return $this->fetchAll_row();	
	}	
	
	public function SelectMatriculesBallingCamp($id) {
		$sql = "SELECT a.id FROM persos a, persos p, races ra, races rp WHERE 
		a.race_id = ra.race_id AND ra.grade_id = -2 AND 
		p.race_id = rp.race_id AND rp.grade_id = -2 AND
		rp.camp_id = ra.camp_id
		AND p.id = ? ORDER BY a.id ASC";
		$this->prepare($sql);
		$this->executePreparedStatement(null, array($id));		
		return $this->fetchAll_row();	
	}	
	
	public function SelectMatriculesBallingJoueurs() {
		$sql = "SELECT persos.id, persos.nom FROM utilisateurs, persos WHERE persos.utilisateur_id = utilisateurs.id GROUP BY utilisateurs.id";
		$this->prepare($sql);
		$this->executePreparedStatement(null, null);		
		return $this->fetchAll_row();	
	}		
	
	public function SelectMatriculesBallingSpecial($nom) {
		if($nom == 'admin') {
			$pattern = '_1__';
		}
		if($nom == 'anim') {
			$pattern = '__1_';		
		}
		if($nom == 'at') {
			$pattern = '___1';		
		}

		$sql = "SELECT persos.id FROM persos LEFT JOIN utilisateurs ON (persos.utilisateur_id = utilisateurs.id) WHERE utilisateurs.droits LIKE '$pattern' GROUP BY utilisateurs.id";
		$this->query($sql);
		return $this->fetchAll_row();
	}	
	
	public function SelectInfoListeNumerique($id = null, $camp = null, $mat=null, $aura = false) {
		$sql = "SELECT *, (liste LIKE :pattern) AS enregistre FROM bals_listeview ";
		$arg = array(':pattern' => "%|$mat|%");
				
		if(isset($camp) && isset($mat)) {
			$sql .= "WHERE (camp = :camp OR camp IS NULL) AND (type='public' OR type='aura' OR liste LIKE :pattern) ";
			$arg[':camp'] = $camp;
			if(isset($id)) {
				$sql .= "AND ";
			}			
		} elseif(isset($id)) {
			$sql .= 'WHERE ';
		}
		
		if(isset($id)) {
			$sql .= "id = :id LIMIT 1";
			$arg[':id'] = $id;
		}

		$this->prepare($sql);
		$this->executePreparedStatement(null, $arg);				
		return $this->fetchAll_assoc();		
	}
	
	public function SelectMembresListeNumerique($array) {
		$sql = "SELECT id, nom FROM persos WHERE id IN ($array)";
		//echo $sql;
		$this->query($sql);				
		return $this->fetchAll_assoc();		
	}
	
	public function IsOnListe($id, $mat) {
		$sql = "SELECT true FROM bals_listes WHERE (liste LIKE ? OR owner=?) AND id=?";
		$this->prepare($sql);
		$this->executePreparedStatement(null, array("%|$mat|%",$mat,$id));		
		return ($this->fetch_row()) ? true : false;		
	}
	
	public function DeleteListe($id) {
		$sql = "DELETE FROM bals_listes WHERE id=?";
		$this->prepare($sql);
		$this->executePreparedStatement(null, array($id));			
	}
	
	public function QuiteListe($id,$mat) {
		$liste = $this->SelectInfoListeNumerique($id);
		
		$matricules = explode("|",$liste[0]['liste']);
		
		$k = array_search($mat,$matricules);
		unset($matricules[$k]);
		
		$matricules = array_unique($matricules);
		
		$liste = implode("|",$matricules).'|';
		$sql = "UPDATE bals_listes SET liste=? WHERE id=?";
		$this->prepare($sql);
		$this->executePreparedStatement(null, array($liste,$id));	
	}	
}
