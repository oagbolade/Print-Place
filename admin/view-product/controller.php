<?php
	//Get required files
	require_once '../../config.php';
	require_once '../../db-config.php';
	
	//Initialization
	$response = "";
	$pageName = "View Products";
	$dataModel = new DataModel($db_conn, 'error');
	$users = new Users($dataModel);
	$whereAuthenticate = ['id'=>$userId];
	$userDetails = $users->getUserDetails($whereAuthenticate);
	$users->adminAuthority($whereAuthenticate, __FILE__, __LINE__);
    $product = new Products($users, ['id'=>$userId]);
	$_SESSION['token'] = md5(TOKEN);
	
	$Tag = new Tag(URL.'admin/');
	$head = $Tag->createHead("Print Shop | View-Product ", "nav-md home-page", ['css' => ['css/nprogress.css']]);
	
	$mastHead = $Tag->createMastHead($dataModel, $userDetails['email']);
	$menu = $Tag->createSideBar($dataModel, $userDetails['email'], ['parent'=>'Product', 'child'=>$pageName]);
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