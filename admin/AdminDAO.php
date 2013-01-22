<?php



	require_once ("../../conf/ConnecteurDAO.php");

	class AdminDAO extends ConnecteurDAO {
					
		public function SelectIdEffet($type,$valeur) {
			$sql = "SELECT `id` FROM `effet` WHERE `type_effet`='".$type."' AND `effet`='".$valeur."' LIMIT 1";
			$this->query($sql);
			return $this->fetch_assoc();
		}

		public function SelectEffetById($id) {
			$sql = "SELECT `type_effet`, `effet` FROM `effet` WHERE `id`='".$id."' LIMIT 1";
			$this->query($sql);
			$array = $this->fetch_assoc();
			return $array;	
		}

		public function InsertEffet($type,$valeur) {
			$sql = "INSERT INTO `effet` (`type_effet`, `effet`) VALUES('".$type."', '".$valeur."');";
			$this->exec($sql);
			return $this->_conn->lastInsertId();
		}
		
		public function SelectActions() {
			$sql = "SELECT * FROM `action`";
			$this->query($sql);
			return $this->fetchAll_assoc();	
		}
		
		public function SelectActionById($id) {
			$sql = "SELECT * FROM `action` WHERE `id`='$id'";
			$this->query($sql);
			return $this->fetchAll_assoc();			
		}
		
		public function SupprimerAction($id) {
			$sql = "DELETE FROM `action` WHERE `id` = '$id'";
			$nb = $this->exec($sql);
			return ($nb==1);
		}
		
                public function SupprimeAlteration($tab) {
                    $query = "DELETE FROM `caracs_alter_mag` WHERE `unique_id` = :uid";
                    $stat = $this->prepare($query);   
                    
                    foreach($tab as $value) {
                        $stat->bindParam(':uid', $value, PDO::PARAM_INT);
                        $this->executePreparedStatement($stat);
                    }
                }

                public function ModifieAlteration($tab)
                {
                    
                    $query = "UPDATE `caracs_alter_mag` SET
                        `alter_pa` = :pa,
                        `alter_pv` = :pv,
                        `alter_mouv` = :mouv,
                        `alter_def` = :def,
                        `alter_att` = :att,
                        `alter_recup_pv` = :recup,
                        `alter_force` = :force,
                        `alter_perception` = :percept,
                        `alter_niv_mag` = :nivmag,
                        `alter_res_mag` = :resmag,
                        `alter_esq_mag` = :esqmag,
                        `alter_res_phy` = :resphy,
                        `nb_tour` = :nbtour,
                        `cassable` = :cassable,
                        `dissipe_mort` = :dissipemort
                        WHERE `unique_id` = :uid";	
                    $stat = $this->prepare($query);                    
                    
                    foreach($tab as $value)
                    {
                        $stat->bindParam(':pa', $value['pa'], PDO::PARAM_INT);
                        $stat->bindParam(':pv', $value['pv'], PDO::PARAM_INT);
                        $stat->bindParam(':mouv', $value['mouv'], PDO::PARAM_INT);
                        $stat->bindParam(':def', $value['def'], PDO::PARAM_INT);
                        $stat->bindParam(':att', $value['att'], PDO::PARAM_INT);
                        $stat->bindParam(':recup', $value['recup'], PDO::PARAM_INT);
                        $stat->bindParam(':force', $value['force'], PDO::PARAM_INT);
                        $stat->bindParam(':percept', $value['percept'], PDO::PARAM_INT);
                        $stat->bindParam(':nivmag', $value['nivmag'], PDO::PARAM_INT);
                        $stat->bindParam(':resmag', $value['resmag'], PDO::PARAM_INT);
                        $stat->bindParam(':esqmag', $value['esqmag'], PDO::PARAM_INT);
                        $stat->bindParam(':resphy', $value['resphy'], PDO::PARAM_INT);
                        $stat->bindParam(':nbtour', $value['nbtour'], PDO::PARAM_INT);
                        $stat->bindParam(':cassable', $value['casse'], PDO::PARAM_INT);  
                        $stat->bindParam(':dissipemort', $value['dissip'], PDO::PARAM_INT);  
                        $stat->bindParam(':uid', $value['uid'], PDO::PARAM_INT);  

                        $this->executePreparedStatement($stat);
 
                    }                     
                }
                
               public function AjouteAlteration($perso_id, $tab)
                {
                    
                    $query = "INSERT INTO `caracs_alter_mag` (`perso_id`, 
                        `alter_pa`, `alter_pv`, `alter_mouv`, `alter_def`, `alter_att`,
                        `alter_recup_pv`, `alter_force`, `alter_perception`, `alter_niv_mag`,
                        `alter_res_mag`, `alter_esq_mag`, `alter_res_phy`, `nb_tour`, `cassable`, `dissipe_mort`) 
                        VALUES
                        (:perso, 
                        :pa, :pv, :mouv, :def, :att, :recup, :force,
                        :percept, :nivmag, :resmag, :esqmag, :resphy,
                        :nbtour, :cassable, :dissipemort)";	
                    
                    $stat = $this->prepare($query);                    
                    
                    $stat->bindParam(':pa', $tab['pa'], PDO::PARAM_INT);
                    $stat->bindParam(':pv', $tab['pv'], PDO::PARAM_INT);
                    $stat->bindParam(':mouv', $tab['mouv'], PDO::PARAM_INT);
                    $stat->bindParam(':def', $tab['def'], PDO::PARAM_INT);
                    $stat->bindParam(':att', $tab['att'], PDO::PARAM_INT);
                    $stat->bindParam(':recup', $tab['recup'], PDO::PARAM_INT);
                    $stat->bindParam(':force', $tab['force'], PDO::PARAM_INT);
                    $stat->bindParam(':percept', $tab['percept'], PDO::PARAM_INT);
                    $stat->bindParam(':nivmag', $tab['nivmag'], PDO::PARAM_INT);
                    $stat->bindParam(':resmag', $tab['resmag'], PDO::PARAM_INT);
                    $stat->bindParam(':esqmag', $tab['esqmag'], PDO::PARAM_INT);
                    $stat->bindParam(':resphy', $tab['resphy'], PDO::PARAM_INT);
                    $stat->bindParam(':nbtour', $tab['nbtour'], PDO::PARAM_INT);
                    $stat->bindParam(':cassable', $tab['casse'], PDO::PARAM_INT);  
                    $stat->bindParam(':dissipemort', $tab['dissip'], PDO::PARAM_INT);  
                    $stat->bindParam(':perso', $perso_id, PDO::PARAM_INT);  

                    $this->executePreparedStatement($stat);
                    
                }                
                
		public function CleanupEffets() {
			// A implémenter
		}
		
		public function AjouterAction($nom, $desc, $cout, $cercle, $niveau, $races, $grade, $galon, $zone, $cible, $lanceur, $effets, $typecible, $typeaction) {
			$query = "INSERT INTO `action` (`id`, `nom`, `description`, `cout`, `cercle_id`, `niv`, `race`, `grade`, `galon`, `zone`, `cible`, `lanceur`, `id_effet`, `type_cible`, `type_action`) VALUES
				('', :nom, :desc, :cout, :cercle, :niveau, :races, :grade, :galon, :zone, :cible, :lanceur, :effets, :typecible, :typeaction)";	
			$stat = $this->prepare($query);
			
			$stat->bindParam(':nom', htmlspecialchars($nom), PDO::PARAM_STR);
			$stat->bindParam(':desc', htmlspecialchars($desc), PDO::PARAM_STR);
			$stat->bindParam(':cout', $cout, PDO::PARAM_INT);
			$stat->bindParam(':cercle', $cercle, PDO::PARAM_INT);
			$stat->bindParam(':niveau', $niveau, PDO::PARAM_INT);
			$stat->bindParam(':races', $races, PDO::PARAM_STR);
			$stat->bindParam(':grade', $grade, PDO::PARAM_INT);
			$stat->bindParam(':galon', $galon, PDO::PARAM_INT);
			$stat->bindParam(':zone', $zone, PDO::PARAM_INT);
			$stat->bindParam(':cible', $cible, PDO::PARAM_INT);
			$stat->bindParam(':lanceur', $lanceur, PDO::PARAM_INT);
			$stat->bindParam(':effets', $effets, PDO::PARAM_STR);
			$stat->bindParam(':typecible', $typecible, PDO::PARAM_STR);
			$stat->bindParam(':typeaction', $typeaction, PDO::PARAM_STR);
			
			return $this->executePreparedStatement($stat);
					
		}
		
		public function ModifieAction($id, $nom, $desc, $cout, $cercle, $niveau, $races, $grade, $galon, $zone, $cible, $lanceur, $effets, $typecible, $typeaction) {
			$query = "UPDATE  `ewo`.`action` SET  
			`nom` =  :nom,
			`description` =  :desc,
			`cout` =  :cout,
			`cercle_id` =  :cercle,
			`niv` =  :niveau,
			`race` =  :races,
			`grade` =  :grade,
			`galon` =  :galon,
			`zone` =  :zone,
			`cible` =  :cible,
			`lanceur` =  :lanceur,
			`id_effet` =  :effets,
			`type_cible` =  :typecible,
			`type_action` =  :typeaction
			
			WHERE  `action`.`id` = :id LIMIT 1" ;
			
			$stat = $this->prepare($query);
				
			$stat->bindParam(':id', $id, PDO::PARAM_INT);
			$stat->bindParam(':nom', $nom, PDO::PARAM_STR);
			$stat->bindParam(':desc', $desc, PDO::PARAM_STR);
			$stat->bindParam(':cout', $cout, PDO::PARAM_INT);
			$stat->bindParam(':cercle', $cercle, PDO::PARAM_INT);
			$stat->bindParam(':niveau', $niveau, PDO::PARAM_INT);
			$stat->bindParam(':races', $races, PDO::PARAM_STR);
			$stat->bindParam(':grade', $grade, PDO::PARAM_INT);
			$stat->bindParam(':galon', $galon, PDO::PARAM_INT);
			$stat->bindParam(':zone', $zone, PDO::PARAM_INT);
			$stat->bindParam(':cible', $cible, PDO::PARAM_INT);
			$stat->bindParam(':lanceur', $lanceur, PDO::PARAM_INT);
			$stat->bindParam(':effets', $effets, PDO::PARAM_STR);
			$stat->bindParam(':typecible', $typecible, PDO::PARAM_STR);
			$stat->bindParam(':typeaction', $typeaction, PDO::PARAM_STR);
			
			return $this->executePreparedStatement($stat);
		}

		public function SelectMedailleListe() {
			$query = "SELECT id, image FROM medailles_liste";
			$this->query($query);
			return $this->fetchAll();			
		}
                
		public function SelectPersosFromString($fragment) {
			$query = "SELECT SQL_CACHE nom FROM persos WHERE nom LIKE ?";
			$this->prepare($query);
			$this->executePreparedStatement(null, array('%'.$fragment.'%'));
			$array = $this->fetchAll_row();
			
			foreach($array as $pseudo) {
				$list[] = '"'.$pseudo[0].'"';
			}
			
			return $list;
		}
		
		public function SelectPnj() {
			$sql = "SELECT persos.id AS id, persos.race_id, persos.grade_id, persos.nom AS nom, persos.date_tour AS date_tour, persos.mortel AS mortel, 
						damier_persos.pos_x AS pos_x, damier_persos.pos_y AS pos_y, cartes.nom AS cartes, utilisateurs.nom AS user
						FROM persos
						LEFT JOIN damier_persos ON damier_persos.perso_id = persos.id
						LEFT JOIN cartes ON damier_persos.carte_id = cartes.id 
						INNER JOIN utilisateurs ON persos.utilisateur_id = utilisateurs.id
						WHERE persos.pnj = 1";
			$this->query($sql);
			return $this->fetchAll_assoc();	
		}	

		public function SelectIa() {
			$sql = "SELECT persos_ia.id AS id, persos.nom AS nom, persos_ia.time AS date_tour, 
						damier_persos.pos_x AS pos_x, damier_persos.pos_y AS pos_y, cartes.nom AS cartes, 
						persos_ia.dna, persos_ia.type
						FROM persos_ia
						JOIN persos ON (persos.id = persos_ia.id)
						LEFT JOIN damier_persos ON damier_persos.perso_id = persos.id
						LEFT JOIN cartes ON damier_persos.carte_id = cartes.id";
			$this->query($sql);
			return $this->fetchAll_assoc();	
		}			

	}
