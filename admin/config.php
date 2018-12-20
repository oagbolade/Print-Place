<?php
	session_start();
	date_default_timezone_set('Africa/Lagos');
	
	define('SITENAME', 'Seplat eVendor Manager');	
	define('URLEMAIL', "evendor.com");
	define('CONTACTEMAIL', "info@evendor.com");
	define('PHONEADMIN', "08034265103");
	define('WEBMASTEREMAIL', "info@alabiansolutions.com");
	define('SALT', '$2a$12$q.g9b586NIDlO5mPl1y2Cy$');
	define('ERRORPAGE', 'error');
	$token = '!2U@uYh12&u:T&8|x28HT'; 
	define('TOKENRAW', $token);
	define('TOKEN', $token . rand(1000, 9999));
	unset($token);
    
  /*Development Server*/
  define('URL', "http://localhost/seplat/");
  define('ROOT', $_SERVER['DOCUMENT_ROOT'] . '/seplat/');
	define('DB_USER', 'root');
	define('DB_NAME', 'seplat');
	define('DB_PASSWORD', '');
	define('DB_SERVER', 'localhost');
	define('DEVELOPMENT', TRUE);
  
  /*Production Server
  define('URL', "http://myclubfans.com/");
  define('ROOT', $_SERVER['DOCUMENT_ROOT'] . '/');
	define('DB_USER', '');
	define('DB_NAME', '');
	define('DB_PASSWORD', '');
	define('DB_SERVER', '');
  define('DEVELOPMENT', FALSE);*/
	
	spl_autoload_register(function ($class){
			$lastSplash=strrpos ($class, "\\");
			$classname=substr($class, $lastSplash);
			require_once ROOT.'Classes/'.ucfirst($classname).".php";	
		}
	);