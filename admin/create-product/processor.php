<?php
	//Get required files
	require_once '../../config.php'; 
	require_once '../../db-config.php';
if (isset($_SESSION['token']) && $_POST['token']==$_SESSION['token']) {
	
	//Turn off magic quotes
	Functions::magicQuotesOff();
	
	//Check if the access to this script is coming from its index's page
		
	$dataModel = new DataModel($db_conn, 'error');
	$users = new Users($dataModel);
	$product = new Products($users, ['id'=>$userId]);
	$users->adminAuthority(['id'=>$userId], __FILE__, __LINE__);

	//Get validator to validate data sent to this script
	$FormValidator = new Validator();

	//Prepare form data for Validator
	$data[] = ['validationString' => 'non empty', 'dataName' => 'name', 'dataValue' => $_POST['name']];	
	$data[] = ['validationString' => 'non empty', 'dataName' => 'category', 'dataValue' => $_POST['category']];	
	$data[] = ['validationString' => 'non empty', 'dataName' => 'size', 'dataValue' => $_POST['size']];	
	$data[] = ['validationString' => 'number', 'dataName' => 'cost', 'dataValue' => $_POST['cost']];	
	$data[] = ['validationString' => 'number', 'dataName' => 'discount', 'dataValue' => $_POST['discount']];
	// $data[] = ['validationString' => 'number', 'dataName' => 'designFee', 'dataValue' => $_POST['designFee']];
	// $data[] = ['validationString' => 'non empty', 'dataName' => 'designer', 'dataValue' => $_POST['designer']];	
	$data[] = ['validationString' => 'non empty textarea', 'dataName' => 'description', 'dataValue' => $_POST['description']];	
	$data[] = ['validationString' => 'non empty textarea', 'dataName' => 'material', 'dataValue' => $_POST['material']];	
	$data[] = ['validationString' => 'non empty textarea', 'dataName' => 'finishing', 'dataValue' => $_POST['finishing']];	
	$data[] = ['validationString' => 'non empty textarea', 'dataName' => 'delivery', 'dataValue' => $_POST['delivery']];
	
	//Validate sent data
	$validationResult = $FormValidator->formValidation($data);
	if($validationResult['error']){
		if(!$validationResult['data']['name']) $dataError[] = "Provide the product name";
		if(!$validationResult['data']['size']) $dataError[] = "Size field should not be empty";
		if(!$validationResult['data']['cost']) $dataError[] = "Cost field should not be empty";
		if(!$validationResult['data']['discount']) $dataError[] = "Discount field should not be empty";
		// if(!$validationResult['data']['designer']) $dataError[] = "Designer field should not be empty";
		// if(!$validationResult['data']['designFee']) $dataError[] = "Design fee field should not be empty";
		if(!$validationResult['data']['description']) $dataError[] = "Description field should not be empty";
		if(!$validationResult['data']['material']) $dataError[] = "Material field should not be empty";
		if(!$validationResult['data']['finishing']) $dataError[] = "Finishing field should not be empty";
		if(!$validationResult['data']['delivery']) $dataError[] = "Delivery field should not be empty";
		$_SESSION['dataError'] = $dataError;
	  	header("Location: .");
	  	exit();
	}

	function uploadImage($image, $dir, $arr, $size, $count)
	{
		$fileUpload = new FileUpload($image, $dir, $count, time());
		$list = $fileUpload->setFileExtensionsAccepted($arr);
		$fileUpload->checkFileSize($size);
		$ext = $fileUpload->getFileExtension();
		$file = time().'.'.$ext;
		if ($fileUpload->uploadFileWithValidation()) return $file;
		return false;
	}
	$result = $validationResult['data'];
	$response = "Product wasn't created";
	$image = uploadImage("image", DIR."images/products/", array("jpg", "png", "jpeg"), 2000, 0);
	/*
	$data = ['name'=>$result['name'], 'image'=>$image, 'added_by'=>$userId, 'size'=>$result['size'], 'cost'=>$result['cost'], 'discount'=>$result['discount'], 'designer'=>$result['designer'], 'design_fee'=>$result['designFee'], 'description'=>$result['description'], 'material'=>$result['material'], 'finishing'=>$result['finishing'], 'category'=>$result['category']];
	*/
	$data = ['name'=>$result['name'], 'image'=>$image, 'added_by'=>$userId, 'size'=>$result['size'], 'cost'=>$result['cost'], 'discount'=>$result['discount'], 'description'=>$result['description'], 'material'=>$result['material'], 'finishing'=>$result['finishing'], 'category'=>$result['category'], 'delivery'=>$result['delivery']];
	if ($image) {
		if ($product->createProducts($data)) {
		echo "$image";
			$response = "{$result['name']} product has been created";
		}
	}
	//Generate response to be sent back
	$_SESSION['response'] = $response;
	header("Location: .");
}else{
	$_SESSION['spoofing'] = "Spoofing";
	header("Location: ".URL."admin/error/");
}