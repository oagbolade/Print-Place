<?php
	//Get required files
	require_once '../../config.php';
	require_once '../../db-config.php';
	
	//Initialization
	$response = "";
	$pageName = "My Orders";
	$dataModel = new DataModel($db_conn, 'error');
	$users = new Users($dataModel);
	$whereAuthenticate = ['id'=>$userId];
	$userDetails = $users->getUserDetails($whereAuthenticate);
	if (!$users->userAdminAuthority(['id'=>$userId], __FILE__, __LINE__)) {
		header("Location: ".URL."admin/error");
		exit();
	}
    $product = new Products($users, ['id'=>$userId]);
	$_SESSION['token'] = md5(TOKEN);
    $product = new Products($users, ['id'=>$userId]);
    $orders = new Orders($product);
	$userOrders = $orders = $orders->getUserOrders(['buyer'=>$userId]);
	$myOrders = "";
	$kanter = 0;
	
	foreach ($userOrders as $userOrder) {
		++$kanter;
		$image = "No image";
		if ($userOrder[0]['image']) {
			$image = json_decode($userOrder[0]['image'], true);
			$image = $image['image one'];
			$image = "<img src='".URL."images/customer-design/$image' style='width:100px;height:100px;' />";
		}
		$totalCost = number_format($userOrder['total_cost'], 2);
		$products = $product->getAProduct(['id'=>$userOrder['product_id']]);
		$myOrders .= "<tr>
					<td>$kanter</td>
					<td>{$products['name']}</td>
					<td><img src='".URL."images/products/{$products['image']}' style='width:100px;height:100px;' /></td>
					<td>$image</td>
					<td>{$userOrder['quantity']}</td>
					<td>$totalCost</td>
					<td>{$userOrder['order_date']}</td>
					<td>{$userOrder['status']}</td>
				</tr>";
	}
	
	$Tag = new Tag(URL.'admin/');
	$head = $Tag->createHead("Print Shop | View-Orders ", "nav-md home-page", ['css' => ['css/nprogress.css']]);
	
	$mastHead = $Tag->createMastHead($dataModel, $userDetails['email']);
	$menu = $Tag->createSideBar($dataModel, $userDetails['email'], ['parent'=>'My Orders', 'child'=>$pageName]);
	$slogan = $Tag->createFooterSlogan();
	$footer = $Tag->createFooter(['js/custom.js']);

	// Show cancel order form
    function showForm($product_id, $status = null){
        $form = "
               <form method='post' action='processor.php?id=$product_id'>
                     <button type='submit' class='btn btn-danger btn-xs'>Cancel Order</button>
               </form>
        ";
        if ($status !== 'cancelled'){
            return $form;
        }
        return false;
    }
	
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