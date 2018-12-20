<?php
	//Get required files
	require_once '../../config.php';
	require_once '../../db-config.php';

	//Turn off magic quotes
	Functions::magicQuotesOff();

    //Perform page action
    //$DbHandle = new DBHandler($PDO, "login", __FILE__);
    $dataModel = new DataModel($db_conn, 'error');
    $users = new Users($dataModel);
    $product = new Products($users, ['id'=>$userId]);
    $orders = new Orders($product);

    $status_options = ['new', 'paid', 'shipping', 'processing', 'cancelled', 'completed'];
    if (isset($_SESSION['token']) && $_POST['token']==$_SESSION['token'] && isset($_POST['sort'])){
        $status = $_POST['status_options'];
        checkStatusExists($status, $status_options);
        header("Location: ".URL."admin/view-orders/index.php?status=$status");
        exit();
    }

    function checkStatusExists($status, $status_options){
        if (!in_array($status, $status_options)){
            $_SESSION['response'] = "Please pick a sorting criteria";
            header("Location: ".URL."admin/view-orders/");
            exit();
        }
    }

    $order_id = $_GET['id'];
    if (trim($order_id) === ''){
        $_SESSION['response'] = "Cannot get order to be processed";
        header("Location: .");
        exit();
    }

    $whereData = [
        'id' => $order_id
    ];

    if (!$orders->setToCanceled($whereData)){
        $_SESSION['response'] = "Database Error! Failed to Cancel Order";
        header("Location: .");
        exit();
    }

	//Generate response to be sent back
    $_SESSION['response'] = "Order Canceled";
    header("Location: .");
    exit();
