<?php
	//Get required files
	require_once '../../config.php';
	require_once '../../db-config.php';
if (isset($_SESSION['token'])) {
	//Turn off magic quotes
	Functions::magicQuotesOff();

    //Perform page action
    //$DbHandle = new DBHandler($PDO, "login", __FILE__);
    $dataModel = new DataModel($db_conn, 'error');
    $users = new Users($dataModel);
    $product = new Products($users, ['id'=>$userId]);

    $product_id = urldecode($_GET['id']);
    if (!is_numeric($product_id)) {
        $_SESSION['error'] = 'Product reference not correct';
        header("Location: ".URL.'error/');
    }
    if (isset($product_id) && trim($product_id) === ''){
        $_SESSION['response'] = "Cannot get product to be deleted";
        header("Location: .");
        exit();
    }

    $whereData = [
        'id' => intval($product_id)
    ];

    if (!$product->deleteProduct($whereData)){
        $_SESSION['response'] = "Database Error! Failed to Delete Product";
        header("Location: .");
        exit();
    }

	//Generate response to be sent back
    $_SESSION['response'] = "Product Deleted Successfully";
    header("Location: .");
    exit();
}else{
    $_SESSION['error'] = "Spoofing";
    header("Location: ".URL."admin/error/");
}