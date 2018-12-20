<?php
  //Get required files
  require_once "../config.php";
  require_once "../db-config.php";
  
  $_SESSION['token'] = md5(TOKEN);
  

  if(isset($_SESSION['logger-type'])) unset($_SESSION['logger-type']);
  if(isset($_GET['token'])){
    $dataModel = new DataModel($db_conn, 'error');
    $dataModel->setTable(LOGIN);
    $dataModel->selectData(['activation_token'], ['activation_token'=>stripcslashes($_GET['token'])]);
    $allTokens = $dataModel->fetchRecords()['activation_token'];
    if ($_GET['token'] == $allTokens) {
      $_SESSION['logger-type'] = 'New Logger';
    }else{
      header("Location: .");
      exit();
    }
  }

  $Tag = new Tag();
  
  $head = $Tag->createHead(" Print Shop | Login", "login al-login", ['css' => ['vendors/animate.css/animate.min.css']]);
  $footer = $Tag->createFooter();
  
  //Responses initialization
  $registerMsg = "";
  $loginMsg = "";
  $passwordMsg = "";
  $pwdResetResponse = "";
  $response = "";
 
  //Error in data sent for processing
  if(isset($_SESSION['dataError'])){
    if(!isset($_SESSION['spoofing'])){
      $content = "<ul>";
      foreach ($_SESSION['dataError']['message'] as $aMessage) {
        $content .= "<li class='text-left'>$aMessage</li>"; 
      }
      $content .= "</ul>";
      switch ($_SESSION['dataError']['form']) {
        case 'register':
          $registerMsg = $Tag->createAlert("", $content, 'danger', false);
        break;
        case 'login':
          $loginMsg = $Tag->createAlert("", $content, 'danger', false);
        break;
        case 'password reset':
          $passwordMsg = $Tag->createAlert("", $content, 'danger', false);
          $footer = $Tag->createFooter(['js/pwd-reset-error-respond.js']);
        break;
      }
    }
    else {
      $functions = new Functions();
      $ErrorAlerter = new ErrorAlert($_SESSION['spoofing'], $functions);
      $ErrorAlerter->sendAlerts();
      $loginMsg = $Tag->createAlert("System Error", "Ouch we are sorry something went wrong, we think your internet connection may be slow", 'danger', true);
      unset($_SESSION['spoofing']);
    }
    unset($_SESSION['dataError']);
  }
  
  //Token processing for account activation
  if(isset($_GET['token']) && isset($_GET['email'])){
    $FormValidator = new Validator();
    $token = urldecode($FormValidator->getSanitizeData($_GET['token']));
    $email = urldecode($FormValidator->getSanitizeData($_GET['email']));
    $DbHandle = new DBHandler($PDO, "login", __FILE__);
    $User = new Users($DbHandle);
    $loginMsg = $Tag->createAlert("", "Account activation failed", 'danger', false);
    if($userDetails = $User->userDetails($email, "logger")){
      if($userDetails['token'] == $token){
        $functions = new Functions();
        $parameter = ['url' => URL, 'urlemail' => URLEMAIL, 'contactemail' => CONTACTEMAIL, 'development' => DEVELOPMENT];
        $User->setEmailParameter($parameter);
        $User->changeUserStatus($email, "active", $functions);
        $loginMsg = $Tag->createAlert("", "Account activation successful, you can login now", 'success', false);
      } 
    }
  }
  
  $pageHeader = "
    <div>
      <a href='". URL ."'>
        <!--<img class='al-login-logo' src='". URL ."images/logo.png'/>-->  
      </a>
      <h1>Print Shop</h1>
    </div>
    <br />
  ";

  $pageFooter = "
    <div>
      <p>&copy; ". date("Y") ." All Rights Reserved. Print Shop <a style='text-decoration:underline;' href=''>Privacy and Terms</a></p>
    </div>
  ";