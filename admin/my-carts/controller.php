<?php
	//Get required files
	require_once '../../config.php';
	require_once '../../db-config.php';
	
	//Initialization
	$response = "";
	$pageName = "My Carts";
	$dataModel = new DataModel($db_conn, 'error');
	$users = new Users($dataModel);
	$product = new Products($users, ['id'=>$userId]);
	$cart = new Cart($product);

	$whereAuthenticate = ['id'=>$userId];
	$userDetails = $users->getUserDetails($whereAuthenticate);
	if (!$users->userAdminAuthority(['id'=>$userId], __FILE__, __LINE__)) {
		header("Location: ".URL."admin/error");
		exit();
	}

	$_SESSION['token'] = md5(TOKEN);

    if (isset($_POST['cart'])) {
		$productId = $_POST['cart'];
	    $cartProducts = $product->getAProduct(['id'=>$productId]);
		$cart->addToCart(['buyer'=>$userId, 'product_id'=>$productId]);
    }
	$myCarts = "";
	$kanter = 0;
	
	$userCarts = $cart->getCartProducts(['buyer'=>$userId]);
	if (is_array($userCarts)) {
		foreach ($userCarts as $userCart) {
			++$kanter;
			$date = date('M d, Y', strtotime($userCart['date']));
			$products = $product->getAProduct(['id'=>$userCart['product_id']]);
			$totalCost = number_format($products['cost'], 2);
			$myCarts .= "<tr>
						<td>$kanter</td>
						<td>{$products['name']}</td>
						<td><img src='".URL."images/products/{$products['image']}' style='width:100px;height:100px;' /></td>
						<td>$totalCost</td>
						<td>$date</td>
						<td><a class='btn btn-info btn-xs' href='".URL."products/?pid={$products['id']}'>order</a></td>
						<td><a class='btn btn-danger btn-xs' href='processor.php/?pid={$products['id']}'><span class='fa fa-remove'></span></a></td>
					</tr>";
		}
	}
	$Tag = new Tag(URL.'admin/');
	$head = $Tag->createHead("Print Shop | view cart ", "nav-md home-page", ['css' => ['css/nprogress.css']]);
	
	$mastHead = $Tag->createMastHead($dataModel, $userDetails['email']);
	$menu = $Tag->createSideBar($dataModel, $userDetails['email'], ['parent'=>'My Carts', 'child'=>'']);
	$slogan = $Tag->createFooterSlogan();
	$footer = $Tag->createFooter(['js/custom.js']);
	
	//Error in data sent for processing
	if(isset($_SESSION['dataError'])){
		if(!isset($_SESSION['spoofing'])){
			$content = "<ul>";
			foreach ($_SESSION['dataError'] as $aMessage) {
				$content .= "<li class='text-left'>$aMessage</li>";	
			}
			$content .= "</ul>";
			$response = $Tag->createAlert("", $content, 'danger', true);
		}
		else{
			$functions = new Functions();
			$ErrorAlerter = new ErrorAlert($_SESSION['spoofing'], $functions);
			$ErrorAlerter->sendAlerts();
			$response = $Tag->createAlert("System Error", "Ouch we are sorry something went wrong, we think your internet connection may be slow", 'danger', true);
			unset($_SESSION['spoofing']);
		}
		unset($_SESSION['dataError']);
	}	
	//Response after data processing
	if(isset($_SESSION['response'])){
		$response = $Tag->createAlert("", $_SESSION['response'], 'success', true);
		unset($_SESSION['response']);
	}