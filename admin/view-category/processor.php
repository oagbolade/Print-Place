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

    $category_id = urldecode($_GET['id']);
    if (!is_numeric($category_id)) {
        $_SESSION['error'] = 'Category reference not correct';
        header("Location: ".URL.'error/');
    }
    if (isset($category_id) && trim($category_id) === ''){
        $_SESSION['response'] = "Cannot get category to be deleted";
        header("Location: .");
        exit();
    }

    $whereData = [
        'id' => $category_id
    ];

    if (!$product->deleteCategory($whereData)){
        $_SESSION['response'] = "Database Error! Failed to Delete Category";
        header("Location: .");
        exit();
    }

	//Generate response to be sent back
    $_SESSION['response'] = "Category Deleted Successfully";
    header("Location: .");
    exit();
}else{
    $_SESSION['error'] = "Spoofing";
    header("Location: ".URL."admin/error/");
}