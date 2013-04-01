<?php

	class Parser{
		
		/*
		 * message	: chaine à parser
		 * perso	: objet représentant le perso concerné
		 */
		
		static public function parseMessage($message,$perso){
			$tbl = explode('$id->',$message);

			$j = count($tbl);
			$r = $tbl[0];
			for($i=0;$i  < $j;++$i){
				
				$head 	= preg_replace('#^([a-zA-Z0-9]*) .*#','$1',$tbl[$i]); //attribut à remplacer
				$tail	= substr($tbl[$i],strlen($head));
				
				//Aller chercher la valeur dans la base de données
				
				$r .= $perso->$head.$tail;
			}
			
			return $r;
		}

	}

