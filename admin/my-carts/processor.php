<?php
	//Get required files
	require_once '../../config.php'; 
	require_once '../../db-config.php';
if (isset($_SESSION['token'])) {
	if(!isset($_GET['pid'])) header("Location: .");
	
	//Turn off magic quotes
	Functions::magicQuotesOff();
	
	//Check if the access to this script is coming from its index's page
		
	$dataModel = new DataModel($db_conn, 'error');
	$users = new Users($dataModel);
	$product = new Products($users, ['id'=>$userId]);

    $userDetails = $users->getUserDetails(['id'=>$userId]);
    if (!$users->userAdminAuthority(['id'=>$userId], __FILE__, __LINE__)) {
        header("Location: ".URL."admin/error");
        exit();
    }

	//Get validator to validate data sent to this script
	$FormValidator = new Validator();

	//Prepare form data for Validator
	$data[] = ['validationString' => 'number', 'dataName' => 'pid', 'dataValue' => $_GET['pid']];	
	
	//Validate sent data
	$validationResult = $FormValidator->formValidation($data);
	if($validationResult['error']){
		if(!$validationResult['data']['pid']) $dataError[] = "Please, try again";
		$_SESSION['dataError'] = $dataError;
	  	header("Location: .");
	  	exit();
	}

	$result = $validationResult['data'];
	$response = "Cart not deleted";
	$dataModel = new DataModel($db_conn, 'error');
	$users = new Users($dataModel);
	$product = new Products($users, ['id'=>$userId]);
	$cart = new Cart($product);
	if ($cart->removeProductFromCart(['product_id'=>$result['pid'], 'buyer'=>$userId])) {
        $response = "Cart has been deleted";
    }

	//Generate response to be sent back
	echo $_SESSION['response'] = $response;
	header("Location: .");
}else{
	$_SESSION['spoofing'] = "Spoofing";
	header("Location: ".URL."admin/error/");
}