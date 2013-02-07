<?php 

namespace persos;
use \conf\ConnecteurDAO as ConnecteurDAO;

class PersosDAO extends ConnecteurDAO {

	public function SelectPersoById($id) {
		$this->SelectData(array(array('operation' => '=', 'variable' => 'persos.id', 'value' => $id)));
		return $this->fetch_assoc();
	}
	
	public function SelectPersoByName($name) {
		$this->SelectData(array(array('operation' => 'LIKE', 'variable' => 'persos.nom', 'value' => $name)));
		return $this->fetch_assoc();
	}
	
	private function SelectData($array = null) {
		$bind = array();
		$sql = "SELECT persos.id AS id_personnage, persos.nom AS nom_perso, races.color AS couleur, races.nom AS nom_race   
		FROM persos 
		INNER JOIN races 
		ON persos.race_id = races.id WHERE 1=1";
		if($array) {
			foreach($array as $ligne) {
				$id = md5($ligne['variable']);
				$sql .= ' AND ' . $ligne['variable'] . ' ' . $ligne['operation'] . ' :' .$id;
				$bind[$id] = $ligne['value'];
			}
		}

        $this->prepare($sql);
        $this->executePreparedStatement(null, $bind);			

	}	
	
}