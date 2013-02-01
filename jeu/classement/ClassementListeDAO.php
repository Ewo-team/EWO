<?php

namespace jeu\classement;

/**
 * ClassementDAO
 *
 * @author Ganesh <ganesh@ewo.fr>
 * @version 1.0
 * @package classement
 * @category dao
 */

class ClassementListeDAO extends ClassementDAO {
	
	public function prepareClassement() {
		// début de la requête
		$this->_select = 'SELECT * ';
				
		if($this->_type === ClassementDAO::FAMILLE) {
			$this->_select .= ', avg(xp) as px ';
		}				
				
		if($this->_type === ClassementDAO::SURVIE) {
			if($this->_date) {
				$this->_select .= ", (SELECT date FROM morgue WHERE morgue.mat_victime=classement.mat AND date <= '". date("Y-m-d H:i:s",$this->_date+86399) ."' ORDER BY date DESC LIMIT 1) AS datemort ";
			} else {
				$this->_select .= ', (SELECT date FROM morgue WHERE morgue.mat_victime=classement.mat ORDER BY date DESC LIMIT 1) AS datemort ';
			}
			
		}
				
		$this->_select .= 'FROM classement ';

		$this->_where = 'WHERE ';
			
		if($this->_type === ClassementDAO::SURVIE) {
			$this->_where .= 'grade >= 2 AND ';
		}
			
		if($this->_type === ClassementDAO::FAMILLE) {
			$this->_where .= 'type = 7 AND ';
		}				
			
		// races
		$separateur = false;
		if($this->_nbraces != 4) {
		
			if($this->_ange===1) {
				$this->_where .= 'camp=3 ';
				$separateur = true;
			}
			
			$this->separateur($this->_where, $separateur);
			
			if($this->_humain===1) {
				$this->_where .= 'camp=1 ';
				$separateur = true;
			}

			$this->separateur($this->_where, $separateur);
			
			if($this->_demon===1) {
				$this->_where .= 'camp=4 ';
				$separateur = true;
			}

			$this->separateur($this->_where, $separateur);
			
			if($this->_paria===1) {
				$this->_where .= 'camp=2 ';
			}

			$this->_where .= 'AND ';
		} 
		
		// On prépare la date
		// @TODO optimiser ici
		// Format de date: 2010-03-03 08:26:00
		if($this->_date) {
			$this->_where .= "camp<5 AND date = '". date("Y-m-d",$this->_date) ."' ";
		} else {
			$this->_where .= 'camp<5 AND date = CURDATE() ';
		}
		
		$this->_group = 'GROUP BY mat ';
		
		if($this->_type === ClassementDAO::FAMILLE) {
			$this->_group = 'GROUP BY joueur ';
		}		
		
		$this->_order = 'ORDER BY ';
			
		// ordre grade
		if($this->_grade === 1) {
			$this->_order .= "grade DESC, ";
			
			if($this->_galon === 1) {
				$this->_order .= "galon DESC, ";
			}
		}
		
				
		// ordre type
		if($this->_type === ClassementDAO::MEURTRE) {
			$this->_order .= "meurtre DESC, ";	
		}	
		if($this->_type === ClassementDAO::MORT) {
			$this->_order .= "mort DESC, ";	
		}	
		if($this->_type === ClassementDAO::TAILLECV) {
			$this->_order .= "(1-(mort / (mort+meurtre))) DESC, ";	
		}	
		if($this->_type === ClassementDAO::SURVIE) {
			$this->_order .= "datemort ASC, ";	
		}			
			
		// Tri par xp, dans tout les cas			
		if($this->_type === ClassementDAO::FAMILLE) {
			$this->_order .= "avg(xp) DESC ";
		} else {
			$this->_order .= "xp DESC ";
		}	
		

		
		// pagination
		if($this->_pageMax > 1) {
			$this->_order .= 'LIMIT '. (($this->_page-1) * $this->_nbParPage) .','.$this->_nbParPage;	
		}
		
	
		$this->_sql = $this->_select . $this->_join . $this->_where . $this->_group . $this->_order;
	
	}
		
	public function compteLignes() {
		if($this->_type === ClassementDAO::FAMILLE) {
			$compter = 'DISTINCT joueur';
		} else {
			$compter = 'mat';
		}	
		$requete = 'SELECT COUNT('.$compter.') AS nb FROM classement ' . $this->_join . $this->_where;
		//echo $requete;
		$this->prepare($requete);
		$this->executePreparedStatement();
		$return = $this->fetch();
		return $return[0];		
	}
	
	public function cherchePositionMat($mat) {
		if($this->persoExist($mat)) {
			$requete = 'SELECT count(*) FROM classement ' . $this->_join . $this->_where; 

			if($this->_type === ClassementDAO::XP) {
				$requete .= 'AND xp >= (SELECT xp FROM classement '.$this->_where.' AND mat=? LIMIT 1) ';
			}		
			if($this->_type === ClassementDAO::MEURTRE) {
				$requete .= 'AND meurtre >= (SELECT meurtre FROM classement '.$this->_where.' AND mat=? LIMIT 1) ';
			}
			if($this->_type === ClassementDAO::MORT) {
				$requete .= 'AND mort >= (SELECT mort FROM classement '.$this->_where.' AND mat=? LIMIT 1) ';	
			}
			
			$this->prepare($requete);
			$this->executePreparedStatement(null,array($mat));
			$return = $this->fetch();
			
			if($return[0] == 0)
			{
				$return[0] = 1;
			}
			
			$this->page(ceil($return[0] / $this->_nbParPage));
			
		}
	}	
}
