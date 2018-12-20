<?php
	//Get required files
	require_once '../../config.php';
	require_once '../../db-config.php';
	
	$_SESSION['token'] = md5(TOKEN);
	$Tag = new Tag(URL.'admin/');
	
	$head = $Tag->createHead("Print Shop | Change Password", "login al-login", ['css' => ['admin/vendors/animate.css/animate.min.css']]);
	$footer = $Tag->createFooter();
	
	//Initialization
	$form = "";
	$noInitilization = false;
	$response = $Tag->createAlert("", "Password reset token is invalid", 'danger', false);
	
	//Error in data sent for processing
	if(isset($_SESSION['dataError'])){
		if(!isset($_SESSION['spoofing'])){
			$content = "<ul>";
			foreach ($_SESSION['dataError'] as $aMessage) {
				$content .= "<li class='text-left'>$aMessage</li>";	
			}
			$content .= "</ul>";
			$response = $Tag->createAlert("", $content, 'danger', false);
			$noInitilization = true;
		} 
		else {
			$functions = new Functions();
			$ErrorAlerter = new ErrorAlert($_SESSION['spoofing'], $functions);
			// $ErrorAlerter->sendAlerts();
			$loginMsg = $Tag->createAlert("System Error", "Ouch we are sorry something went wrong, we think your internet connection may be slow", 'danger', true);
			unset($_SESSION['spoofing']);
		}
		unset($_SESSION['dataError']);
	}
	
	//Reset code validation
	if(isset($_GET['token']) && isset($_GET['email'])){
		$FormValidator = new Validator();
		$token = urldecode($FormValidator->getSanitizeData($_GET['token']));
		$email = urldecode($FormValidator->getSanitizeData($_GET['email']));
		$dataModel = new DataModel($db_conn, 'error');
	    $users = new Users($dataModel);
		if($userDetails = $users->getUserDetails(['email'=>$email])){
			if($userDetails['password_token'] != $token){
				$response = $Tag->createAlert("", "Password reset token is invalid", 'danger', false);
			} 
			else {
				if(!$noInitilization) $response = "";
				$form = generateForm($email, $token);
			}
		}
	}
	else {
		$email = "";
		$token = "";
	}
	
	//Response after data processing
	if(isset($_SESSION['response'])){
		$response = $Tag->createAlert("", $_SESSION['response'], 'success', false);
		unset($_SESSION['response']);
	}
	
	$pageHeader = "
		<div>
			<a href='". URL ."'>
				<!--<img class='al-login-logo' src='". URL ."admin/images/logo.png'/>-->			
			</a>
      <h1>Print Shop</h1>
    </div>
    <br />
	";	
	
	function generateForm($email, $resetToken){
		$form ="
			<form action='". htmlspecialchars('processor.php') ."' method='post'>
      	<input type='hidden' name='token' value='{$_SESSION['token']}' />
      	<input type='hidden' name='email' value='$email' />
      	<input type='hidden' name='resetToken' value='$resetToken' />
        <h2>Change Password</h2>
        <div>
        	<span style='float: left; text-shadow: none;'>min 8 characters</span>
          <input type='password' name='password' class='form-control' placeholder='Password' required />
        </div>
        <div>
          <input type='password' name='repeatPassword' class='form-control' placeholder='Password Again' required />
        </div>
        <div>
          <button class='btn btn-default submit' type='submit'>Change</button>
        </div>
        <div class='clearfix'></div>
        <div class='separator'>
          <div class='clearfix'></div>
          <br />
          <div>
			      <p>&copy; ". date("Y") ." All Rights Reserved. Print Shop <a href=''>Privacy and Terms</a></p>
			    </div>
        </div>
      </form>
		";
		return $form;
	}