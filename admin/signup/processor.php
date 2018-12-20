<?php
require_once "../../config.php";
require_once "../../db-config.php"; 

if (isset($_POST["submit"]) && isset($_SESSION['token']) && $_POST['token']==$_SESSION['token']) {
	unset($_SESSION['token']);
	$FormValidator = new Validator();
	$data[] = ['validationString' => 'sanitize', 'dataName' => 'fname', 'dataValue' => ucfirst($_POST['fname'])];
	$data[] = ['validationString' => 'sanitize', 'dataName' => 'lname', 'dataValue' => ucfirst($_POST['lname'])];
    $data[] = ['validationString' => 'email', 'dataName' => 'email', 'dataValue' => strtolower($_POST['email'])];
    $data[] = ['validationString' => 'password rule', 'dataName' => 'password', 'dataValue' => $_POST['password']];
    $data[] = ['validationString' => 'sanitize', 'dataName' => 'user_type', 'dataValue' => ucfirst($_POST['accountType'])];
    $data[] = ['validationString' => 'sanitize', 'dataName' => 'phone', 'dataValue' => ucfirst($_POST['phone'])];
    
	$validationResult = $FormValidator->formValidation($data);
	if($validationResult['error']){
		if(!$validationResult['data']['email']) $dataError[] = "Please, provide a valid email";
		if(!$validationResult['data']['password']) $dataError[] = "Please, provide a strong password";
		$_SESSION['dataError'] = $dataError;
	  	header("Location: ".URL.'admin/signup/');
	  	exit();
	}
	if ($_POST['password'] !== $_POST['rpassword']) {
		$dataError[] = "Passwords do not match";
		$_SESSION['dataError'] = $dataError;
	  	header("Location: ".URL.'admin/signup/');
	  	exit();
	}
	$result = $validationResult['data'];
	$dataModel = new DataModel($db_conn, 'error');
	$users = new Users($dataModel);

	$profile = [];
	foreach ($result as $key => $post) {
		if (!in_array($key, ['email','password'])) {
			if (!empty($post)) {
				$profile[$key] = $post;
			}
		}
	}
	
	$login = ['email'=>$result['email'], 'password'=>$users->encriptPassword($result['password']), 'activation_token'=>$_POST['token']];
	$token = $_POST['token'];
	$dataModel->setTable(LOGIN);
	$emailExist = $users->isEmailPhoneUsernameUsed('email', ['email'=>$result['email']]);
	if (!$emailExist) {
		if ($users->signUpUser($login, $profile)) {
			$response = 'Check your email to complete your registration';
			$adminEmails = [WEBMASTEREMAIL, CONTACTEMAIL];
			$emailPush = new EmailPush($adminEmails, [$result['email']], $_POST['token']);
			$emailPush->messageUsers("Users Subject", "Thanks for registering. Please, follow the link below to complete your registration.<a href='http://sworte.com/?token=$token'>Sworte</a>");
			$emailPush->messageAdmin("Admin Subject", "We have a new client");
			$_SESSION['signup-response'] = $response;
			header("Location: ".URL.'admin/');
			exit();
		}elseif ($users->signUpAdmin($login, $profile)) {
			$response = 'Check your email to complete your registration';
			$adminEmails = [WEBMASTEREMAIL, CONTACTEMAIL];
			$emailPush = new EmailPush($adminEmails, [$result['email']], $_POST['token']);
			$emailPush->messageUsers("Users Subject", "Thanks for registering. Please, follow the link below to complete your registration.<a href='http://sworte.com/?token=$token'>Sworte</a>");
			$emailPush->messageAdmin("Admin Subject", "We have a new client");
			$_SESSION['signup-response'] = $response;
			header("Location: ".URL.'admin/');
			exit();
		}else{
			$response = 'Please, try again';
		}
	}else{
		$response = 'Email already exist';
	}
	$_SESSION['signup-response'] = $response;
	header("Location: ".URL.'admin/signup/');
	exit();
}else{
	header("Location: ".URL."admin/error/");
	exit();
}