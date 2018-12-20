<?php
require_once '../config.php';
require_once '../db-config.php';

$response = "";
$pageName = "Products";
$dataModel = new DataModel($db_conn, 'error');
$users = new Users($dataModel);
$product = new Products($users, ['id'=>$userId]);
$cart = new Cart($product);
$cartNo = $cart->cartNumbers(['buyer'=>$userId]);

$userDetails = $users->getUserDetails(['id'=>$userId]);
$FormValidator = new Validator();
if (isset($_GET['pid'])) {
	$pid = $FormValidator->getNumericData($_GET['pid']);
}else{
	header("Location: ".URL);
}
$_SESSION['token'] = md5(TOKEN);
$products = $product->getAProduct(['id'=>$pid]);
$_SESSION['product_id'] = $pid; 