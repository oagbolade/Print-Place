<?php
	/**
   * Users
   * 
   * This class is used for user interaction
   * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
   * @copyright   2017 Alabian Solutions Limited
   * @link        alabiansolutions.com
   */
	class Users{
		private $_salt;
		private $_emailParameter;
		public $_DbHandle;
		
		/**
     * Setup up db interaction
     * @param string $DbHandle an instant of DBHandler
     */
    public function __construct(DBHandler $DbHandle){
      $this->_DbHandle = $DbHandle;
			if($this->_DbHandle->getTable() != 'login') $DbHandle->setTable('login');
    }
		
		/**
		 * Set the value of the salt to be used for hashing
		 * @param string $salt the salt value to be used
		 * @return void
		 */
		public function setSalt($salt){
			$this->_salt = $salt;
		}
		
		/**
		 * Retrieve the value of the salt to be used for hashing
		 * @param string $salt the salt value to be used
		 * @return string $salt the value of the salt
		 */
		public function getSalt(){
			return $this->_salt;
		}
		
		/**
		 * Set the value of the parameter to be used for sending email via Functions object's method
		 * @param array $parameter the parameter(url, urlemail, contactemail, development) to be supplied
		 * @return void
		 */
		public function setEmailParameter($parameter){
			$this->_emailParameter = $parameter;
		}
		
		/**
		 * Retrieve the value of the parameter to be used for sending email via Functions object's method
		 * @return array $parameter the parameter(url, sitename, urlemail, contactemail, development) 
		 */
		public function getEmailParameter(){
			return $this->_emailParameter;
		}
		
		/**
		 * Get the table dbHandler is using and change it to 'users' table
		 * @return string the table dbHandler is using
		 */
		private function getTableSetToUser(){
			$DbHandle = $this->_DbHandle;
			$table = $DbHandle->getTable();
			if($DbHandle->getTable() != 'login'){
				$DbHandle->setTable('login');
			}
			return $table;
		}
		
		/**
		 * Get the user's id associated with an email
		 * @param string $email the email of the user
		 * @return mix the id of the user if found or false if not
		 */
		public function getIDFrmEmail($email){
			$DbHandle = $this->_DbHandle;
			$table = $this->getTableSetToUser();
			$id = false;
			$thisDetails = $DbHandle->retrieveData(__LINE__, $criteria);
				if($thisDetails){
					$id = $thisDetails[0]['id'];
				}
			$DbHandle->setTable($table);
			return $id;
		}
		
		/**
		 * Get the user's id associated with an email
		 * @param string $email the email of the user
		 * @param string userType either applicant or member
		 * @return mix the $no of the user if found or false if not
		 */
		public function getNoFrmEmail($email, $userType){
			$DbHandle = $this->_DbHandle;
			if($userType == 'member'){
				$useTable = 'member';	
				$noType = 'member_no';
			}
			else {
				$useTable = 'application';
				$noType = 'applicant_no';
			}			
			$table = $DbHandle->getTable();
			$DbHandle->setTable($useTable);
			$no = false;
			$details = $DbHandle->retrieveData(__LINE__, ['email'=>$email], $noType);
				if($details){
					$no = $details[0][$noType];
				}
			$DbHandle->setTable($table);
			return $no;
		}
		
		/**
		 * Get the user's email from his/her no
		 * @param string $no either applicat no or member no
		 * @param string userType either applicant or member
		 * @return mix $email the email of the user or false if no is associate with a user
		 */
		public function getEmailFrmNo($no, $userType){
			$DbHandle = $this->_DbHandle;
			if($userType == 'member'){
				$useTable = 'member';	
				$criteria = ['member_no'=>$no];
			}
			else {
				$useTable = 'application';
				$criteria = ['applicant_no'=>$no];
			}
			
			$table = $DbHandle->getTable();
			$DbHandle->setTable($useTable);
			$email = false;
			$details = $DbHandle->retrieveData(__LINE__, $criteria, ['email']);
				if($details){
					$email = $details[0]['email'];
				}
			$DbHandle->setTable($table);
			return $email;
		}
		
		
		
		/**
		 * Get the user's id from either email or username
		 * @param $string $emailOrUsername either the email or username of the user 
		 * @return integer $id the id of the user
		 */
		public function getIDFrmEmailOrUsername($emailOrUsername){
			$DbHandle = $this->_DbHandle;
			$table = $this->getTableSetToUser();
			$criteria = ['username'=>$emailOrUsername];
			if(filter_var($emailOrUsername, FILTER_VALIDATE_EMAIL)) $criteria = ['email'=>$emailOrUsername];
			$thisDetails = $DbHandle->iRetrieveData(__LINE__, $criteria);
			$id = $thisDetails[0]['id'];
			$DbHandle->setTable($table);
			return $id;
		}
		
		/**
		 * Get the user's email from username
		 * @param string $username the username of the usr
		 * @return string $email the retrieved email
		 */
		public function getEmailFrmUsername($username){
			$DbHandle = $this->_DbHandle;
			$table = $this->getTableSetToUser();
			$details = $DbHandle->iRetrieveData(__LINE__, ['username'=>$username]);
			$DbHandle->setTable($table);
			return $details[0]['email'];
		}
		
		/**
		 * Get the user's email from id
		 * @param integer $id the id of the usr
		 * @return string $email the retrieved email
		 */
		public function getEmailFrmID($id){
			$DbHandle = $this->_DbHandle;
			$table = $this->getTableSetToUser();
			$details = $DbHandle->iRetrieveData(__LINE__, ['id'=>$id]);
			$DbHandle->setTable($table);
			return $details[0]['email'];
		}
		
		/**
		 * Get the user's username from email
		 * @param string $email the email of the user
		 * @return string $username the retrieved username
		 */
		public function getUsernameFrmEmail($email){
			$DbHandle = $this->_DbHandle;
			$table = $this->getTableSetToUser();
			$details = $DbHandle->iRetrieveData(__LINE__, ['email'=>$email]);
			$DbHandle->setTable($table);
			return $details[0]['username'];
		}
		
		
		
		/**
		 * Check if email is associated with a user on the system 
		 * @param string $email the email be checked
		 * @return boolean $usedEmail true if email is used already
		 */	
		public function isEmailUsed($email){
			$DbHandle = $this->_DbHandle;
			$table = $DbHandle->getTable();
			$DbHandle->setTable('login');
			$data = ["email"=>$email];
  		if($DbHandle->retrieveData(__LINE__, $data)){
  			$usedEmail = TRUE;
  		}
			else {
				$usedEmail = FALSE;
			}
			$DbHandle->setTable($table);
  		return $usedEmail;
		}

		/**
		 * Used to create a new logger
		 * @param Functions an instance of Functions object used to get access some cool functionality
		 * @param array $data an array of data to be inserted into the users table 
		 * @param integer $line the line no where this method is called from
		 * @param string $logger the type of logger been created
		 * @param string $creator the creator of the account which is self or admin
		 * @return boolean $created true if the account was successfully created or false otherwise
		 */
		public function createLogger(Functions $function, $data, $line, $logger, $creator = "self"){
			$DbHandle = $this->_DbHandle;
			$table = $DbHandle->getTable();
			$DbHandle->setTable("login");
			$emailParameter = $this->_emailParameter;
			$message = "";
			$created = false;
			if(!$this->isEmailUsed($data['email'])){
				//Store data in user table
				$loggerData = [
					'email' => $data['email'], 
					'password' => crypt($data['password'], SALT), 
					'token' => substr($function->characterFromASCII($function->asciiTableDigitalAlphabet(),'string'), 0,16), 
					'date' => "NOW()", 
				];
				$loggerData['user_type'] = $logger;
				if($creator == 'self'){
					$loggerData['status'] = "inactive";	
					$loggerData['default_password'] = "no";
					$mailUrl = "
						<a style='color:#fff; text-decoration:underline;' href='{$emailParameter['url']}?token=".urlencode($loggerData['token'])."&email=".urlencode($loggerData['email'])."'>
							{$emailParameter['url']}?token={$loggerData['token']}&email={$loggerData['email']}
						</a>
					";
					$requestRegister = "register";
					$congraMsg = "
						Congratulation for creating an account on {$emailParameter['url']}. You will need to verify your 
						email address to be able to use your account.";
					$defaultPassword = "";
					$loginEmail = "";
				}
				else {
					$loggerData['status'] = "active";
					$loggerData['default_password'] = "yes";
					$mailUrl = "
						<strong>PORTAL URL:</strong>
						<a style='color:#fff; text-decoration:underline;' href='{$emailParameter['url']}'>
							{$emailParameter['url']}
						</a>
					";
					$requestRegister = "request";
					$congraMsg = "
						Congratulation an account has been created for you on". SITENAME .". You will need to visit the 
						portal now and login. <strong>BUT YOU WILL TO CHANGE YOUR DEFAULT PASSWORD ON FIRST SUCCESSFUL LOGIN</strong>
						";
					$loginEmail = "<br/><strong>EMAIL:</strong> {$loggerData['email']}";
					$defaultPassword = "<br/><strong>DEFAULT PASSWORD:</strong> {$data['password']}";
				}
				$newLogger = $DbHandle->createData(__LINE__, $loggerData);
				$created = true;
				
				//Send mail to user
				$message = $function::emailHead($emailParameter['url']);
				$message .= "
					<p style='margin-bottom:20px;'>Good Day Sir/Madam</p>
					<p style='margin-bottom:8px;'>
						$congraMsg You will need to click on the link below to do that or copy and paste in the address bar of your 
						browser to do same.
						<br/>
						$mailUrl
						$loginEmail
						$defaultPassword
					</p>
					<p style='margin-bottom:8px;'>
						<span style='font-weight:bold;'>NB</span><br/>
					   If you did not $requestRegister for an account at {$emailParameter['url']} please ignore this mail. Please do 
					   not reply to this mail as it is sent from an unmonitored address. You can contact us via {$emailParameter['contactemail']}
					</p>
				";
				$message .= $function::footerHead($emailParameter['url']);
				if(!$emailParameter['development']){
			  	$subject = "Account Creation";
			  	$headers  = 'MIME-Version: 1.0' . "\r\n";
			  	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			  	$headers .= "From: {$emailParameter['sitename']} <noreply@".$emailParameter['urlemail'].">" . "\r\n";
					mail($data['email'], $subject, $message, $headers);
				}
			}
			$DbHandle->setTable($table);
			if($emailParameter['development']) return $message;
			return $created;
		}
		
		/**
		 * Used to create a new applicant
		 * @param array $data an array of data to be inserted into the applicant table 
		 * @param integer $line the line no where this method is called from
		 * @return boolean $created true if the applicant was successfully created or false otherwise
		 */
		public function createApplicant($data, $line){
			$DbHandle = $this->_DbHandle;
			$table = $DbHandle->getTable();
			$DbHandle->setTable("application");
			$created = false;
			
			$DbHandle->createData($line, $data);
			$DbHandle->setTable($table);
			return $created;
		}
		
		/**
		 * Used to create a new member
		 * @param Functions an instance of Functions object used to get access some cool functionality
		 * @param array $data an array of data to be inserted into the users table 
		 * @param integer $line the line no where this method is called from
		 * @param string $creator the creator of the account which is self or admin
		 * @return boolean $created true if the account was successfully created or false otherwise
		 */
		public function createMember(Functions $function, $data, $line, $logger, $creator = "self"){
			$DbHandle = $this->_DbHandle;
			$table = $DbHandle->getTable();
			$DbHandle->setTable("member");
			$created = false;
			
			$DbHandle->setTable($table);
			return $created;
		}
		
		/**
		 * Used to create a new staff
		 * @param Functions an instance of Functions object used to get access some cool functionality
		 * @param array $data an array of data to be inserted into the users table 
		 * @param integer $line the line no where this method is called from
		 * @param string $creator the creator of the account which is self or admin
		 * @return boolean $created true if the account was successfully created or false otherwise
		 */
		public function createStaff($data, $line){
			$DbHandle = $this->_DbHandle;
			$table = $DbHandle->getTable();
			$DbHandle->setTable("staff");
			$created = $DbHandle->createData($line, $data);
			$DbHandle->setTable($table);
			return $created;
		}
		
		/**
		 * Used to delete a user
		 * @param string $email the email of the user
		 * @param integer $id the user id from the users table
		 * @param string $location the path to the user passport file
		 */
		public function deleteUser($email, $location, $userType){
			$DbHandle = $this->_DbHandle;
			$table = $DbHandle->getTable();
			$DbHandle->setTable('login');
			$thisProfile = $this->userDetails($email, $userType);
			if(!($thisProfile['passport'] == "male.png" || $thisProfile['passport'] == "female.png" || $thisProfile['passport'] == "profile.png")){
				unlink("$location/images/passport/{$thisProfile['passport']}");
			}
			$DbHandle->deleteData(__LINE__, ['email' => $email]);
			$DbHandle->setTable($table);
		}
		
		/**
		 * Used to get detail of a user from the database
		 * @param string $email the email of the user
		 * @param string $userType the user type if logger, applicant, member, staff 
		 * @return mix $userDetails an array that contains the details of the user or false if no such user
		 */
		public function userDetails($email, $userType = ""){
			$DbHandle = $this->_DbHandle;
			$table = $DbHandle->getTable();
			$user = $DbHandle->iRetrieveData(__LINE__, ['email'=>$email]);

			if ($user[0]['user_type'] != "vendor") {
				if(!$userType){
					$userType = "logger";
					$DbHandle->setTable("login");
					if($userAuthority = $DbHandle->retrieveData(__LINE__, ['email'=>$email])){
						$exactUsersType = Functions::userType();	
						$userType = $exactUsersType[$userAuthority[0]['user_type']];
					}
				}
				$tableUsed = Functions::userTable();
				
				$DbHandle->setTable($tableUsed[$userType]);
				$details = false;
				$thisDetails = $DbHandle->retrieveData(__LINE__, ['email'=>$email]);
				if($thisDetails){
					foreach ($thisDetails[0] as $field => $record) {
						$details[$field] = $record;
					}
				}
				
				$DbHandle->setTable("login");
				$typeStatus = $DbHandle->retrieveData(__LINE__, ['email'=>$email]);
				$details['status'] = $typeStatus[0]['status'];
				$details['id'] = $typeStatus[0]['id'];
				$details['default_password'] = $typeStatus[0]['default_password'];
				$details['token'] = $typeStatus[0]['token'];
				$details['user_type'] = $typeStatus[0]['user_type'];
			}else{
				$details = false;
				$DbHandle->setTable("login");
				if($loginDetails = $DbHandle->retrieveData(__LINE__, ['email'=>$email])){
					foreach ($loginDetails[0] as $detailsKey=>$detailsValue) {
						$details[$detailsKey] = $detailsValue;
					}
				}
				$tables = Functions::userTable();
				$thisTable = $tables[$details['user_type']];
				$DbHandle->setTable($thisTable);
				if($thisDetails = $DbHandle->iRetrieveData(__LINE__, ['login_id'=>$details['id']])){
					foreach ($thisDetails[0] as $field => $record) {
						if(isset($details[$field])){
							$details["{$thisTable}_{$field}"] = $record;
						}
						else {
							$details[$field] = $record;	
						}
					}
				}	
			}
			
			$DbHandle->setTable($table);
			return $details;
		}
		
		/**
		 * Used to get all the users details on the app
		 * @param string $userType the user type if logger, applicant, member, staff
		 * @return mix $allDetails an array that contains all user details or false is no user in the app
		 */
		public function allUserDetails($userType){
			$DbHandle = $this->_DbHandle;
			$tableUsed = Functions::userTable();
			$table = $DbHandle->getTable();
			$DbHandle->setTable($tableUsed[$userType]);
			$allDetails = false;
			$allUsers = $DbHandle->iRetrieveData(__LINE__);
			if($allUsers){
				foreach($allUsers as $aUser){
					$aUserDetails = $this->userDetails($aUser['email'], $userType);
					$aUser['status'] = $aUserDetails['status'];
					$aUser['default_password'] = $aUserDetails['default_password'];
					$aUser['token'] = $aUserDetails['token'];
					$aUser['user_type'] = $aUserDetails['user_type'];
					$allDetails[] = $aUser;
				}
			} 
			$DbHandle->setTable($table);
			return $allDetails;
		}
		
		/**
		 * Used to get detail of a vendor from the database
		 * @param string $email the email of the vendor
		 * @param string $table the table to get the details from
		 * @return mix $vendorDetails an array that contains the details of the user or false if no such user
		 */
		public function vendorDetails($email, $vendorTable = 'login'){
			$DbHandle = $this->_DbHandle;
			$table = $DbHandle->getTable();
			$details = false;
			$DbHandle->setTable('vendor');
			$id = $this->getIDFrmEmailOrUsername($email);
			if($vendorDetails = $DbHandle->retrieveData(__LINE__, ['login_id'=>$id])){
				foreach ($vendorDetails[0] as $detailsKey=>$detailsValue) {
					$details[$detailsKey] = $detailsValue;
				}
			}
			if($vendorTable=="login"){
				
			}
			else {
				$DbHandle->setTable($vendorTable);
				if($thisDetails = $DbHandle->iRetrieveData(__LINE__, ['vendor_id'=>$details['id']])){
					foreach ($thisDetails[0] as $field => $record) {
						if(isset($details[$field])){
							$details["{$vendorTable}_{$field}"] = $record;
						}
						else {
							$details[$field] = $record;	
						}
					}
				}	
			}
					
			$DbHandle->setTable($table);
			return $details;
		}
		
		/**
		 * Use to flip user account status between active and inactive
		 * @param string $email the email of the user
		 * @param string $status either active or inactive
		 * @param Function $functions an instance of Function object
		 * @param string $sendMail if true user is notify via mail about activation
		 * @return void
		 */
		public function changeUserStatus($email, $status, Functions $function, $sendMail = true){
			$DbHandle = $this->_DbHandle;
			$table = $DbHandle->getTable();
			$DbHandle->setTable('login');
			$data = array('status' => $status);
			$key = array('email' => $email);
			$DbHandle->updateData(__LINE__, $data, $key);
			
			//Send mail to user
			if($sendMail){
				$emailParameter = $this->_emailParameter;
				if($status == 'active'){
					$firstParagraph = "
						Congratulation your account on Membership Application Portal has been activated. You can now 
						<a href='{$emailParameter['url']}' style='color:#fff; text-decoration:underline;'>login</a>, in case you 
						have forgotten your password please just do a password reset by clicking on 'Lost Password?'
					";
				}
				else {
					$firstParagraph = "This is to inform you that your account on Membership Application Portal has been suspended.";
				}
				$message = $function::emailHead($emailParameter['url']);
				$message .= "
					<p style='margin-bottom:20px;'>Good Day Sir/Madam</p>
					<p style='margin-bottom:8px;'>
						$firstParagraph 
					</p>
					<p style='margin-bottom:8px;'>
						<span style='font-weight:bold;'>NB</span><br/>
					   If you did not have an account at {$emailParameter['url']} please ignore this mail. Please do 
					   not reply to this mail as it is sent from an unmonitored address. You can contact us via {$emailParameter['contactemail']}
					</p>
				";
				$message .= $function::footerHead($emailParameter['url']);
				if(!$emailParameter['development']){
			  	$subject = "Account Activation";
			  	$headers  = 'MIME-Version: 1.0' . "\r\n";
			  	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			  	$headers .= "From: {$emailParameter['sitename']} <noreply@".$emailParameter['urlemail'].">" . "\r\n";
					mail($email, $subject, $message, $headers);
				}				
			}
			
			//Restore table
			$DbHandle->setTable($table);
		}
				
	}