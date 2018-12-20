<?php
	/**
	 * Author: Oyekunle Opeyemi
	   Date: July, 2018
	 */
	class EmailPush
	{
		protected $_admin;
		protected $_users;
		protected $_token;
 
		function __construct($admin, $users, $token="")
		{
			$this->_admin = $admin;
			$this->_users = $users;
			$this->token = $token;
		}
		public function userMessage($content)
		{
			if ($content) {
				return $content;
			}
		}
		public function adminMessage($content)
		{
			if ($content) {
				return $content;
			}
			return false;
		}
		public function emailHeader()
		{
			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '.SITENAME.'<'.CONTACTEMAIL.'>';
			return $headers;
		}
		public function messageAdmin($subject, $content)
		{
			if ($this->adminMessage($content)) {
				foreach ($this->_admin as $recipient) {
					$sent = mail($recipient, $subject, $this->adminMessage($content), $this->emailHeader());
					if(DEVELOPMENT) echo $this->adminMessage($content)."<br>";
				}
				return $sent;
			}else{
				return false;
			}
		}
		public function messageUsers($subject, $content)
		{
			if ($this->userMessage($content)) {
				foreach ($this->_users as $recipient) {
					$sent = mail($recipient, $subject, $this->userMessage($content), $this->emailHeader());
					if(DEVELOPMENT) echo $this->userMessage($content)."<br>";
				}
				return $sent;
			}else{
				return false;
			}
		}
	}