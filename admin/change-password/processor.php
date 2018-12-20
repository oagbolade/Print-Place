<?php
require_once '../../config.php';
require_once '../../db-config.php';
if (isset($_POST["change"]) && isset($_SESSION['token']) && $_POST['token']==$_SESSION['token']) {
	$passToken = md5($userEmail.time().rand(100, 999));
	$dataModel = new DataModel($db_conn, 'error');
	$users = new Users($dataModel);
	$FormValidator = new Validator();
	$dataModel->setTable(LOGIN);

	$data[] = ['validationString' => 'password rule', 'dataName' => 'oldPassword', 'dataValue' =>$_POST['oldPassword']];
	$data[] = ['validationString' => 'password rule', 'dataName' => 'newPassword', 'dataValue' =>$_POST['newPassword']];
	$validationResult = $FormValidator->formValidation($data);
	if($validationResult['error']){
		if(!$validationResult['data']['newPassword']) $dataError[] = "Please, provide a strong password";
		if(!$validationResult['data']['oldPassword']) $dataError[] = "Please, provide a strong password";
		$_SESSION['dataError'] = $dataError;
		$_SESSION['dataError'] = $dataError;
		header("Location: .");
		exit();
	}
	if ($_POST['oldPassword'] == $_POST['newPassword']) {
		$dataError[] = "No changes made. Password is the same.";
		$_SESSION['dataError'] = $dataError;
		header("Location: .");
	}
	$result = $validationResult['data'];
	
	$newPassword = $users->encriptPassword($result['newPassword']);
	$oldPassword = $users->encriptPassword($result['oldPassword']);
	$result = $validationResult['data'];
	$columns = ['pending_password'=>$newPassword, 'password_token'=>$passToken];
	$whereData = ['id'=>$userId, 'password'=>$oldPassword];
	$dataModel->updateData($columns, $whereData);
	if ($dataModel->getNoAffectedRows()) {
		$email = urlencode($userEmail);
		$ref = urlencode($passToken);
		$adminEmails = [CONTACTEMAIL, WEBMASTEREMAIL];
		$emailPush = new EmailPush($adminEmails, [$userEmail], $_POST['token']);
		$emailPush->messageUsers(SITENAME.": Password Change", "Click on this <a href='".URL."admin/complete-password-change/?email=$email&ref=$ref'>link</a> to complete your password change.If you didn't make the request, please secure your account.");
		$_SESSION['change-password-success'] = 'Check your email to complete your password change.';
	}else{
		$_SESSION['change-password'] = 'Please, try again';
	}
	// header("Location: .");
}else{
	header("Location: .");
}