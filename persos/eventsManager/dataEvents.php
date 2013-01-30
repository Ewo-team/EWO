<?php
/**
 * Couche data de la gestion des évènements.
 */


class DataEvents{

	public function __construct(){
		$ewo_bdd = bdd_connect('ewo');
		/*@include("../conf/connect.conf.php");
		$ewo_bdd = mysql_connect($_SERVEUR,$_USER,$_PASS,'events');
		mysql_select_db($_BDD,$ewo_bdd);*/
	}

	public function __destruct(){
		//$ewo_bdd = mysql_close();
	}

	private function query($sql){
		$resultat = mysql_query($sql) or die (mysql_error());
		return true;
	}

	private function getArray($sql){
		$resultat = mysql_query($sql) or die (mysql_error());
		
		$i = 0;
		while($it=  mysql_fetch_assoc($resultat)){
			$resultArray[$i++] = $it;
		}
		
		return $resultArray;
	}

	/**
	 *
	 * Permet de commiter un évènement dans la bdd
	 * @param text $type
	 * @param array $data
	 * @return unknown_type
	 */
	public function addEvent($persoID, $type, $data){

		$sql = "INSERT INTO evenement (date, perso_id, evenement_type_id, champs ) VALUES (
								'".date('Y-m-d-H:i:s')."', '$persoID', '$type', '$data');";

		$request = $this->query($sql);
		return $request;
	}

	/**
	 *
	 * Permet de récupérer les Events
	 * @param array $idList				list des matricule pour lesquels on doit récupérer les évènements
	 * @param array $filterTypeList		Ramener un/des types d'évènement(s) particulier si null on ramène tout
	 * @param bool $private				Permet de définir le niveau de détail des évènements à ramener (évènements publics ou privés...par défaut on ramène tout)
	 * @param string $orderby			Permet de faire des tris particuliers directement depuis le SQL
	 * @param int $limitStart			Numéro du résultat à partir duquel on part
	 * @param int $limit				Nombre maximum de résultat
	 * @return unknown_type
	 */
	public function getEvent($idList = null,$filterTypeList=null,$private=true,$orderby='perso_id DESC, date ASC',$limitStart = 0,$limit = 100){

		// On test toutes les données en entrée...
		$test = true;

		// La liste des ID doit être un tableau
		if(!is_array($idList) && $idList !== null){
			$test = false;
		}
		else{
			if($idList !== null){
				foreach($idList as $key => $value){
					$idList[$key] = strip_tags($value);
				}
			}
		}

		// Les filtres doivent être soit null soit dans un tableau
		if(!is_array($filterTypeList) && $filterTypeList !== null){
			$test = false;echo ' unlol';
		}
		else{
			if($filterTypeList !== null){
				foreach($filterTypeList as $key => $value){
					$filterTypeList[$key] = strip_tags($value);
				}
			}
		}

		// On fait un petit controle sur orderBy
		$orderby = strip_tags($orderBy);

		// On fait un petit control sur les limit....
		if(!is_int($limitStart) || !is_int($limit)){
			$test = false;
		}

		// Si tout est OK on peut commencer à aller toper les datas.
		if($test === true){
			// On fait le SELECT de base
			$sql = '
			SELECT evenement.*, persos.nom FROM evenement
			LEFT JOIN  persos ON evenement.perso_id = persos.id';

			// Si idlist n'est pas null, on récupère les évènements pour une liste d'ID déterminée.
			
			if($idList !== null || $filterTypeListe !== null){
				$sql.=' WHERE ';
				if($idList !== null){

					$whereIDList = '';
					foreach($idList as $value){
						$whereIDList.= ' OR perso_id = '.$value;
					}
				}
				// On place le AND
				$whereIDList .= ' AND ';
				// On place les filtres sur les types d'évènements
				if($filterTypeList !== null){
					foreach($filterTypeList as $value){
						$whereIDList.= 'OR evenement_type_id = '.$value;
					}
				}
			}
			// On enlève le "AND" et la virgule du début si jamais il n'y avait pas de clause WHERE complète
			$whereIDList = trim($whereIDList,' AND ');
			$sql.= trim($whereIDList,' OR');

			// Order
			if($orderBy != ''){
				$sql.= ' ORDER BY'.$orderby;
			}
			// Limit
			$sql.= ' LIMIT '.$limitStart.','.$limit;

			// On effectue la requete.
			$request = $this->getArray($sql);

			return $request;
		}

	}

}

?>
