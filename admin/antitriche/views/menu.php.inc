<?php
	/*
	 * Construit le menu en fonction de l'AT passé en paramètre
	 */
	 
	include_once('ref.php.inc');
	include($ref.'admin/antitriche/class/tools.php.inc');
	 
	function menu($at){
		global $at_items;
		return '
				<ul>
					'.menuRec($at->getLevel(),$at_items,'').'
					<li>
						<a href="../../">Retour au site</a>
					</li>
				</ul>';
	}
	
	function menuRec($lvl,$tbl,$tab){
		$r = '';
		
		foreach($tbl as $val){
			if(is_array($val))
				$item = $val[0];
			else
				$item = $val;
				
			if($item->getLevel() > $lvl)//Pas le droit d'y toucher
				continue;	
			
			$r .= '
					'.$tab.'<li>';
						
						if(is_array($val))
							$r .= '
						'.$tab.$item->getName().'
						'.$tab.'<ul>
							'.menuRec($lvl,$val[1],$tab.'	').'
						'.$tab.'</ul>
						';
						else
							$r .= '
						'.$tab.'<a href="index.php?action='.$item->getAcronym().'">
							'.$tab.$item->getName().'
						'.$tab.'</a>';
						$r .='
					'.$tab.'</li>';
		}
		return $r;
	}
?>
