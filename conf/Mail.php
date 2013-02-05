<?php

namespace conf;

class Mail {

	private $Bcc = array();
	private $Cc = array();
	private $To = array();
	public $From;
	public $FromName;
	public $Reply;
	public $ReplyName;
	public $Subject;
	public $MessageText;
	public $MessageHtml;
	public $ParseTitle;
	public $ParseCorps;
	public $ParseID;
	private $Separator;
	private $template;

	public function __construct($template = 'BASE') {
		$this->Separator = '-----=' . md5(uniqid(mt_rand())); 
		$this->ParseID = md5(uniqid(mt_rand()));
		
		$this->template = MailTemplate::getTemplate($template);
		
		$this->applyTemplate();
	}
	
	public static function Html2Text($html) {
		$html = preg_replace('/<(br).*>/i', "\n", $html);
		$html = preg_replace('/<(p)>/i', "\n", $html);
		$texte = strip_tags($html);
	
		return $texte;
	}
	
	public function AddTo($param1, $param2 = null) {
		if($param2 == null) {
			$this->To[] = $param1;
		} else {
			$this->To[$param2] = $param1;
		}
	}
	
	public function AddCc($param1, $param2 = null) {
		if($param2 == null) {
			$this->Cc[] = $param1;
		} else {
			$this->Cc[$param2] = $param1;
		}	
	}		
	
	public function AddBcc($param1, $param2 = null) {
		if($param2 == null) {
			$this->Bcc[] = $param1;
		} else {
			$this->Bcc[$param2] = $param1;
		}	
	}	
	
	public function Parse() {
	
		if(isset($this->template['MessageHtmlParse'])) {
		
			$html = $this->template['MessageHtmlParse'];		
		
			if(isset($this->ParseTitle)) {
				$html = preg_replace('/{TITRE}/', $this->ParseTitle, $html);
			}
			
			if(isset($this->ParseCorps)) {
				$html = preg_replace('/{CONTENT}/', $this->ParseCorps, $html);
			}

			
			$html = preg_replace('/{IDMAIL}/', $this->ParseID, $html);

			$this->MessageHtml = $html;

		}
		
		if(isset($this->template['MessageTexteParse'])) {
		
			$texte = $this->template['MessageTexteParse'];		
		
			if(isset($this->ParseTitle)) {
				$texte = preg_replace('/{TITRE}/', $this->ParseTitle, $texte);
			}
			
			if(isset($this->ParseCorps)) {
				$texte = preg_replace('/{CONTENT}/', Mail::Html2Text($this->ParseCorps), $texte);
			}

			$texte = preg_replace('/{IDMAIL}/', $this->ParseID, $texte);

			$this->MessageText = $texte;
		}		
	}
	
	public function Send() {
	
		if($this->validation()) {
			$to = '';
			
			foreach($this->To as $key => $value) {
				$to .= is_numeric($key) ? $value.',' : '"' . mb_encode_mimeheader($key) . '" <' . $value . '>,';
			}
			
			$to = substr($to,0,-1);


			$headers = $this->getHeaders();
			
			$msg = $this->getMessageText();
			$msg .= $this->getMessageHtml();
						
			$send = mail($to, '=?UTF-8?B?' . base64_encode($this->Subject) . '?=', $msg, $headers);		
			
			return $send;
		}
	}
	
	private function applyTemplate() {
		if(isset($this->template['Bcc'])) {
			$this->Bcc = $this->template['Bcc'];
		}
		
		if(isset($this->template['Cc'])) {
			$this->Cc = $this->template['Cc'];
		}

		if(isset($this->template['To'])) {
			$this->To = $this->template['To'];
		}

		if(isset($this->template['From'])) {
			$this->From = $this->template['From'];
		}

		if(isset($this->template['FromName'])) {
			$this->FromName = $this->template['FromName'];
		}

		if(isset($this->template['Reply'])) {
			$this->Reply = $this->template['Reply'];
		}

		if(isset($this->template['ReplyName'])) {
			$this->ReplyName = $this->template['ReplyName'];
		}

		if(isset($this->template['Subject'])) {
			$this->Subject = $this->template['Subject'];
		}		
	}
	
	private function validation() {
	
		if(count($this->To) == 0) {
			return false;
		}
		
		if(!isset($this->From)) {
			return false;
		}

		if(!isset($this->Subject)) {
			return false;
		}
		
		if(!isset($this->MessageText)) {
			if(!isset($this->MessageHtml)) {
				return false;
			} else {
				$this->MessageText = Mail::Html2Text($this->MessageHtml);
			}
		}
		
		if(!isset($this->MessageHtml)) {
			return false;
		}		

		return true;
	}
		
	private function getMessageText() {
		
		$message = 'This is a multi-part message in MIME format.'."\n\n"; 

		$message .= '--'.$this->Separator."\n"; 
		$message .= 'Content-Type: text/plain; charset="UTF-8"'."\n"; 
		$message .= 'Content-Transfer-Encoding: 8bit'."\n\n"; 
		$message .= $this->template['MessageTextePrefix'];	
		$message .= $this->MessageText;
		$message .= $this->template['MessageTexteSuffix']."\n\n"; 	

		return $message;
	}
	
	private function getMessageHtml() {
		$message = '--'.$this->Separator."\n";
		$message .= 'Content-Type: text/html; charset="UTF-8"'."\n"; 
		$message .= 'Content-Transfer-Encoding: 8bit'."\n\n"; 
		$message .= $this->template['MessageHtmlPrefix'];			
		$message .= $this->MessageHtml; 
		$message .= $this->template['MessageHtmlSuffix'] ."\n\n"; 
		$message .= '--'.$this->Separator."\n"; 	
		
		return $message;
	}
	
	private function getHeaders() {
	
		$from        = empty($this->FromName) ? $this->From : '"' . mb_encode_mimeheader($this->FromName) . '" <' . $this->From . '>';	
		
		if(isset($this->Reply)) {
			$reply        = empty($this->ReplyName) ? $this->Reply : '"' . mb_encode_mimeheader($this->ReplyName) . '" <' . $this->Reply . '>';			
		} else {
			$reply        = $from;				
		}
		
		$emails    = 'From: ' . $from . "\n" .
					 'Reply-To: ' . $reply . "\n";	
		
		if(count($this->Cc) > 0) {
			$cc = '';
			
			foreach($this->Cc as $key => $value) {
				$cc .= is_numeric($key) ? $value.',' : '"' . mb_encode_mimeheader($key) . '" <' . $value . '>,';
			}
			
			$emails .= 'Cc: '.substr($cc,0,-1). "\n";
		}
		
		if(count($this->Bcc) > 0) {
			$bcc = '';
			
			foreach($this->Bcc as $key => $value) {
				$bcc .= is_numeric($key) ? $value.',' : '"' . mb_encode_mimeheader($key) . '" <' . $value . '>,';
			}
			
			$emails .= 'Bcc: ' . substr($bcc,0,-1). "\n";			
		}
		
		$headers    = array
		(
			'MIME-Version: 1.0',
			'Content-Type: multipart/alternative; boundary="'.$this->Separator.'"',
			'Date: ' . date('r', $_SERVER['REQUEST_TIME']),
			'Message-ID: <' . $_SERVER['REQUEST_TIME'] . md5($_SERVER['REQUEST_TIME']) . '@' . $_SERVER['SERVER_NAME'] . '>'
		);

		$returns    = array
		(
			'Return-Path: ' . $from, 
			'X-Originating-IP: ' . $_SERVER['SERVER_ADDR']
		);		
		
		$string_headers = implode("\n", $headers). "\n" . $emails .  implode("\n", $returns);
		return $string_headers;
	}
}

class MailTemplate {
	private static $BASE = array(
		'From' => 'no-reply@ewo.fr',
		'FromName' => 'EWO',
		'Subject' => '[EWO] ',
		'MessageHtmlPrefix' => '',
		'MessageTextePrefix' =>	'',
		'MessageHtmlSuffix' =>	'',
		'MessageTexteSuffix' =>	'',
		'MessageHtmlParse' => '<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv=Content-Type content="text/html; charset=utf-8"><meta content="width=device-width"><body style="margin:0px; padding:0px; -webkit-text-size-adjust:none"><table width="100%" cellpadding=0 cellspacing=0 border=0 style="background-color:rgb(42, 55, 78)"><tbody><tr><td align=center bgcolor="#2A374E"><table cellpadding=0 cellspacing=0 border=0><tbody><tr><td class=w640 width=640 height=10></td><tr><td align=center class=w640 width=640 height=20><a style="color:#ffffff; font-size:12px" href="http://www.ewo-le-monde.com/site/mail/affiche.php?id={IDMAIL}"><span style="color:#ffffff; font-size:12px">Voir le contenu de ce mail en ligne</span></a></td><tr><td class=w640 width=640 height=10></td><tr class=pagetoplogo><td class=w640 width=640><table class=w640 width=640 cellpadding=0 cellspacing=0 border=0 bgcolor="#F2F0F0"><tbody><tr><td class=w30 width=30></td><td class=w580 width=580 valign=middle align=left><div class=pagetoplogo-content style="text-align: center"><img class=w580 style="padding: 10px;text-decoration: none;  color:#476688; font-size:30px" src="http://www.ewo-le-monde.com/images/logo.png" alt="Eternal War One" width=254 height=175></div></td><td class=w30 width=30></td></table></td><tr><td class=w640 width=640 height=1 bgcolor="#d7d6d6"></td><tr class=content><td class=w640 class=w640 width=640 bgcolor="#ffffff"><table class=w640 width=640 cellpadding=0 cellspacing=0 border=0><tbody><tr><td class=w30 width=30></td><td class=w580 width=580><table class=w580 width=580 cellpadding=0 cellspacing=0 border=0><tbody><tr><td class=w580 width=580><h2 style="color:#0E7693; font-size:22px; padding-top:12px">{TITRE}</h2><div align=left class=article-content>{CONTENT}</div></td><tr><td class=w580 width=580 height=1 bgcolor="#c7c5c5"></td></table></td><td class=w30 class=w30 width=30></td></table></td><tr><td class=w640 width=640 height=15 bgcolor="#ffffff"></td><tr class=pagebottom><td class=w640 width=640><table class=w640 width=640 cellpadding=0 cellspacing=0 border=0 bgcolor="#c7c7c7"><tbody><tr><td colspan=5 height=10></td><tr><td class=w30 width=30></td><td class=w580 width=580 valign=top><p align=right class=pagebottom-content-left><a style="color:#255D5C" href="http://www.ewo-le-monde.com"><span style="color:#255D5C">Eternal War One</span></a></p></td><td class=w30 width=30></td><tr><td colspan=5 height=10></td></table></td><tr><td class=w640 width=640 height=60></td></table></td></table>',
		'MessageTexteParse' => "Eternal War One\n===============\n\n{TITRE}\n------\n\n{CONTENT}\n\nwww.ewo-le-monde.com\n\nVersion HTML : http://www.ewo-le-monde.com/site/mail/affiche.php?id={IDMAIL}"
	);
	
	public static function getTemplate($template) {
		return self::$$template;
	}
	
	/*public static ADMIN = array(
		'Bcc'
		'Cc'
		'To'
		'From'
		'FromName'
		'Reply'
		'ReplyName'
		'SubjectPrefix'
		'MessageHtmlPrefix'
		'MessageTextePrefix'		
		'MessageHtmlSuffix'
		'MessageTexteSuffix'
	);	*/
}
