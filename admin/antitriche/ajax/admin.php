<?php
	include('ref.php.inc');
	require_once($ref.'admin/antitriche/session_store.php.inc');
	
	$at = get();
	
	if(!isset($_POST['action']))
		die();
	if($_POST['action'] == 'getLog' && isset($_POST['member']) && is_numeric($_POST['member'])){
		echo '
		<table>
			<tr>
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
					
				FROM
					`at_log_at` a,
					`at_log` l,
					`utilisateurs` u
				WHERE
					l.compte = '.$_POST['member'].' AND
					l.id = a.id AND
					u.id = l.compte
				ORDER BY
					l.date DESC
			';
			
			
			
			$search = mysql_query($sql) or die(mysql_error());
			while($log = mysql_fetch_object($search)){
				echo '
			<tr>
				<td>'.$log->date.'</td>
				<td>'.$log->action.'</td>
				<td>'.$log->message.'</td>
			</tr>
				';
			}
			
			
			echo '
		</table>';
	}
	else if($_POST['action'] == 'getName' AND isset($_POST['begin'])){
		$sql = '
				SELECT
					p.nom as name
					
				FROM
					`persos` p,
					`utilisateurs` u
					LEFT JOIN `at_members` m
						ON (m.id = u.id)
				WHERE
					p.utilisateur_id = u.id AND
					m.id IS NULL
				ORDER BY
					p.nom ASC
			';
			
			
		$i = false;
		
		$search = mysql_query($sql) or die(mysql_error());
		while($member = mysql_fetch_object($search)){
			if($i)
				echo ' ';
			else
				$i = true;
			echo str_replace(' ','&nbsp;',$member->name);
		}
	}
?>
