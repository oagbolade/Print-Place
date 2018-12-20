<?php
	//Get required file
	require_once 'config.php';
	
	//Set up connection to database
	$connection=new Connector(URL.ERRORPAGE);
  $connection->host = DB_SERVER;
  $connection->username = DB_USER;
  $connection->password = DB_PASSWORD;
  $connection->database = DB_NAME;
	
	$dbConnector=$connection->doConnect();
	
	//Get a PDO handle
	$PDO = new PDOHandler($dbConnector, URL.ERRORPAGE);