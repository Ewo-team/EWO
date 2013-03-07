<?php

use \conf\ConnecteurDAO as ConnecteurDAO;

class EwoForumDAO extends ConnecteurDAO {
    
    public function selectPerso($pseudo) {
        $sql = 'SELECT user_id FROM phpbb_users WHERE username_clean = ?';
        $this->prepare($sql);
        $this->executePreparedStatement(null,array($pseudo));
        return $this->fetch();          
    }
    
    public function selectLegions($name) {
        $sql = "SELECT group_id FROM phpbb_groups WHERE group_name LIKE = ' $name%'";
        $this->query($sql);
        return $this->fetchAll();             
    }
    
    public function listePersos($user_id) {
        $sql = 'SELECT nom FROM persos WHERE utilisateur_id = ?';
        $this->prepare($sql);
        $this->executePreparedStatement(null,array($user_id));
        $result = $this->fetchAll();
        $liste = array();
        
        foreach($result as $ligne) {
            $liste[] = $ligne['nom'];
        }
        
        return $liste;
    }
    
    public function setHash(array $pseudo,$hash) {
        $sql = 'UPDATE phpbb_users SET user_password = :hash WHERE username_clean = :pseudo';
        $query = $this->prepare($sql);
        foreach ($pseudo as $p) {
            $this->executePreparedStatement($query,array(':hash' => $hash, ':pseudo' => $p));        
        }
    }
    
    public function getHashEmail($email) {
        $sql = "SELECT user_password FROM phpbb_users WHERE user_email = ? AND user_password != 'blanc' LIMIT 1";
        $this->prepare($sql);
        $this->executePreparedStatement(null,array($email));
        return $this->fetch();            
    }
    
    public function setMasterId($email) {
        $sql = 'SELECT user_id FROM phpbb_users WHERE user_email = ? LIMIT 1';
        $this->prepare($sql);
        $this->executePreparedStatement(null,array($email));
        $result = $this->fetch();          
        $master = $result[0];
        
        $sql = 'UPDATE phpbb_users SET master_id = :master WHERE user_email = :email AND user_id != :master';
        $this->prepare($sql);
        $this->executePreparedStatement(null,array(':master' => $master, ':email' => $email));       
        
        $sql = 'UPDATE phpbb_users SET master_id = 0 WHERE user_id = ?';
        $this->prepare($sql);
        $this->executePreparedStatement(null,array($master));            
    }
    
    public function isBlankPassword($pseudo) {
        $sql = 'SELECT user_password as pass FROM phpbb_users WHERE username_clean = ?';
        $this->prepare($sql);
        $this->executePreparedStatement(null,array($pseudo));
        $result = $this->fetch();   
        return (isset($result['pass']) && $result['pass'] == 'blanc') ? true : false;
    }
    
    public function addPerso($us, $us_clean, $pwd, $email, $email_hash) {			
		$sql = "INSERT INTO `ewo_forum`.`phpbb_users` (`user_id`, `user_type`, `group_id`, `user_permissions`, `user_perm_from`, `user_ip`, 
				`user_regdate`, `username`, `username_clean`, `user_password`, `user_passchg`, `user_pass_convert`, `user_email`, `user_email_hash`, 
				`user_birthday`, `user_lastvisit`, `user_lastmark`, `user_lastpost_time`, `user_lastpage`, `user_last_confirm_key`, `user_last_search`, 
				`user_warnings`, `user_last_warning`, `user_login_attempts`, `user_inactive_reason`, `user_inactive_time`, `user_posts`, `user_lang`, 
				`user_timezone`, `user_dst`, `user_dateformat`, `user_style`, `user_rank`, `user_colour`, `user_new_privmsg`, `user_unread_privmsg`, 
				`user_last_privmsg`, `user_message_rules`, `user_full_folder`, `user_emailtime`, `user_topic_show_days`, `user_topic_sortby_type`, 
				`user_topic_sortby_dir`, `user_post_show_days`, `user_post_sortby_type`, `user_post_sortby_dir`, `user_notify`, `user_notify_pm`, 
				`user_notify_type`, `user_allow_pm`, `user_allow_viewonline`, `user_allow_viewemail`, `user_allow_massemail`, `user_options`, 
				`user_avatar`, `user_avatar_type`, `user_avatar_width`, `user_avatar_height`, `user_sig`, `user_sig_bbcode_uid`, `user_sig_bbcode_bitfield`, 
				`user_from`, `user_icq`, `user_aim`, `user_yim`, `user_msnm`, `user_jabber`, `user_website`, `user_occ`, `user_interests`, `user_actkey`, 
				`user_newpasswd`, `user_form_salt`, `user_new`, `user_reminded`, `user_reminded_time`, `master_id`) VALUES (NULL, '0', '3', '', '0', '', ".time().", :user, :userclean, :pass, '0', '0', :mail, :hash, '', '0', '0', '0', '', '', '0', '0', '0', '0', '0', '0', '0', '', '0.00', '0', 'd M Y H:i', '0', '0', '', '0', '0', '0', '0', '-3', '0', '0', 't', 'd', '0', 't', 'a', '0', '1', '0', '1', '1', '1', '1', '230271', '', '0', '0', '0', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1', '0', '0', '0');";

        $query = $this->prepare($sql);
        $this->executePreparedStatement($query,array(
            ':user' => $us,
            ':userclean' => $us_clean,
            ':pass' => $pwd,
            ':mail' => $email,
            ':hash' => $email_hash
        )); 
		
		/*echo "INSERT INTO `ewo_forum`.`phpbb_users` (`user_id`, `user_type`, `group_id`, `user_permissions`, `user_perm_from`, `user_ip`, 
				`user_regdate`, `username`, `username_clean`, `user_password`, `user_passchg`, `user_pass_convert`, `user_email`, `user_email_hash`, 
				`user_birthday`, `user_lastvisit`, `user_lastmark`, `user_lastpost_time`, `user_lastpage`, `user_last_confirm_key`, `user_last_search`, 
				`user_warnings`, `user_last_warning`, `user_login_attempts`, `user_inactive_reason`, `user_inactive_time`, `user_posts`, `user_lang`, 
				`user_timezone`, `user_dst`, `user_dateformat`, `user_style`, `user_rank`, `user_colour`, `user_new_privmsg`, `user_unread_privmsg`, 
				`user_last_privmsg`, `user_message_rules`, `user_full_folder`, `user_emailtime`, `user_topic_show_days`, `user_topic_sortby_type`, 
				`user_topic_sortby_dir`, `user_post_show_days`, `user_post_sortby_type`, `user_post_sortby_dir`, `user_notify`, `user_notify_pm`, 
				`user_notify_type`, `user_allow_pm`, `user_allow_viewonline`, `user_allow_viewemail`, `user_allow_massemail`, `user_options`, 
				`user_avatar`, `user_avatar_type`, `user_avatar_width`, `user_avatar_height`, `user_sig`, `user_sig_bbcode_uid`, `user_sig_bbcode_bitfield`, 
				`user_from`, `user_icq`, `user_aim`, `user_yim`, `user_msnm`, `user_jabber`, `user_website`, `user_occ`, `user_interests`, `user_actkey`, 
				`user_newpasswd`, `user_form_salt`, `user_new`, `user_reminded`, `user_reminded_time`, `master_id`) VALUES (NULL, '0', '3', '', '0', '', ".time().", $us, $us_clean, $pwd, '0', '0', $email, $email_hash, '', '0', '0', '0', '', '', '0', '0', '0', '0', '0', '0', '0', '', '0.00', '0', 'd M Y H:i', '0', '0', '', '0', '0', '0', '0', '-3', '0', '0', 't', 'd', '0', 't', 'a', '0', '1', '0', '1', '1', '1', '1', '230271', '', '0', '0', '0', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1', '0', '0', '0');";*/

        
    }
    
    public function removeGroup($id,$group) {
                
        $sql = "DELETE FROM phpbb_user_group WHERE user_id = :id AND group_id IN ($group)";
        $query = $this->prepare($sql);
        $this->executePreparedStatement($query,array(
                    ':id' => $id
                )); 
		//echo "DELETE FROM phpbb_user_group WHERE user_id = $id AND group_id IN ($group)";
    }
    
    public function addGroup($id,$group) {
        $sql = "INSERT INTO phpbb_user_group (group_id, user_id, group_leader, user_pending) VALUES (:group, :id, 0, 0)"; 
        $query = $this->prepare($sql);
        $this->executePreparedStatement($query,array(':id' => $id, ':group' => $group));  
		//echo "INSERT INTO phpbb_user_group (group_id, user_id, group_leader, user_pending) VALUES ($group, $id, 0, 0)"; 
    }
	
	public function setRank($id,$rank) {
        $sql = "UPDATE phpbb_users SET user_rank = :rank WHERE user_id = :id"; 
        $query = $this->prepare($sql);
        $this->executePreparedStatement($query,array(':id' => $id, ':rank' => $rank));  	
		//echo "UPDATE phpbb_users SET user_rank = $rank WHERE user_id = $id"; 		
	}
}