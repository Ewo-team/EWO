<?php
session_start();
$root_url = "..";
include ($root_url."/conf/master.php");

bdd_connect('ewo');

$data = $_POST;

foreach ($data as $row) 
{
	/* split up in segments */
	$segments = explode(':', $row);
	
	/* define the column */
	$column_id = $segments[0];
	
	/* the blocks */
	$blocks = explode(',', $segments[1]);
	
	/* we take each block */
	foreach ($blocks as $order_id => $block_id)
	{
		/* check if the block is already present in the database */
		$block_exists = mysql_fetch_row(mysql_query("SELECT * FROM blocks WHERE block_id = '{$block_id}' AND perso_id='".$_SESSION['persos']['current_id']."'"));
		
		/* if not, we insert it */
		if ($block_exists == FALSE) 
		{
			if (empty($block_id)) return;
			
			mysql_query("INSERT INTO blocks (block_id, perso_id, column_id, order_id) VALUES ('{$block_id}', '".$_SESSION['persos']['current_id']."','{$column_id}', {$order_id})");
			echo "Moved block: {$block_id} to column: {$column_id} and updated rank to: {$order_id}<br />";
		}
		/* or else we update it */
		else 
		{
			if (empty($block_id)) return;
			
			mysql_query("UPDATE blocks SET block_id = '{$block_id}', column_id = '{$column_id}', order_id = {$order_id} WHERE unique_id = ".$block_exists[0]." AND perso_id='".$_SESSION['persos']['current_id']."'");
			echo "Moved block: {$block_id} to column: {$column_id} and updated rank to: {$order_id}<br />";
		}
	}
	
}

?>
