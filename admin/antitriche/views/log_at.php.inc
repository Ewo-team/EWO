<?php
	include_once('ref.php.inc');

	function view_log($at){
		if(!isset($_SESSION['at_log_log_at']) || $_SESSION['at_log_log_at']+1800 <= time()){
			$logger = $at->getATLogger();
			$logger->log(array(0,'a regard&eacute; les logs de l\'AT',null));
			$_SESSION['at_log_log_at'] = time();
		}
	}

	function content(){
		if(!isset($_GET['p']) OR !is_numeric($_GET['p']))
			$p = 1;
		else
			$p = $_GET['p'];
			
		$sql 	= 'SELECT SQL_CACHE COUNT(*) as nb FROM `at_log_at`;';
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
					<a href="index.php?action=log_at&amp;p='.$i.'">'.$i.'</a>
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
				<th>Membre</th>
				<th>Date</th>
				<th>Action</th>
				<th>Message</th>
			</tr>';
			
			$sql = '
				SELECT
					u.nom as member,
					l.date as date,
					a.action as action,
					a.message as message
					
				FROM `at_log_at` a
				INNER JOIN `at_log` l
					ON (l.id = a.id)
				INNER JOIN `utilisateurs` u
					ON (u.id = l.compte)
				ORDER BY
					l.date DESC
				LIMIT '.(($p-1)*30).',30
			';
			
			$search = mysql_query($sql);
			while($log = mysql_fetch_object($search)){
				$r .= '
			<tr>
				<td>'.stripslashes($log->member).'</td>
				<td>'.$log->date.'</td>
				<td>'.$log->action.'</td>
				<td>'.$log->message.'</td>
			</tr>
				';
			}
			
			
			$r .='
		</table>'.$pages;
		
		return $r;
	}
?>
