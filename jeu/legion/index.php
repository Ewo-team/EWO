<?php
/**
 * Légion - Structure
 *
 * @author Herbomez Benjamin <benjamin.herbomez@gmail.com>
 * @version 1.0
 */
	$root_url = '../../';
	require_once $root_url.'conf/master.php';
	include($root_url.'/template/header_new.php');
	include('config.php.inc');
	include('fonctions.php.inc');


	//Il faut être connecté
	ControleAcces('utilisateur',1);
	
        checkLegion();
        
	if(isset($_GET['p']) && array_key_exists($_GET['p'],$pages)){
		$p = $pages[$_GET['p']];
	}
	else
		$p = $pages[0];
		
        
	
	echo '
	<link rel="stylesheet" href="',$root_url,'/jeu/legion/style.css" type="text/css" />
	<table style="width:98%;height:100%;margin:auto;border-spacing:0px;">
		<tr>
			<td id="legionLeftPanel" valign="top">
			';
			include('liste.php.inc');
			echo '
			</td>
			<td id="legionRightPanel" valign="top">
				';
				include($p.'.php.inc');
				echo'
			</td>
			
		</tr>
	</table>
	';
	
	include($root_url.'/template/footer_new.php');
?>
