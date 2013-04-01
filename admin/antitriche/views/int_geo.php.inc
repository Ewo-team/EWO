<?php
	include_once('ref.php.inc');
	include('config.php.inc');

	function view_log($at){
		if(!isset($_SESSION['at_log_int_geo']) || $_SESSION['at_log_int_geo']+1800 <= time()){//On refresh toute les 30 minutes
			$logger = $at->getATLogger();
			$logger->log(array(ATLogger::LOG_AUTO_INTER_GEO,'a utilis&eacte; l\'outil automatique d\'interaction g&eacute;ographique',null));
			$_SESSION['at_log_int_geo'] = time();
		}
	}

	function content(){
		if(!isset($_GET['p']) OR !is_numeric($_GET['p']))
			$p = 1;
		else
			$p = $_GET['p'];
			
		$sql 	= '
			SELECT (COUNT(id)/'.AT_RESULT_PAGE_COUNT.') as nb 
			FROM at_log_inter_geo
			;
		';
		$search	= mysql_query($sql) or die(mysql_error());
		$data	= mysql_fetch_object($search);
		
		$nbPage = ceil($data->nb);
		if($p > $nbPage)
			$p = $nbPage;
			
			
		$s = $p - 7;
		if($s < 1)
			$s = 1;
		$e = $p + 7;
		if($e > $nbPage)
			$e = $nbPage;
		$d = round(($e - $s)/3);
		
		$pages = '';	
		for($i = $s;$i <= $e;++$i){
			if($i < $p-ceil($d/2))
				$c = 'redSection';
			elseif($i >= $p+ceil($d/2))
				$c = 'blueSection';
			else
				$c = 'greenSection';
			$pages .= '
				<span class="'.$c.'">
					<a href="index.php?action=int_geo&amp;p='.$i.'">'.$i.'</a>
				</span>
			';
		}	
		
		$pages = '
			<div style="text-align	: center">
				'.$pages.'
			</div>
		';
		
		$r = $pages.'
		<table>
			<tr>
				<th width="10%">Compte</th>
				<th width="10%">Utilisateurs</th>
				<th width="10%">Date</th>
			</tr>';
			
			$sql = '
				SELECT
					p1.id as mat1,
					p1.nom as nom1,
					p2.id as mat21,
					p2.nom as nom2,
					u.nom as user,
					al.date
				FROM  `at_log_inter_geo` ig
				INNER JOIN `persos` p1
					ON (p1.id = ig.id_perso1)
				INNER JOIN `persos` p2
					ON (p2.id = ig.id_perso2)
				INNER JOIN `utilisateurs` u
					ON (u.id = p1.utilisateur_id)
				INNER JOIN `at_log` al
					ON (ig.id = al.id)
					ORDER BY al.date DESC
				LIMIT '.(($p-1)*AT_RESULT_PAGE_COUNT ).','.AT_RESULT_PAGE_COUNT .'
			';
			$search = mysql_query($sql) or die(mysql_error());
			while($log = mysql_fetch_object($search)){
					$r .= '
			<tr>
				<td>'.$log->user.'</td>
				<td>
				</td>
				<td>'.$log->date.'</td>
			</tr>
				';
			}
			
			$r .='
		</table>'.$pages;
		
		return $r;
	}

