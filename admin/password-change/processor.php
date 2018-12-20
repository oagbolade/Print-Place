<?php
	//Get required files
	require_once '../../config.php'; 
	require_once '../../db-config.php'; 
	
	//Turn off magic quotes
	Functions::magicQuotesOff();
	
	//Check if the access to this script is coming from password change index page
  if(isset($_POST['token']) && $_POST['token']==$_SESSION['token']){
  	// unset($_SESSION['token']);
		//Get validator to validate data sent to this script
		$FormValidator = new Validator();
		
		//Check if password supplied are equal
		$passwordDiffError = false;
		if($_POST['password'] != $_POST['repeatPassword']) $passwordDiffError = true;
		
		//Prepare form data for Validator
		$data[] = ['validationString' => 'email', 'dataName' => 'email', 'dataValue' => $_POST['email']];
		$data[] = ['validationString'=>'password rule', 'dataName'=>'email', 'dataValue'=>$_POST['password']];
		
		//Validate sent data
		$validationResult = $FormValidator->formValidation($data);
		if($validationResult['error']){
			if(!$validationResult['data']['email']) $dataError[] = "Invalid email";
			if(!$validationResult['data']['password']) $dataError[] = "Invalid password (must contain capital letter, number & minimum 8 characters)";
			if($_POST['newPassword'] != $_POST['repeatNewPassword']) $dataError[] = "Password and repeated password differs";
			echo $_SESSION['dataError'] = $dataError;
	  		// header("Location: ".URL."admin/password-change/?token=". urlencode($_POST['resetToken']) ."&email=". urlencode($_POST['email']));
	  		exit();
  		}
  		
		$dataModel = new DataModel($db_conn, 'error');
		$users = new Users($dataModel);
		$dataModel->setTable(LOGIN);
		$dataModel->updateData(['password' => $users->encriptPassword($_POST['password']), 'password_token'=>NULL], ['email' => $_POST['email']]);
		echo $_SESSION['response'] = "Your password has been successfully changed. You can now <a style='text-decoration:underline;' href='". URL ."'>LOGIN</a> with your new password";
		// header("Location: .");
	  exit();
	}
	else {
		echo $_SESSION['spoofing']="CSRF suspected in  ". __FILE__;
		$_SESSION['dataError']=TRUE;
		// header("Location: .");
		exit();
	}