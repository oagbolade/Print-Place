<?php
	//Get required files
	require_once '../db-config.php';
	
	//List of unpaid member older than 12 months
	$DbHandle = new DBHandler($PDO, "login", __FILE__);
	$User = new Users($DbHandle);
	$DbHandle->setTable('score');
	$sql = "SELECT member_no FROM score WHERE paid = 'no' AND date < DATE_SUB(NOW(), INTERVAL 1 YEAR);";
	$membersNo = [];
	if($membersNoCollection = $DbHandle->dataList(__LINE__, $sql)){
		foreach ($membersNoCollection as $aMember) {
			$membersNo[] = $aMember['member_no'];
		} 
	}
	$membersNo = array_unique($membersNo);
	
	//Delete these members
	$DbHandle->setTable('member');
	foreach ($membersNo as $aMemberNo) {
		$memberDetails = $User->userDetails($User->getEmailFrmNo($aMemberNo, 'member'));
		
		//form mail
		$message = Functions::emailHead(URL);
		$message .= 
			"Good Day Sir/Madam<br/>
			<p style='margin-bottom:8px;'>
				Due to inactive  after registration to become a member of Nigeria Insitute of Management.
				Your record has been removed from our system. If you still wish to become a member of the 
				Insitute you can re apply again on <a href='".URL."' style='text-decoration:underline; color:#fff;'>Membership Application Portal.</a>
			</p>";
		$message .= Functions::footerHead(URL);
		
		//Send mail
		if(!DEVELOPMENT){
	  	$subject = "Removal from NIM Membership Application Portal";
	  	$headers  = 'MIME-Version: 1.0' . "\r\n";
	  	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	  	$headers .= "From: ". SITENAME ." <noreply@". URLEMAIL .">" . "\r\n";
			mail($memberDetails['email'], $subject, $message, $headers);
		}
		
		//send sms
		$sms = "Due to inactive your record has been removed from NIM Membership Application Portal";
		//Functions::smsSender($sms, $memberDetails['phone']);
		
		//Delete Records
		$DbHandle->setTable('score');
		$DbHandle->deleteData(__LINE__, ['member_no'=>$aMemberNo]);
		$DbHandle->setTable('member');
		$DbHandle->deleteData(__LINE__, ['member_no'=>$aMemberNo]);
	}	