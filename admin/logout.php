<?php
	require_once '../config.php';
	require_once '../db-config.php';
	$dataModel = new DataModel($db_conn, 'error');
	$users = new Users($dataModel);
	$users->logoutUser();