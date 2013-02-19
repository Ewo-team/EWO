<?php

namespace jeu\legion;

/**
 * Classe de chargement des nom de types et d'alignement
 *
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 * @package legions
 */


	class NameManager{

		private $typeNames          = array();
		private $alignementNames    = array();

		public function __construct(){
			if(\conf\VariableStorage::Exists(LegionConfig::typeApcName) && \conf\VariableStorage::Exists(LegionConfig::alignementApcName))
                            $this->loadValuesFromApc();
			else
                            $this->loadValuesFromBdd();
		}


		//Les valeurs doivent exister dans APC
		private function loadValuesFromApc(){
			$this->typeNames 	= \conf\VariableStorage::Consulte(LegionConfig::typeApcName);
			$this->alignementNames	= \conf\VariableStorage::Consulte(LegionConfig::alignementApcName);
		}

		private function loadValuesFromBdd(){
			//Construction à partir de la base de donnée
			$this->typeNames 		= array();
			$this->alignementNames	= array();

			$sql 	= LegionDAO::getInstance();
			$query	= '
				SELECT
					id 	as id,
					nom	as nom
				FROM `faction_types`';

			$stmt = $sql->query($query);
			while($entree = $sql->fetch($stmt,\PDO::FETCH_OBJ)){
				$this->typeNames[$entree->id] = $entree->nom;
			}

			$query	= '
				SELECT
					id      as id,
					label	as nom
				FROM `faction_alignement`';

			$stmt = $sql->query($query);
			while($entree = $sql->fetch($stmt,\PDO::FETCH_OBJ)){
				$this->alignementNames[$entree->id] = $entree->nom;
			}
			$this->store();
		}

		private function store(){
			\conf\VariableStorage::Sauve(LegionConfig::typeApcName,$this->typeNames);
			\conf\VariableStorage::Sauve(LegionConfig::alignementApcName,$this->alignementNames);
		}

		//L'id doit être dans le tableau !
		public function getTypeName($id){
                    return $this->typeNames[$id];
		}

		//L'id doit être dans le tableau !
		public function getAlignementName($id){
                    return $this->alignementNames[$id];
		}

                public function getTypes(){
                    return $this->typeNames;
                }

                public function getAlignements(){
                    return $this->alignementNames;
                }
	}
?>
