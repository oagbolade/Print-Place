<?php
	//Get required files
	require_once '../../config.php'; 
	require_once '../../db-config.php'; 
//Check if the access to this script is coming from its index's page
if(isset($_GET['reference'])){
	
	//Turn off magic quotes
	Functions::magicQuotesOff();
	
	//Get validator to validate data sent to this script
	$FormValidator = new Validator();
	$reference = $FormValidator->getSanitizeData($_GET['reference']);

	$dataModel = new DataModel($db_conn, 'error');
	$users = new Users($dataModel);

	if (!$users->userAdminAuthority(['id'=>$userId], __FILE__, __LINE__)) {
		$_SESSION['error'] = "Unauthorized user in ".__FILE__."on line". __LINE__;
		header("Location: ".URL."admin/error/");
		exit();
	}

	//Check payment reference
	$PayStack = new Paystack(PRIVATEKEYPS, PUBLICKEYPS, 'reference');
	$checkPayment = $PayStack->checkReferenceNo($reference);
	if(!$checkPayment['error']){
		$status = $checkPayment['status'];
		
		//Get transaction details
		$dataModel->setTable(PAYMENT);
		$dataModel->selectData(['transaction_no', 'buyer', 'approval', 'order_id', 'status', 'payment_type', 'amount', 'payment_date', 'discount'], ['transaction_no'=>$reference]);
		$transactionDetails = $dataModel->fetchRecords();
		
		$adminEmails = [WEBMASTEREMAIL, CONTACTEMAIL];
		$emailPush = new EmailPush($adminEmails, [$userEmail]);

		$discount = "";
		if (isset($transactionDetails['discount'])) {
			$discount = number_format($transactionDetails['discount'], 2);
			$amount = $transactionDetails['amount'] - $transactionDetails['discount'];
			$amount = number_format($amount, 2);
		}
		
		//Check payment for perfect match
		if($transactionDetails['amount'] == ($status->amount/100) && $status->status == 'success'){
			$dataModel->updateData(['approval'=>'Paystack','status' => 'success', 'payment_date'=>date("Y-m-d h:i:s")], ['transaction_no' => $reference]);
			$dataModel->setTable(ORDERS);
			$dataModel->updateData(['status' => 'paid'], ['id' =>$_SESSION['customer details']['order id']]);
			$message .= "
				<p style='margin-bottom:20px;'>Good Day Sir/Madam</p>
				<p style='margin-bottom:8px;'>
					Your product fee payment is successful. Details of the transaction is below<br/>
					Transaction Reference: $reference<br/>
					Email:$userEmail <br/>
					Quantity: {$_SESSION['customer details']['quantity']}<br/>
					$amount
					$discount
					Total Amount Paid: N". $_SESSION['customer details']['total cost']."<br/>
					Payment Status: SUCCESSFUL<br/>
				</p>
				<p style='margin-bottom:8px;'>
					If there is any issue with this transaction you can reach us via ". CONTACTEMAIL."
				</p>";
			$emailPush->messageUsers("Payment Successful", $message);
			header("Location: ". URL . "admin/paid/" );
			exit();
		}
		else {
			
			$message .= "
				<p style='margin-bottom:20px;'>Good Day Sir/Madam</p>
				<p style='margin-bottom:8px;'>
					Your product fee payment is successful. Details of the transaction is below<br/>
					Transaction Reference: $reference<br/>
					Email:$userEmail <br/>
					Quantity: {$_SESSION['customer details']['quantity']}<br/>
					Amount: {$transactionDetails['amount']}<br/>
					Total Amount Paid: N". $_SESSION['customer details']['total cost']."<br/>
					Payment Status: FAILED<br/>
				</p>
				<p style='margin-bottom:8px;'>
					If there is any issue with this transaction you can reach us via ". CONTACTEMAIL."
				</p>";
			$emailPush->messageUsers("Payment Failed", $message);
			$_SESSION['dataError'] = $dataError = ['Payment Failed'];
			header("Location: .");
			exit();
		}
	}
	else {
		$message .= "
			<p style='margin-bottom:20px;'>Good Day Sir/Madam</p>
			<p style='margin-bottom:8px;'>
				Your product fee payment is successful. Details of the transaction is below<br/>
				Transaction Reference: $reference<br/>
				Email:$userEmail <br/>
				Quantity: {$_SESSION['customer details']['quantity']}<br/>
				Total Amount Paid: N". $_SESSION['customer details']['total cost']."<br/>
				Payment Status: FAILED<br/>
			</p>
			<p style='margin-bottom:8px;'>
				If there is any issue with this transaction you can reach us via ". CONTACTEMAIL."
			</p>";
		$emailPush->messageUsers("Payment Failed", $message);
		$_SESSION['dataError'] = $dataError = ['Payment Failed'];
	  	header("Location: .");
	  	exit();
	}

	$dataModel->setTable(ORDERS);
	$dataModel->updateData(['status' => 'failed'], ['id' =>$_SESSION['customer details']['order id']]);

	//Generate response to be sent back
	$_SESSION['response'] = $response;
	header("Location: .");
}else{
	$_SESSION['spoofing'] = "Spoofing";
	header("Location: ".URL."admin/error/");
}