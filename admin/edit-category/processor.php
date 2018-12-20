<?php
	//Get required files
	require_once '../../config.php'; 
	require_once '../../db-config.php';
if (isset($_SESSION['token']) && $_POST['token']==$_SESSION['token']) {
    $category_id = urldecode($_GET['id']);
	//Turn off magic quotes
	Functions::magicQuotesOff();
	
	//Check if the access to this script is coming from its index's page
		
	$dataModel = new DataModel($db_conn, 'error');
	$users = new Users($dataModel);
	$product = new Products($users, ['id'=>$userId]);
	$users->adminAuthority(['id'=>$userId], __FILE__, __LINE__);
    $date = date('Y-m-d h:i:s');
    $image = "";

	//Get validator to validate data sent to this script
	$FormValidator = new Validator();

	//Prepare form data for Validator
	$data[] = ['validationString' => 'non empty', 'dataName' => 'name', 'dataValue' => $_POST['name']];	
	
	//Validate sent data
	$validationResult = $FormValidator->formValidation($data);
	if($validationResult['error']){
		if(!$validationResult['data']['name']) $dataError[] = "Provide the category";
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
	$response = "Category wasn't created";

	if($_FILES['image']['name'][0] == ""){
        $image = $_POST['tmp_image'];
    }
    else{
        $image = uploadImage("image", DIR."images/category/", array("jpg", "png", "jpeg"), 2000, 0);
    }

	$data = ['name'=>$result['name'], 'image'=>$image, 'changed_by'=>$userId];
    $whereData = [
        'id' => $category_id
    ];
	if ($image) {
		if ($product->updateCategory($data, $whereData)) {
			$response = "{$result['name']} Update Successfull";
		}
	}

	//Generate response to be sent back
	$_SESSION['response'] = $response;
    header("Location: ".URL."admin/view-category/");

}else{
	$_SESSION['spoofing'] = "Spoofing";
	header("Location: ".URL."admin/error/");
}