<?php
require_once 'config.php';
require_once 'db-config.php';

$response = "";
$pageName = "Create Product";
$dataModel = new DataModel($db_conn, 'error');
$users = new Users($dataModel);
$product = new Products($users, ['id'=>$userId]);
$userDetails = $users->getUserDetails(['id'=>$userId]);

$categories = $product->getAllCategories();
$cart = new Cart($product);
$cartNo = $cart->cartNumbers(['buyer'=>$userId]);

$printCategories = '';
if (is_array($categories)) {
	foreach ($categories as $category) {
		$printCategories .= "<div class='col-lg-3 col-sm-6 col-12'>
							    <div class='category-wrap'>
							        <img src='images/category/{$category['image']}' alt=''>
							        <div class='category-content flex-style'>
							            <h3>{$category['name']}</h3>
							        </div>
							    </div>
							</div>";
	}
}

$products = $product->getAllProducts();
$printProducts = '';
if (is_array($products)) {
	foreach ($products as $product) {
		$cost = number_format($product['cost'], 2);
		$printProducts .= "<li class='col-lg-3 col-sm-6 col-12'>
						    <div class='product-wrap'>
						        <a href='products/?pid={$product['id']}'>
							        <div class='product-img'>
							            <img src='images/products/{$product['image']}' alt=''>
							        </div>
						        </a>
						        <div class='product-content fix'>
						            <h3><a href='#'>{$product['name']}</a></h3>
						            <h3><a href='#'>&#8358;$cost</a></h3>
						        </div>
						    </div>
						</li>";
	}
}