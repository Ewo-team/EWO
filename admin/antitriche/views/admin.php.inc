<?php
	include_once('ref.php.inc');

	function view_log($at){
		
	}

	function content($at){ 
		if(isset($_POST['nameAdd']) && isset($_POST['lvl']) AND is_numeric($_POST['lvl']) AND $_POST['lvl'] >= 1 AND $_POST['lvl'] < $at->getLevel()){
			return contentBase($at,true,contentAdd($at));
		}
		else if(isset($_POST['virer']) && isset($_POST['id']) AND is_numeric($_POST['id'])){
			return contentBase($at,true,contentVirer($at));
		}
		else if(isset($_POST['mod']) && isset($_POST['id']) AND is_numeric($_POST['id'])){
			return contentBase($at,true,contentUpdate($at));
		}
		return contentBase($at,false,'');
	}
	
	function contentBase($at,$v,$c){
		$v1 = '';
		$v2 = 'visibility:hidden;';
		if(!$v) {
			$v1 = 'visibility:hidden;';
			$v2 = 'visibility:hidden;';
		}
		
		$panel = '
			<section style="min-width : 600px;'.$v1.'" id="sectionAdmin" class="blueSection">
				<header>Action</header>
				<div id="notifAdmin">'.$c.'</div>
				<div id="formAdmin" style="overflow:auto;'.$v2.'">
					<form method="post" action="index.php?action=admin">
						<h3></h3>
						<label>
							Niveau	:
							<select name="lvl" onchange="lvlChanged(this.value);">
								'.getLevelList($at).'
							</select>
						</label><br />
					</form>
				</div>
				<div id="logArea" style="overflow:auto;">
				
				</div>
			</section>
		';
		$liste =  '
		<section style="margin-left	: 100px;" class="redSection">
			<header>
				Liste des membres (admins exclus)
			</header>
			<div>
				<ul>
					<li class="link" onclick="add();">Ajouter un nouveau membre</li>';
			//récupération de la liste des ats
			$sql = '
				SELECT
					u.id as id,
					u.nom as name,
					m.lvl as level
				FROM
					`at_members` m
				INNER JOIN
					`utilisateurs` u
					ON (u.id = m.id)
				WHERE
					m.lvl < '.$at->getlevel().' 
				ORDER BY u.nom ASC
			';
			
			$search = mysql_query($sql);
			while($member = mysql_fetch_object($search)){
				$liste .= '
					<li class="link" onclick="display('.$member->id.',\''.$member->name.'\','.$member->level.')">
						'.stripslashes($member->name).' (nv : '.$member->level.')
					</li>';
			}
		
			$liste .='
				</ul>
			</div>
		</section>
		';
		
		return $liste.$panel.'<div class="spacer"></div>';
	}
	
	function getLevelList($at){
		if($at->getLevel() == 4)
			return '			
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>';
		return '
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>';
	}
	
	function contentAdd($at){
		$sql = '
			SELECT
				u.id		as id,
				u.droits	as rights,
				u.nom		as name
			FROM
				`utilisateurs` u
			INNER JOIN
				`persos` p
				ON(
					p.utilisateur_id = u.id AND
					p.nom="'.addslashes($_POST['nameAdd']).'"
				)
		';
		$search = mysql_query($sql) or die(mysql_error());
		if(!($data = mysql_fetch_object($search)))
			return 'Erreur<pre>'.$sql.'</pre>'.mysql_error();
		

		if($data->rights[3] ==1 ||  $data->rights[1] == 1)
			return '<strong>Cet utilisateur dispose d&eacute;j&agrave; les droits d\'AT</strong>';
		
		$data->rights[3] = 1;
		
		$sql = '
			UPDATE `utilisateurs`
			SET
				droits = "'.$data->rights.'"
			WHERE
				id = '.$data->id.';
		';
		
		mysql_query($sql);
		
		$sql = '
			INSERT INTO `at_members`
			(`id`,`lvl`)
			VALUES('.$data->id.','.$_POST['lvl'].')
		';
		mysql_query($sql);
		
		$at->getATLogger()->log(AT_ACTION_AJOUTER,'a ajout&eacute; '.$data->name.' &agrave; l\'AT',$at->getId());
		
		return '<strong>Utilisateur ajouté</strong>';
	}
	
	function contentVirer($at){
		$sql = '
			SELECT
				u.id		as id,
				u.droits	as rights,
				m.lvl		as level
			FROM
				`utilisateurs` u,
				`at_members` m
			WHERE
				u.id ="'.addslashes($_POST['id']).'" AND
				m.id = u.id
		';
		$search = mysql_query($sql) or die(mysql_error());
		if(!($data = mysql_fetch_object($search)))
			return 'Erreur';

		if($data->rights[3] !=1 || $data->level >= $at->getLevel())
			return '<strong>Vous ne pouvez pas virer ce membre</strong>';
		
		$data->rights[3] = 0;
		
		$sql = 'UPDATE `utilisateurs` SET droits = "'.$data->rights.'" WHERE id='.$data->id;
		mysql_query($sql);
		$sql = 'DELETE FROM `at_members` WHERE id = '.$data->id;
		mysql_query($sql);
		
		$at->getATLogger()->log(AT_ACTION_VIRER,'a supprim&eacute; '.$data->name.' de l\'AT',$at->getId());
		
		return 'Membre supprim&eacute;';
	}
	
	function contentUpdate($at){
		$sql = '
			SELECT
				u.id		as id,
				u.droits	as rights,
				m.lvl		as level,
				u.nom 		as name
			FROM
				`utilisateurs` u,
				`at_members` m
			WHERE
				u.id ="'.addslashes($_POST['id']).'" AND
				m.id = u.id
		';
		$search = mysql_query($sql) or die(mysql_error());
		if(!($data = mysql_fetch_object($search)))
			return 'Erreur';

		if($data->rights[3] !=1 || $data->level >= $at->getLevel())
			return '<strong>Vous ne pouvez pas modifier le status de ce membre</strong>';
		
		
		$sql = 'UPDATE `at_members` SET lvl = "'.$_POST['lvl'].'" WHERE id='.$data->id;
		mysql_query($sql);
		
		
		$at->getATLogger()->log(AT_ACTION_AJOUTER,'a modifi&eacute; les informations de '.$data->name.', passage au niveau '.$_POST['lvl'],$at->getId());
		
		return 'Modification effectu&eacute;e';
	}
?>
