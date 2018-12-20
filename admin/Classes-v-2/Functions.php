<?php
	/**
	 * Functions
	 * 
	 * This class is used to to supply some commonly used functions
	 * @author			Alabi A. <alabi.adebayo@alabiansolutions.com>
	 * @copyright		2014 Alabian Solutions Limited
	 * @link 				alabiansolutions.com
	 */
	class Functions{
	/**
	 * Check if magic quotes is on and then turn it off if it is.
	 */
		public static function magicQuotesOff(){
			if (get_magic_quotes_gpc()){
					function stripslashes_deep($value){
						$value = is_array($value) ?
						array_map('stripslashes_deep', $value) :  stripslashes($value);
						 return $value;
					}
				$_POST = array_map('stripslashes_deep', $_POST);
				$_GET = array_map('stripslashes_deep', $_GET);
				$_COOKIE = array_map('stripslashes_deep', $_COOKIE);
				$_REQUEST = array_map('stripslashes_deep', $_REQUEST);
			}
		}

		/**
		 * Sanitize value passed to it. This help prevent malicious input been passed to your script
		 * @param string $var the value to be sanitized 
		 * @return string $var the value after sanitization
		 */

		public static function sanitizeString($var){
			$var = stripslashes($var);
  		$var = htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
  		$var = strip_tags($var);
  		return $var;
		}

		

		/**
		 * Sanitize and echo the string passed to it
		 * @param string $var the value to be echo 
		 */
		public static function sanitizeEchoer($var){
			echo self::sanitizeString($var);
		}

		/**
		 * Simply sanitize the string passed
		 * @param string $var the value to be sanitize 
		 * @return string $cleanVar the santized string
		 */
		public static function sanitizer($var){
			$cleanVar = self::sanitizeString($var);
			return $cleanVar;
		}

		/**
		 * Simply echo the string passed to it
		 * @param string $var the value to be echo 
		 */
		public static function echoer($var){
			echo $var;
		}

		/**
		 * Simply generate the head part of the HTML string for generation of email template
		 * @param string $url the url of the server where the email will be sent from
		 * @return string $headEmail the head part of the HTML string 
		 */
		public static function emailHead($url){
			$headEmail="
			 <html>
				<head>
					<title></title>
		  	</head>
				<body>
			 	<div style='width:88%; color: #fff; padding:0px 0 15px 0; background-color: #68C5F1;'>
		     <div style='
		     	background-color: #fefefe;
		     	border-bottom:2px solid #2A63BD; 
		     	padding:7px 1% 7px;
		     	margin-bottom:15px;
		     	text-align: left; 
		     	'>
		       <img src='". $url ."images/logo.png' style='height:75px; width:75px;'/>
		       <a href='$url' style='text-decoration:none; color:#0e1d54;'>
						<span style='
							color:#2A63BD;
							display: block;
							font-size:20px;
							font-size:1.25rem;
							font-weight: bold;
							text-decoration: none;
						'>
						 	Membership Application Portal
						 </span>
					 </a>
			 	 </div>
			 <div style='padding:5px 1%; color:#fff; font-size:12px; font-family:Arial;'>";
			return $headEmail;
		}

		/**
		 * Simply generate the footer part of the HTML string for generation of email template
		 * @param string $url the url of the server where the email will be sent from
		 * @param string $footerFrom the saluation for closing the mail
		 * @return string $footerEmail the footer part of the HTML string 
		 */
		public static function footerHead($url, $footerFrom =""){
			if(!$footerFrom){
				$footerFrom = "
					<div style='margin-bottom:60px; margin-top:30px; padding: 0px 1%;'>
				   	IT Admin<br/>
				   	<a href='$url' style='color:#f0f0f0;'>For Membership Application Portal</a>
				 	</div>
				";
			}
			$footerEmail="
				</div>
				$footerFrom
				<div style='font-size:9px; background-color: #fefefe; border:1px solid #2A63BD; padding-top:10px; padding-bottom:10px;'>
					<div style='font-size:9px; float:left; color:#999; padding-left:5px' >
						Developed by <a style='color:#999; text-decoration:none;' rel='nofollow' href=''>
			     	ProIT Limited</a>
					</div>
					<div style='font-size:9px; float:right; color:#999; padding-right:5px' >
						&copy; ".date("Y")." NIM
					</div>
					<div style='clear:both;'></div>
				</div>
				</div>
				</body>
				</html>
			 ";
			return $footerEmail;
		}

		/**
		 * Generate the ASCII code of digits, alphabet upper & case
		 * @return array $array an array that contains ASCII of digits, alphabet upper & lower case 
		 */
		public static function asciiTableDigitalAlphabet(){
	  	$array=array();
			//Digitals
			for ($kanter=48; $kanter <=57 ; $kanter++) { 
				$array[]=$kanter;
			}
			//Uppercase
			for ($kanter=65; $kanter <=90 ; $kanter++) { 
				$array[]=$kanter;
			}
			//Lowercase
			for ($kanter=97; $kanter <=122 ; $kanter++) { 
				$array[]=$kanter;
			}
			shuffle($array);
			return $array;
	  }

		/**
		 * Generate the ASCII code of digits, alphabet upper & case
		 * @param array $ASCIIArray an array that contains ASCII Code
		 * @param string $dataFormat the format of the return value of array for array or other value for string
		 * @return mix $characters an array or string that contains character that matches the ASCII Code supplied 
		 */
		public static function characterFromASCII($ASCIIArray, $dataFormat='array'){
			$max=count($ASCIIArray);
			for ($kanter=0; $kanter <$max ; $kanter++) { 
				$array[]=chr($ASCIIArray[$kanter]);
			}
			if($dataFormat=='array'){
				$characters=$array;
			}
			else {
				$characters="";
				foreach ($array as $anArrayValue) {
					$characters.=$anArrayValue;
				}
			}
			return $characters;
		}
		
		/**
		 * Get the title for saluation from the gender supplied
		 * @param string $gender which is either male or female
		 * @param array $type an array that contain the saluation to be used like (his/her, he/she, him/her)
		 * @return string $title the title 
		 */
		public static function iTheTitle($gender, $type=array('sir', 'madam'), $case="UC"){
			if($gender=="male"){
				$title=ucfirst($type[0]);
			}
			else {
				$title=ucfirst($type[0]);
			}
			if($case!="UC"){
				$title=strtolower($title);
			}
			return $title;
		}

		/**
		 * A collection of years from 1940 to present year
		 * @return array $years an array of years from 1940 to present year
		 */
		public static function yearsCollection(){
			for ($i=1940; $i <=date("Y") ; $i++) { 
				$years[]=$i;
			}
			return $years;
		}

		/**
		 * A collection of months in the year 
		 * @return array $months an array of months in the year
		 */
		public static function monthsCollection(){
			$months=array( "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November","December");
			return $months;
		}
		
		/**
		 * A collection of banks in nigeria 
		 * @return array $banks an array of banks in nigeria
		 */
		public static function banksCollection(){
			$banks=array(
				"Access Bank","Citibank","Diamond Bank","Ecobank","Fidelity Bank","First Bank","First City Monument Bank",
				"Guaranty Trust Bank","Heritage Bank Plc","Keystone Bank","Skye Bank","Stanbic IBTC Bank",
				"Standard Chartered Bank","Sterling Bank","Union Bank","United Bank for Africa",
				"Unity Bank Plc","Wema Bank","Zenith Bank","Jaiz Bank");
			return $banks;
		}		
		
		/**
		 * Convert time from mysql database server localtime to Africa/Lagos localtime
		 * @param string $time the time from mysql database server
		 * @param boolean $development true for development server and false for production server
		 * @param boolean $formated the format of the returned time, if UNIX time or human readable time
		 * @return mixed $formatedTime Africa/Lagos localtime 
		 */
		public static function dbTimeToLocal($time, $development, $formated=TRUE){
			$timeDifference=8*60*60;
			$localTime=($development ? strtotime($time):strtotime($time)+$timeDifference);
			$formatedTime=($formated ? date("g:ia jS F Y", $localTime):$localTime);
			return $formatedTime;
		}
		
		/**
		 * Get all DPR Category in the app
		 * @param boolean $numeric if true returned array is numeric else associative
		 * @return array $collection an array of DPR permit category
		 */
		public static function dprCategoryCollection($numeric = true){
			$collection = [
				'category1' => 'General DPR Category', 
				'category2' => 'Major DPR Category',
				'category3' => 'Specialized DPR Category',
				'category4' => 'Non DPR Category'];
			if($numeric){
				$collection = ['category1','category2','category3','category4'];	
			}
			return $collection;
		}
		
		/**
		 * Get table that store user type data
		 * @return array collection of user type table
		 */
		public static function userTable(){
			return [
				'logger' => 'login',
				'vendor' => 'vendor', 
				'vendor requestor' => 'staff',
				'procurer' => 'staff', 
				'it' => 'staff', 
				'it super' => 'staff', 
				'developer' => 'login',
				];
		}
		
		/**
		 * Get all the various type of users on the app
		 * @return array collection of exact user type 
		 */
		public static function userType(){
			return [
				'logger' => 'logger',
				'vendor' => 'vendor', 
				'vendor requestor' => 'vendor requestor',
				'procurer' => 'procurer', 
				'it' => 'it', 
				'it super' => 'it super', 
				'developer' => 'developer'];
		}
		
		/**
		 * Get all the various member type in the app
		 * @param $numeric boolean if true a numeric array is generated
		 * @return array collection of member type 
		 */
		public static function memberType($numeric = false){
			$type = [
				'member1' => 'Graduate',
				'member2' => 'Associate', 
				'member3' => 'Member Exam', 
				'member4' => 'Member Research'];
			if($numeric){
				$type = ['Graduate','Associate','Member Exam','Member Research'];
			}
			return $type;
		}
		
		/**
		 * Get all the various title in the app
		 * @return array collection of title 
		 */
		public static function titleCollectio(){
			return ['Mr', 'Mrs', 'Miss', 'Dr'];
		}
		
		/**
		 * For sending SMS
		 * @param string $message the message to be sent
		 * @param string $phone the phone no the message is to be sent to
		 * @return string response from the SMS gateway
		 */
		public static function smsSender($message, $phoneNo){
			if(strlen($phoneNo!=11)){
				if (strlen($phoneNo) == 13) {
					$phoneNo = "0".substr($phoneNo, -10);
				}
				if (strlen($phoneNo) == 10) {
					$phoneNo = "0$phoneNo";
				}
			}
		  $sms="http://www.intelsms.com/index.php?option=com_spc&comm=spc_api&username=nim&password=nimsms&sender=LCCN&recipient=$phoneNo&message=".urlencode($message);
	    $ch = curl_init();
	    $timeout = 10;
	    curl_setopt($ch, CURLOPT_URL, $sms);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	    $result = curl_exec($ch); //OK 1.8
	    curl_close($ch);
	    return $result;
		}
				
		/**
		 * Produce a numeric array of just a column in the table of a database
		 * @param string $Db the database handler
		 * @param string $table the table to be worked on in the database
		 * @param string $colunmName column be turned to array
		 * @return array $columns a collection of the colunm's value 
		 */
		public static function numericArray($Db, $table, $columnName){
			$formerTable = $Db->getTable();
			$Db->setTable($table);
			$colums = [];
			if($records = $Db->retrieveData(__LINE__)){
				foreach ($records as $aRecord) {
					$colums[] = $aRecord[$columnName];
				}	
			}
			$Db->setTable($formerTable);
			return $colums;
		}
	}