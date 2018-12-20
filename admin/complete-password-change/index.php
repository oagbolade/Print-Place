<?php
require_once '../../config.php';
require_once '../../db-config.php';
if (isset($_GET['ref']) && isset($_GET['email'])) {
	$dataModel = new DataModel($db_conn, 'error');
	$users = new Users($dataModel);
	$FormValidator = new Validator();
	$dataModel->setTable(LOGIN);

	$data[] = ['validationString' => 'non empty', 'dataName' => 'ref', 'dataValue' =>urldecode($_GET['ref'])];
	$data[] = ['validationString' => 'email', 'dataName' => 'email', 'dataValue' =>urldecode($_GET['email'])];
	$validationResult = $FormValidator->formValidation($data);
	if($validationResult['error']){
		if(!$validationResult['data']['ref']) $dataError[] = "Invalid ref supplied".__FILE__;
		if(!$validationResult['data']['email']) $dataError[] = "Invalid email supplied".__FILE__;
		$adminEmails = [CONTACTEMAIL, WEBMASTEREMAIL];
		$emailPush = new EmailPush($adminEmails, [$userEmail]);
		$emailPush->messageAdmin(SITENAME."DANGER: A user attempts to access this page unauthorized. See details - $dataError");
	  	header("Location: .");
	  	exit();
	}
	$result = $validationResult['data'];

	$columns = ['pending_password', 'id'];
	$whereData = ['email'=>$result['email'], 'password_token'=>$result['ref']];
	$dataModel->selectData($columns, $whereData);
	if ($records = $dataModel->fetchRecords()) {
		$columns = ['password'=>$records['pending_password'], 'pending_password'=>NULL, 'password_token'=>NULL];
		$whereData = ['id'=>$records['id']];
		$dataModel->updateData($columns, $whereData);
		if ($dataModel->getNoAffectedRows()) {
			$adminEmails = [CONTACTEMAIL, WEBMASTEREMAIL];
			$emailPush = new EmailPush($adminEmails, [$userEmail]);
			$emailPush->messageUsers(SITENAME.": Password Changed", "Your password has been changed");
			$_SESSION['password-changed-success'] = 'Your password has been changed';
			header("Location: ".URL."admin/");
		}else{
			$_SESSION['password-changed'] = 'Something went wrong. Please, try again.';
			header("Location: ".URL."admin/error/");
		}
	}else{
		$adminEmails = [CONTACTEMAIL, WEBMASTEREMAIL];
		$emailPush = new EmailPush($adminEmails, [$userEmail]);
		$emailPush->messageAdmin(SITENAME."DANGER:", "A user attempts to access this page unauthorized. See details - ".__FILE__."---".__LINE__);
		$_SESSION['password-changed'] = 'Please, try again';
		header("Location: ".URL."admin/error/");
	}
	header("Location: ".URL."admin/");
}else{
	header("Location: ".URL."admin/error/");
}