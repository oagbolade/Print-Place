<?php
	//Get required files
	require_once '../config.php'; 
	require_once '../db-config.php';

if (isset($_SESSION['token']) && $_POST['token']==$_SESSION['token']) {
	
	//Turn off magic quotes
	Functions::magicQuotesOff();
	
	//Get validator to validate data sent to this script
	$FormValidator = new Validator();

	//Check if the access to this script is coming from its index's page
	
	$dataModel = new DataModel($db_conn, 'error');
	$users = new Users($dataModel);
	if (!$users->userAdminAuthority(['id'=>$userId], __FILE__, __LINE__)) {
		$_SESSION["order-product"] = "Login to order product";
		unset($_SESSION["order-product"]);
		header("Location: ".URL."admin/?pid=$pid");
		exit();
	}
	
	$product = new Products($users, ['id'=>$userId]);
	$order = new Orders($product);
	//Prepare form data for Validator
	if (isset($_POST['description'])) {
		$data[] = ['validationString' => 'sanitize', 'dataName' => 'description', 'dataValue' => $_POST['description']];
	}
	$data[] = ['validationString' => 'number', 'dataName' => 'quantity', 'dataValue' => $_POST['quantity']];
	$data[] = ['validationString' => 'number', 'dataName' => 'amount', 'dataValue' => $_POST['amount']];
	if (isset($_POST['discount'])) {
		$data[] = ['validationString' => 'number', 'dataName' => 'discount', 'dataValue' => $_POST['discount']];
	}
	$data[] = ['validationString' => 'number', 'dataName' => 'pid', 'dataValue' => $_POST['pid']];
	
	//Validate sent data
	$validationResult = $FormValidator->formValidation($data);
	if($validationResult['error']){
		if(!$validationResult['data']['quantity']) $dataError[] = "Try again";
		if(!$validationResult['data']['amount']) $dataError[] = "Try again";
		if(!$validationResult['data']['pid']) $dataError[] = "Try again";
		if(isset($_POST['discount'])){
			if(!$validationResult['data']['discount']) $dataError[] = "Try again";
		}
		$_SESSION['dataError'] = $dataError;
	  	header("Location: .");
	  	exit();
	}

	function uploadImage($passport, $dir, $arr, $size, $count)
	{
		$fileUpload = new FileUpload($passport, $dir, $count, time().$count);
		$list = $fileUpload->setFileExtensionsAccepted($arr);
		$fileUpload->checkFileSize($size);
		$ext = $fileUpload->getFileExtension();
		$image1 = $image2 = $image3 = "";
		if ($count == 0) {
			$image1 = time().$count.'.'.$ext;
		}
		if ($count == 1) {
			$image2 = time().$count.'.'.$ext;
		}
		if ($count == 2) {
			$image3 = time().$count.'.'.$ext;
		}
		var_dump($fileUpload->uploadFileWithValidation());
		return [$image1, $image2, $image3];
	}

	$kanter = 0;
	$images = [];
	if (isset($images[0])) {
		foreach ($_FILES['image']['type'] as $image) {
			$images[] = uploadImage("image", DIR."images/customer-design/", array("jpg", "png", "jpeg"), 2000, $kanter)[$kanter];
			++$kanter;
		}
	}
	$image1 = $image2 = $image3 = "";
	$designData = [];
	if (isset($images[0])) {
		$image1 = $images[0];
	}
	if (isset($images[1])) {
		$image2 = $images[1];
	}
	if (isset($images[2])) {
		$image3 = $images[2];
	}
	$designImages = json_encode([
		'image one'=>$image1,
		'image two'=>$image2,
		'image three'=>$image3
	]);
	$result = $validationResult['data'];
	$response = "There was an error. Please, try again";
	if (isset($result['discount'])) {
		$amount = $result['amount'] - $result['discount'];	
		$totalCost = $result['quantity'] * $amount;
	}else{
		$totalCost = $result['quantity'] * $result['amount'];
	}
	if (isset($images[0])) $designData = ['content'=>$result['description'], 'image'=>$designImages];
	$orderData = ['buyer'=>$userId, 'product_id'=>$result['pid'], 'quantity'=>$result['quantity'], 'amount'=>$result['amount'], 'total_cost'=>$totalCost, 'discount'=>$result['discount'], 'status'=>'new'];
	if ($order->orderProductWithSpec($designData, $orderData, __FILE__, __LINE__)) {
		$response = "Pay to complete your order";
	}
	
	//Generate response to be sent back
	$_SESSION['response'] = $response;
	header("Location: ".URL."admin/payment/");
	exit();
}else{
	$_SESSION['spoofing'] = "Spoofing";
	header("Location: ".URL."admin/error/");
}