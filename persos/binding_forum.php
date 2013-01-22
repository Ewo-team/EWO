<?php

$binding['default'] = 3;

$binding[1][0] = 9;
$binding[1][3] = 24;
$binding[1][4] = 28;
$binding[1][5] = 23;

$binding[2][0] = 15;

$binding[3][0] = 7;
$binding[3][3] = 22;
$binding[3][4] = 26;
$binding[3][5] = 21;

$binding[4][0] = 8;
$binding[4][3] = 19;
$binding[4][4] = 27;
$binding[4][5] = 20;

$forum = bdd_connect('forum');

function FlushPerso($id,$default) {
	global $forum;
	
	mysql_query("UPDATE phpbb_users SET group_id = " . $default ." WHERE user_id = ".$id,$forum); 
	mysql_query("DELETE FROM phpbb_user_group WHERE user_id = ".$id,$forum);

}

function SelectId($pseudo) {
	global $forum;
	
	$pseudo = strtolower($pseudo);
	
	$result = mysql_query("SELECT user_id FROM ewo_forum.phpbb_users WHERE username_clean = '$pseudo' LIMIT 1",$forum);
	
	if($result) {
		$id = mysql_fetch_row($result);
		
		return $id[0];
	}
	return false;
}

/**
 * Tente de lier un compte forum à un perso. Si cela à marché (donc que le perso existe sur le forum), true est renvoyé. Sinon false
 */
function LierPerso($pseudo,$grade,$camp,$hash) {
	global $forum;
		
	$id = SelectId($pseudo);

	if($id) {

		mysql_query("UPDATE phpbb_users SET user_password = '$hash' WHERE user_id=$id",$forum);

		// Procéder au changements
		ChangePersoGrade($id,$camp,$grade);
	
		return true;
	
	}
	
	return false;
	
}

function ChangeNom($oldpseudo,$newpseudo) {
	global $binding,$forum;
}

function ChangePersoGrade($id,$newcamp,$newgrade) {
	global $binding,$forum;
	
	FlushPerso($id,$binding['default']);
	 
	mysql_query("INSERT INTO phpbb_user_group (group_id, user_id, group_leader, user_pending) VALUES (" . $binding[$newcamp][0] . ", $id, 0, 0);",$forum);
	
	if($newgrade >= 3 && isset($binding[$newcamp][3])) {
		// Groupe G3
		mysql_query("INSERT INTO phpbb_user_group (group_id, user_id, group_leader, user_pending) VALUES (" . $binding[$newcamp][3] . ", $id, 0, 0);",$forum);
	}
	
	if($newgrade >= 4 && isset($binding[$newcamp][4])) {
		// Groupe G4
		mysql_query("INSERT INTO phpbb_user_group (group_id, user_id, group_leader, user_pending) VALUES (" . $binding[$newcamp][4] . ", $id, 0, 0);",$forum);
	}

	if($newgrade >= 5 && isset($binding[$newcamp][5])) {
		// Groupe G5
		mysql_query("INSERT INTO phpbb_user_group (group_id, user_id, group_leader, user_pending) VALUES (" . $binding[$newcamp][5] . ", $id, 0, 0);",$forum);
	}	
	
	// Recherche de la couleur du groupe
	$result = mysql_query("SELECT group_colour FROM phpbb_groups WHERE group_id=" . $binding[$newcamp][0], $forum);
	$couleur = mysql_fetch_row($result);
		
	// Groupe par défaut	
	$sql = "UPDATE phpbb_users SET group_id = ".$binding[$newcamp][0].", user_colour='".$couleur[0]."' WHERE user_id = $id LIMIT 1";
	mysql_query($sql,$forum);
	
	// Couleurs dernier posteur et premier posteurs
	mysql_query("UPDATE phpbb_topics SET topic_first_poster_colour = '".$couleur[0]."' WHERE topic_poster = $id");
	mysql_query("UPDATE phpbb_topics SET topic_last_poster_colour = '".$couleur[0]."' WHERE topic_last_poster_id = $id");
	mysql_query("UPDATE phpbb_forums SET forum_last_poster_colour = '".$couleur[0]."' WHERE forum_last_poster_id = $id");
}

?>