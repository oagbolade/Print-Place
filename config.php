<?php session_start();
	date_default_timezone_set('Africa/Lagos');
	spl_autoload_register(function ($class){
			$lastSplash=strrpos ($class, "\\");
			$classname=substr($class, $lastSplash);
			require_once 'Classes/'.ucfirst($classname).".php";	
		}
	);

	/* DEVELOPMENT */
	define('ERRORPAGE', 'error');
	define('DEVELOPMENT', 'TRUE');
	define('SITENAME', 'Printshop');	
	define('URLEMAIL', "printshop.com");
	define('CONTACTEMAIL', "info@printshop.com");
	define('WEBMASTEREMAIL', "opyzyle1960@gmail.com");
	define('PHONEADMIN', "07033389938");
	define('SALT', '$2a$12$q.g9b586NIDlO5mPl1y2Cy$');
	define("URL", "http://localhost/printshop/");
	define("DIR", $_SERVER['DOCUMENT_ROOT'] . '/printshop/');
	$token = '!2U@uYh12&u:T&8|x28HT'; 
	define('TOKENRAW', $token);
	define('TOKEN', $token . rand(1000, 9999));

	define('PRIVATEKEYPS', 'sk_test_c30835db5b95b65dcdd11b23d3cd32df44a3c237');
	define('PUBLICKEYPS', 'pk_test_1390999eba5eddf1b91c6af4b691363cf6258cc4');
	unset($token);
	$idGetter = false;

	//database tables
	define('PROFILE', 'profile');
	define('LOGIN', 'login');
	define('ORDERS', 'orders');
	define('PRODUCTS', 'products');
	define('CART', 'cart');
	define('CATEGORY', 'category');
	define('SUBCATEGORY', 'sub_category');
	define('PAYMENT', 'payment');
	define('TRANSACTION', 'transaction');
	define('DESIGN', 'design');

	if (isset($_SESSION['print-new-users'])) {
		$userId = $_SESSION['print-new-users']['id'];
		$userEmail = $_SESSION['print-new-users']['email'];
	}elseif (isset($_SESSION['print-users'])) {
		$userId = $_SESSION['print-users']['id'];
		$userEmail = $_SESSION['print-users']['email'];
	}else{
		$userId = $userEmail = '';
	}