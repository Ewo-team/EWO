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
	private $Separator;
	
	public $Log;

	public function __construct() {
		$this->Separator = '-----=' . md5(uniqid(mt_rand())); 
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
	
	public function Send() {
	
		if($this->validation()) {
			$arrayTo = array();
			
			foreach($this->To as $key => $value) {
				$arrayTo = is_numeric($key) ? $value : '"' . mb_encode_mimeheader($key) . '" <' . $value . '>';
			}
			
			$to = implode(',', $arrayTo);
		
			$headers = $this->getHeaders();
			
			$msg = $this->getMessageText();
			$msg .= $this->getMessageHtml();
			
			$send = mail($to, '=?UTF-8?B?' . base64_encode($this->Subject) . '?=', $msg, $headers);		
			
			$this->Log['to'] = $to;
			$this->Log['subject'] = '=?UTF-8?B?' . base64_encode($this->Subject) . '?=';
			$this->Log['msg'] = $msg;
			$this->Log['headers'] = $headers;	
			
			return $send;
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
			return false;
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
		$message .= $this->MessageText."\n\n"; 	

		return $message;
	}
	
	private function getMessageHtml() {
		$message = '--'.$this->Separator."\n";
		$message .= 'Content-Type: text/html; charset="iso-8859-1"'."\n"; 
		$message .= 'Content-Transfer-Encoding: 8bit'."\n\n"; 
		$message .= $this->MessageHtml."\n\n"; 
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
		
		$emails    = array
		(
			'From: ' . $from,
			'Reply-To: ' . $reply
		);		
		
		if(count($this->Cc) > 0) {
			$arrayCc = array();
			
			foreach($this->Cc as $key => $value) {
				$arrayCc = is_numeric($key) ? $value : '"' . mb_encode_mimeheader($key) . '" <' . $value . '>';
			}
			
			$emails[] = 'Cc: '.implode(',', $arrayCc);	
		}
		
		if(count($this->Bcc) > 0) {
			$arrayBcc = array();
			
			foreach($this->Bcc as $key => $value) {
				$arrayBcc = is_numeric($key) ? $value : '"' . mb_encode_mimeheader($key) . '" <' . $value . '>';
			}
			
			$emails[] = 'Bcc: '.implode(',', $arrayBcc);			
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

		return implode("\n", array_merge($headers, $emails, $returns));
	}
}