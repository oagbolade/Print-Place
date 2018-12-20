<?php
	//Get required files
	require_once '../config.php'; 
	require_once '../db-config.php'; 
	
	//Turn off magic quotes
	Functions::magicQuotesOff();
	
	//Get validator to validate data sent to this script
	$FormValidator = new Validator();
	
	//Prepare form data for Validator
	$data[] = ['validationString' => 'email', 'dataName' => 'email', 'dataValue' => $_POST['resetEmail']];
	
	//Validate sent data
	$validationResult = $FormValidator->formValidation($data);
	if($validationResult['error']){
		$_SESSION['dataError'] = ['form' => 'password reset', 'message' => ['Invalid email']];
  	header("Location: ".URL."admin/");
  	exit();
	}
	
	$dataModel = new DataModel($db_conn, 'error');
    $users = new Users($dataModel);
    $email = $validationResult['data']['email'];
    $message = '';
	if($users->isEmailPhoneUsernameUsed('email', ['email'=>$email])){
		$emailPush = new EmailPush(CONTACTEMAIL, ['email'=>$email]);
		$function = new Functions();
		$token = substr($function->characterFromASCII($function->asciiTableDigitalAlphabet(),'string'), 0,16);
		$dataModel->setTable(LOGIN);
		$dataModel->updateData(['password_token'=>$token], ['email'=>$email]);
		$tokenUrl="
			<a style='color:#fff; text-decoration:underline;' href='".URL."admin/password-change/?token=".urlencode($token)."&email=".urlencode($email)."'>
				".URL."admin/password-change/?token=$token&email=$email
			</a>";
		$message.="
			<p style='margin-bottom:20px;'>Good Day </p>
			<p style='margin-bottom:8px;'>
				You are getting this mail because you requested to change your password on  ".URL." You will 
				need to click on the link below or visit the link by copying and pasting it in your browser and hit enter.
				<br/>
				$tokenUrl
			</p>
			<p style='margin-bottom:8px;'>
				<span style='font-weight:bold;'>NB</span><br/>
			   If you did not requeste a password change at ".URL." please contact us immediately. Please do 
			   not reply to this mail as it is sent from an unmonitored address. You can contact us via 
			   ".CONTACTEMAIL."
			</p>";
			$emailPush->messageUsers(SITENAME.": Reset Password", $message);
		$link = "";
		if(!DEVELOPMENT) $link = "";
		$message = "
		Please check your email <span class='al-emphasis'>$email</span>, the password reset
		code will be sent to it within the next 5 minutes. If the mail is not in your inbox check your spam folder and 
		please kindly whitelist our mail to prevent it from been filtered by your email program in the future.
		$link";	
	}
	else {
		$message = "
			<span class='al-emphasis'>$email</span> is not associated with any account on our 
			system";
	}
	echo $message;
	exit();
	$_SESSION['response'] = ['form' => 'password reset', 'message' => $message];
	header("Location: ".URL."admin/");
  exit();