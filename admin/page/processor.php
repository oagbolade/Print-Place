<?php
	//Get required files
	require_once '../db-config.php'; 
	
	//Turn off magic quotes
	Functions::magicQuotesOff();
		
	//Perform page action
	$DbHandle = new DBHandler($PDO, "login", __FILE__);
	
	//Generate response to be sent back
	$_SESSION['response'] = "the response";
	header("Location: .");
  exit();
