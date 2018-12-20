<?php
	//Get required files
	require_once '../../config.php';
	require_once '../../db-config.php';
	
	//Initialization
	$response = "";
	$pageName = "Payment";
	$dataModel = new DataModel($db_conn, 'error');
	$users = new Users($dataModel);
	$product = new Products($users, ['id'=>$userId]);
	$order = new Orders($product);
	$userDetails = $users->getUserDetails(['id'=>$userId]);
	$pid = "";
	if (isset($_SESSION['product_id'])) {
		$pid = $_SESSION['pid'] = $_SESSION['product_id'];
		// unset($_SESSION['product_id']);
	}
	if (!$users->userAdminAuthority(['id'=>$userId], __FILE__, __LINE__)) {
		header("Location: ".URL."admin/");
		exit();
	}
	
	$_SESSION['token'] = md5(TOKEN);
	$Tag = new Tag(URL.'admin/');
	$head = $Tag->createHead("Print Shop | Home ", "nav-md home-page", ['css' => ['css/nprogress.css']]);
	
	$mastHead = $Tag->createMastHead($dataModel, $userDetails['email']);
	$menu = $Tag->createSideBar($dataModel, $userDetails['email'], ['parent'=>'Payment', 'child'=>$pageName]);
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
			// $ErrorAlerter->sendAlerts();
			$response = $Tag->createAlert("System Error", "Ouch we are sorry something went wrong, we think your internet connection may be slow", 'danger', true);
			unset($_SESSION['spoofing']);
		}
		unset($_SESSION['dataError']);
	}

	$products = $product->getAProduct(['id'=>$pid]);
	$orders = $order->getUserOrders(['product_id'=>$pid])[0];
	
	//Response after data processing
	if(isset($_SESSION['response'])){
		$response = $Tag->createAlert("", $_SESSION['response'], 'success', true);
		unset($_SESSION['response']);
	}
	$uniqueNo = new UniqueNo();
	$transactionNo = $uniqueNo->fromDb($dataModel, 9, $ymd=true, $prefix="T");
	$dataModel->setTable(PAYMENT);
	$dataModel->insertData(['transaction_no'=>$transactionNo, 'amount'=>$orders['total_cost'], 'buyer'=>$userId, 'order_id'=>$orders['id'], 'discount'=>$orders['discount']]);
	if (!$dataModel->getLastInsertID()) {
		echo $_SESSION['error'] = "Could not create a payment";
		// header("Location: ".URL."admin/error/");
		exit();
	}
	$_SESSION['customer details'] = ['order id'=>$orders['id'], 'product id'=>$products['name'], 'total cost'=>$orders['total_cost'], 'amount'=>$orders['amount']];
	//Generate paystack payment button
	$PayStack = new Paystack(PRIVATEKEYPS, PUBLICKEYPS, 'reference');
	$url = "processor.php";
	$payAmt = $orders['total_cost'] * 100;
	$pymtButton = $PayStack->payButton($transactionNo, $payAmt, $userEmail, $url);