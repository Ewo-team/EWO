<?php


class Effet {
	public $type;
	public $valeur;
	
	public function id() {
		$conn = \admin\AdminDAO::getInstance();
		$id = $conn->SelectIdEffet($this->type, $this->valeur);
		if(isset($id) && $id!=false) {
			return $id['id'];
		} else {
			return $conn->InsertEffet($this->type, $this->valeur);
		}
	}
	
	public function __construct($type, $valeur) {
		$this->type = $type;
		$this->valeur = $valeur;
	}
}

?>
