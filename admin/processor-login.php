<?php
require_once "../config.php";
require_once "../db-config.php"; 

if (isset($_POST['login']) && isset($_SESSION['token']) && $_POST['token']==$_SESSION['token']) {
	unset($_SESSION['token']);
	$FormValidator = new Validator();
	$data[] = ['validationString' => 'email', 'dataName' => 'email', 'dataValue' => $_POST['email']];
	$data[] = ['validationString' => 'password rule', 'dataName' => 'password', 'dataValue' => $_POST['password']];
	if (isset($_POST['pid'])) {
		$data[] = ['validationString' => 'sanitize', 'dataName' => 'pid', 'dataValue' => $_POST['pid']];	
	}
	$validationResult = $FormValidator->formValidation($data);
	if($validationResult['error']){
		if(!$validationResult['data']['email'] || !$validationResult['data']['email']){
			$dataError[] = 'Email or password field incorrect';
		}
		header("Location: ".URL.'admin/');
		exit();
	}
	$result = $validationResult['data'];
	$dataModel = new DataModel($db_conn, 'error');
	$users = new Users($dataModel);
	$dataModel->setTable(LOGIN);
	$dataModel->selectData(['id', 'email', 'authority'], ['email'=>$result['email']]);
	$selectData = $dataModel->fetchRecords();
	$emailExist = $selectData['email'];
	$idExist = $selectData['id'];
	$authority = $selectData['authority'];
	$password = $users->encriptPassword($result['password']);
	if ($idExist && $emailExist) {
		if (isset($_SESSION['logger-type']) && $_SESSION['logger-type'] === 'New Logger') {
			if ($users->firstTimeLogger($emailExist, $password, $_POST['token'])) {
				$_SESSION['login-response'] = 'Welcome to '.SITENAME;
				unset($_SESSION['logger-type']);
				if(isset($_POST['pid']) && $_POST['pid']) {
					$pid = urlencode($result['pid']); 
					header("Location: ".URL."products/?pid=$pid");
				}
				if ($authority == 'admin') {
					header("Location: ".URL."admin/home/");
					exit();
				}
				header("Location: ".URL);
				exit();
			} 
		}
		if($users->loginUser($emailExist, $password)) {
			$_SESSION['login-response'] = 'Welcome Back';
			if(isset($_POST['pid']) && $_POST['pid']) {
				$pid = urlencode($result['pid']); 
				header("Location: ".URL."products/?pid=$pid");
				exit();
			}
			if ($authority == 'admin') {
				header("Location: ".URL."admin/home/");
				exit();
			}
			header("Location: ".URL);
			exit();
		}else{
			$_SESSION['login-response'] = 'There was an error. Please, login again.';
			header("Location: ".URL.'admin/');
			exit();
		}
		
	}else{
		$_SESSION['login-response'] = 'Please, register';
		header("Location: ".URL.'admin/signup/');
	}
}else{
	header("Location: ".URL."error/");
}