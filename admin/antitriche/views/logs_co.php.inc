<?php
	include_once('ref.php.inc');
	require_once('config.php.inc');
	
	function view_log($at){
		if(!isset($_SESSION['at_log_log_co']) || $_SESSION['at_log_log_co']+1800 <= time()){//On refresh toute les 30 minutes
			$logger = $at->getATLogger();
			$logger->log(array(ATLogger::LOG_LOGS,'a regard&eacute; les logs de connexion',null));
			$_SESSION['at_log_log_co'] = time();
		}
	}

	function content($at){ 
		if(!isset($_GET['p']) OR !is_numeric($_GET['p']))
			$p = 1;
		else
			$p = $_GET['p'];
			
		$sql 	= 'SELECT SQL_CACHE COUNT(*) as nb FROM `at_log_connexion`;';
		$search	= mysql_query($sql);
		$data	= mysql_fetch_object($search);
		
		$nbPage = ceil($data->nb/30);
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
					<a href="index.php?action=logs_co&amp;p='.$i.'">'.$i.'</a>
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
				<th width="10%">Utilisateur</th>
				<th width="15%">Date</th>
				<th width="10%">IP</th>
				<th width="5%">Cookie</th>
				<th width="60%">Navigateur</th>
			</tr>';
			
			$sql = '
				SELECT
				SQL_CACHE
					u.nom 		as user,
					l.date 		as date,
					c.IP 		as ip,
					c.cookieId	as cookie,
					n.descr		as nav
					
				FROM `at_log_connexion` c
				INNER JOIN `at_log` l
					ON (l.id = c.id)
				INNER JOIN `utilisateurs` u
					ON (u.id = l.compte)
				INNER JOIN `at_navigateur` n
					ON (n.id = c.navigateur)
				ORDER BY
					l.date DESC
				LIMIT '.(($p-1)*AT_RESULT_PAGE_COUNT ).','.AT_RESULT_PAGE_COUNT .'
			';
			
			$search = mysql_query($sql);
			while($log = mysql_fetch_object($search)){
				$r .= '
			<tr>
				<td>'.stripslashes($log->user).'</td>
				<td>'.$log->date.'</td>
				<td>'.$log->ip.'</td>
				<td>'.$log->cookie.'</td>
				<td>'.$log->nav.'</td>
			</tr>
				';
			}
			
			
			$r .='
		</table>'.$pages;
		
		return $r;
	}
	

