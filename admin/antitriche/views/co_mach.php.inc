<?php
	include_once('ref.php.inc');

	function view_log($at){
		if(!isset($_SESSION['at_log_co_mach']) || $_SESSION['at_log_co_mach']+1800 <= time()){//On refresh toute les 30 minutes
			$logger = $at->getATLogger();
			$logger->log(0,'a utilis&eacte; l\'outil automatique par IP',null);
			$_SESSION['at_log_co_mach'] = time();
		}
	}

	function content(){
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
					<a href="index.php?action=co_mach&amp;p='.$i.'">'.$i.'</a>
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
				<th width="10%">Machine</th>
				<th width="10%">Utilisateurs</th>
			</tr>';
			
			$sql = '
				SELECT
				SQL_CACHE
					c.cookieId 	as cookieId
				FROM
					`at_log_connexion` c	
				INNER JOIN
					`at_log` l
				ON
					l.id = c.id
				HAVING
					COUNT(l.compte) > 1
				ORDER BY
					c.cookieId ASC
				LIMIT '.(($p-1)*30).',30
			';
			
			$search = mysql_query($sql);
			while($log = mysql_fetch_object($search)){
				$sql = '
					SELECT
						u.nom	as name
					FROM
						`utilisateurs` u,
						`at_log_connexion` c,
						`at_log` l
					WHERE
						l.id = c.id AND
						u.id = l.compte AND
						c.cookieId = "'.$log->cookieId.'"
					GROUP BY
						u.id
				';
				$search2 = mysql_query($sql);
				$l = array();
				while($user = mysql_fetch_object($search2))
					array_push($l,$user->name);
				if(count($l) > 1){
					$r .= '
			<tr>
				<td>'.$log->cookieId.'</td>
				<td>
					<ul style="margin : 0px;">';
					foreach($l as $name)
						$r .= '
						<li>'.stripslashes($name).'</li>';
					$r .= '
					</ul>
			</tr>
				';
				}
				
				
			}
			
			
			$r .='
		</table>'.$pages;
		
		return $r;
	}
?>
