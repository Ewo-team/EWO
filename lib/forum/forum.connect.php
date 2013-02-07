<?php

/**
* Permet d'afficher les titre du forum.
*/
function topic_forum($forum_id){
global $root_url;
		$ewo_forum = bdd_connect('forum');

		$sql = "SELECT forum_id,topic_id, topic_time, topic_title, topic_views, topic_replies, topic_poster, topic_first_poster_name, topic_first_poster_colour, topic_last_post_id, topic_last_poster_id, topic_last_poster_name, topic_last_poster_colour, topic_last_post_time
			 FROM phpbb_topics WHERE forum_id='".$forum_id."'" .
					' ORDER BY topic_time DESC ' .
					' LIMIT 0 , 5 ';

		$result = mysql_query ($sql);
		mysql_close($ewo_forum);

		while($row = mysql_fetch_array ($result))	{							
			echo '<li>';
			echo "<a href='".$root_url."/forum/viewtopic.php?f=".$forum_id."&t=".$row['topic_id']."'>".$row['topic_title']."</a>";
			echo '</li>';
		}
}
/**
* Permet d'afficher les titre du blog.
*/
function topic_blog($forum_id){
global $root_url;
		$ewo_blog = bdd_connect('blog');
	
		$sql3 = "SELECT post_content, post_name, post_title, post_date FROM wp_posts WHERE post_type = 'post' ORDER BY post_date DESC LIMIT 5";

		$res = mysql_query($sql3);
		mysql_close($ewo_blog);

		while($row3 = mysql_fetch_array ($res))	{
			// création de l'url en fonction du rewrite url du blog ici /année/moi/jour/titre
				$date = explode (' ',$row3['post_date']);
					$year = $date[0];
				$year = explode ('-', $year);
					$annee = $year[0];
					$mois = $year[1];
					$jour = $year[2];
				$lienblog = $annee.'/'.$mois.'/'.$jour;							
			echo '<li>';
			echo "<a href='http://blog.ewo-le-monde.com/$lienblog/".$row3['post_name']."'>".$row3['post_title']."</a>";
			echo '</li>';
		}
}
function annonce_mixtes($nb) {
        
	$tableau = array();

	//
	// Blog
	//
		$ewo_blog = bdd_connect('blog');
	
		$res = mysql_query("SELECT post_content, post_name, post_title, post_author, post_date FROM wp_posts WHERE post_type = 'post' ORDER BY post_date DESC LIMIT ".$nb);
		
		while($ligne = mysql_fetch_array ($res))	{
			
			// création de l'url en fonction du rewrite url du blog ici /année/moi/jour/titre
				$date = explode (' ',$ligne['post_date']);
				$year = $date[0];
				$year = explode ('-', $year);
				$annee = $year[0];
				$mois = $year[1];
				$jour = $year[2];
				$lienblog = $annee.'/'.$mois.'/'.$jour;			
				$time = strtotime($ligne['post_date']);
                                $tableau[$time] = array();
                                $tableau[$time]['titre'] = $ligne['post_title'];
                                $tableau[$time]['corps'] = nl2br(tronquage($ligne['post_content'],300));
                                $tableau[$time]['lien'] = "http://blog.ewo-le-monde.com/$lienblog/".$ligne['post_name'];
		}
		mysql_close($ewo_blog);
		
	//
	// Forum
	//
	
		$ewo_forumannonce = bdd_connect('forum');

		$sql = "SELECT forum_id,topic_id, topic_time, topic_title, topic_views, topic_replies, topic_poster, topic_first_poster_name, topic_first_poster_colour, topic_last_post_id, topic_last_poster_id, topic_last_poster_name, topic_last_poster_colour, topic_last_post_time
			 FROM phpbb_topics WHERE forum_id= 2 AND topic_type != 0" .
					' ORDER BY topic_time DESC ' .
					' LIMIT 0 , '.$nb.' ';
		$result = mysql_query ($sql);

		while($ligne = mysql_fetch_array ($result))	{	
			$sql1 = "SELECT post_text, poster_id
			 FROM phpbb_posts WHERE forum_id=2 AND topic_id='".$ligne['topic_id']."'" .
					' ORDER BY post_time ASC ' .
					' LIMIT 0 , 1 ';	
		
			$resulta = mysql_query ($sql1);
			$row1 = mysql_fetch_array ($resulta);

			$texte = bbcode_format($row1['post_text']);

			$time = $ligne['topic_time'];
			
                        $tableau[$time] = array();
                        $tableau[$time]['titre'] = $ligne['topic_title'];
                        $tableau[$time]['corps'] = tronquage($texte,500);
                        $tableau[$time]['lien'] = SERVER_URL . "/forum/viewtopic.php?f=2&t=".$ligne['topic_id'];                        
		}
		mysql_close($ewo_forumannonce);
		
		$ewo_bdd = bdd_connect('ewo');	
		
                krsort($tableau);
		reset($tableau);
                
                return $tableau;
                
		/*
		
		for($i = 0; $i < $nb; $i++)
		{
                    $ligne = current($tableau);
                    
                    if($i < $vedette) {
                        
                    } else {
                        echo "<fieldset>
                            <legend><b>".$ligne['titre']."</b></legend><br />
                            <p>".$ligne['corps']."</p>
                            <a href='".$ligne['lien']."'>Suite</a>
                            </fieldset>";    
                    }			
			next($tableau);
		}*/
		
}
/**
* Permet d'afficher les annonces du blog.
*/
function annonce_blog($nb){
global $root_url;
global $template_url;
		$ewo_blog = bdd_connect('blog');
	
		$sql3 = "SELECT post_content, post_name, post_title, post_author, post_date FROM wp_posts WHERE post_type = 'post' ORDER BY post_date DESC LIMIT ".$nb;
		$res = mysql_query($sql3);
		
		while($row3 = mysql_fetch_array ($res))	{
			$sql4 = "SELECT user_login FROM wp_users WHERE ID = '".$row3['post_author']."'";
			$res4 = mysql_query($sql4);
			$row4 = mysql_fetch_array ($res4);
			
			// création de l'url en fonction du rewrite url du blog ici /année/moi/jour/titre
				$date = explode (' ',$row3['post_date']);
					$year = $date[0];
				$year = explode ('-', $year);
					$annee = $year[0];
					$mois = $year[1];
					$jour = $year[2];
				$lienblog = $annee.'/'.$mois.'/'.$jour;			
			
                                echo "<fieldset>
                                <legend><b>".$row['post_title']."</b></legend><br />
                                <p>".nl2br(tronquage($row3['post_content'],300))."</p>
                                <a href='http://blog.ewo-le-monde.com/$lienblog/".$row3['post_name']."'>Suite</a>
                                </fieldset>";
		}
	mysql_close($ewo_blog);
}

/**
* Permet d'afficher les annonces du forum.
*/
function annonce_forum($forum_id,$nb){
global $root_url;
global $template_url;
		$ewo_forumannonce = bdd_connect('forum');

		$sql = "SELECT forum_id,topic_id, topic_time, topic_title, topic_views, topic_replies, topic_poster, topic_first_poster_name, topic_first_poster_colour, topic_last_post_id, topic_last_poster_id, topic_last_poster_name, topic_last_poster_colour, topic_last_post_time
			 FROM phpbb_topics WHERE forum_id= 2 AND topic_type != 0" .
					' ORDER BY topic_time DESC ' .
					' LIMIT 0 , '.$nb.' ';
		$result = mysql_query ($sql);

		while($row = mysql_fetch_array ($result))	{	
			$sql1 = "SELECT post_text, poster_id
			 FROM phpbb_posts WHERE forum_id='".$forum_id."' AND topic_id='".$row['topic_id']."'" .
					' ORDER BY post_time ASC ' .
					' LIMIT 0 , 1 ';	
		
			$resulta = mysql_query ($sql1);
			$row1 = mysql_fetch_array ($resulta);

			$texte = bbcode_format($row1['post_text']);

			
			echo "<fieldset>
						<legend><b>".$row['topic_title']."</b></legend><br />
						<p>".tronquage($texte,500)."</p>
                                                <a href='".$root_url."/forum/viewtopic.php?f=".$forum_id."&t=".$row['topic_id']."'>Suite</a>
						</fieldset>";
		}
		mysql_close($ewo_forumannonce);
		$ewo_bdd = bdd_connect('ewo');
}
?>
