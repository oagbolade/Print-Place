<?php 
  $connector = new Connector("error.php");
  $connector->host = 'localhost';
  $connector->username = 'root';
  $connector->password = '';
  $connector->database = 'print_shop';
  $db_conn = $connector->doConnect();