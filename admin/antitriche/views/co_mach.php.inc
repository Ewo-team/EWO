<?php
	include_once('ref.php.inc');
	include('config.php.inc');

	function view_log($at){
		if(!isset($_SESSION['at_log_co_mach']) || $_SESSION['at_log_co_mach']+1800 <= time()){//On refresh toute les 30 minutes
			$logger = $at->getATLogger();
			$logger->log(array(0,'a utilis&eacte; l\'outil automatique par IP',null));
			$_SESSION['at_log_co_mach'] = time();
		}
	}

	function content(){
		if(!isset($_GET['p']) OR !is_numeric($_GET['p']))
			$p = 1;
		else
			$p = $_GET['p'];
			
		$sql 	= '
			SELECT (COUNT(cookieId)/'.AT_RESULT_PAGE_COUNT.') as nb 
			FROM (SELECT 
				ac.cookieId as cookieId
				FROM  `at_log_connexion` ac
				INNER JOIN `at_log` al
					ON (al.id = ac.id)
				GROUP BY ac.cookieId	
				HAVING COUNT(DISTINCT al.compte) > 1) as tbl
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
			elseif($i > $p+ceil($d/2))
				$c = 'blueSection';
			else
				$c = 'greenSection';
			$pages .= '
				<span class="'.$c.'">
					<a href="index.php?action=co_mach&amp;p='.$i.'">'.$i.'</a>
				</span>
			';
		}	
		
		$pages = '
			<div style="text-align	: center">
				'.$pages.'
			</div>
		';
	
		$r = '<table>
			<tr>
				<th width="10%">IP</th>
				<th width="10%">Utilisateurs</th>
			</tr>';
			
			$sql = '
				SELECT
					ac.cookieId as cookieId,
					GROUP_CONCAT(DISTINCT u.nom) as names,
					COUNT(DISTINCT u.nom) as count
				FROM  `at_log_connexion` ac
				INNER JOIN `at_log` al
					ON (al.id = ac.id)
				INNER JOIN `utilisateurs` u
					ON (u.id = al.compte)
				GROUP BY ac.cookieId
				HAVING count > 1
				LIMIT '.(($p-1)*AT_RESULT_PAGE_COUNT ).','.AT_RESULT_PAGE_COUNT .'
			';
			$search = mysql_query($sql) or die(mysql_error());
			while($log = mysql_fetch_object($search)){
					$r .= '
			<tr>
				<td>'.$log->cookieId.'</td>
				<td>
					<ul style="margin : 0px;">';
					foreach(explode(',', $log->names) as $name)
						$r .= '
						<li>'.stripslashes($name).'</li>';
					$r .= '
					</ul>
			</tr>
				';
			}
			
			$r .='
		</table>'.$pages;
		
		return $r;
	}
?>
