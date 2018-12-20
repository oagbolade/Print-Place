<?php
/**
 * Author: Oyekunle Opeyemi
   Date: July, 2018
 */
class Users
{
	public $_dataModel;
	function __construct(DataModel $dataModel)
	{
		$this->_dataModel = $dataModel;
	}
	public function loginUser($email, $password, $directory="")
	{
		$userLogIn = false;
		$whereData = ['email' => $email, 'password'=>$password, 'status' => 'active'];
		$column = ['id', 'email'];
		if (count($whereData) > 3) {
			return false;
		}
		if ($this->_dataModel->selectData($column, $whereData)) {
			if ($record = $this->_dataModel->fetchRecords()) {
				$userLogIn = ['id'=>$record['id'], 'email'=>$record['email']];
				$_SESSION['print-users'] = $userLogIn;
				$userLogIn = true;
			}
		}	
		return $userLogIn;
	}
	public function firstTimeLogger($email, $password, $newToken, $directory="")
	{
		$userLogIn = false;
		$this->_dataModel->setTable(LOGIN);
		$updateData = ['status'=>'active', 'activation_token'=>$newToken];
		$whereData = ['email'=>$email, 'password'=>$password, 'status'=>'inactive'];
		if (!$this->_dataModel->updateData($updateData, $whereData)) {
			return false;
		}
		$whereData = ['email' => $email, 'password'=>$password, 'status' => 'active'];
		$column = ['id', 'email'];
		if (count($whereData) > 3) {
			return false;
		}
		if ($this->_dataModel->selectData($column, $whereData)) {
			if ($record = $this->_dataModel->fetchRecords()) {
				$userLogIn = ['id'=>$record['id'], 'email'=>$record['email']];
				$_SESSION['print-new-users'] = $userLogIn;
				$userLogIn = true;
			}
		}
		return $userLogIn;
	}
	// column must be a string
	public function getIdEmailUsername($column, $whereData)
	{
		$this->_dataModel->setTable(LOGIN);
		$acceptedColumns = ['email', 'id', 'username', 'phone', 'login_id'];
		if (is_string($column) && in_array($column, $acceptedColumns)) {
			$this->_dataModel->selectData([$column], $whereData);
			return $columnExist = $this->_dataModel->fetchRecords()[$column];
		}
		return false;
	}
	public function isEmailPhoneUsernameUsed($column, $whereData)
	{
		if ($column !== 'id') {
			if ($columnExist = $this->getIdEmailUsername($column, $whereData)) {
				return $columnExist;
			}
		}
		return FALSE;
	}
	
	public function signUpUser($insertData, $insertTmpProfile, $directory="")
	{
		$date = date("Y-m-d");
		$this->_dataModel->setTable(LOGIN);
		$updatedInsertedData = array_merge_recursive($insertData, ['authority'=>'user', 'status'=>'inactive', 'date'=>$date]);
		if ($this->_dataModel->insertData($updatedInsertedData)) {
			$id = $this->_dataModel->getLastInsertID();
			$updatedTmpProfile = array_merge_recursive($insertTmpProfile, ['login_id'=>$id]);
			$this->_dataModel->setTable(PROFILE);
			if ($this->_dataModel->insertData($updatedTmpProfile)) {
				return $this->_dataModel->getLastInsertID();
			}		
		}
		return false;
	}
	
	public function signUpAdmin($insertData, $insertTmpProfile, $directory="")
	{
		$date = date("Y-m-d");
		$this->_dataModel->setTable(LOGIN);
		$updatedInsertedData = array_merge_recursive($insertData, ['authority'=>'admin', 'status'=>'inactive', 'date'=>$date]);
		if ($this->_dataModel->insertData($updatedInsertedData)) {
			$id = $this->_dataModel->getLastInsertID();
			$updatedTmpProfile = array_merge_recursive($insertTmpProfile, ['user_id'=>$id,'date'=>$date]);
			$this->_dataModel->setTable(PROFILE);
			if ($this->_dataModel->insertData($updatedTmpProfile)) {
				return $this->_dataModel->getLastInsertID();
			}		
		}
		return false;
	}

	public function getUserDetails($whereData)
	{
		$data = ['id', 'email', 'authority', 'status', 'password_token', 'date'];
		$this->_dataModel->setTable(LOGIN);
		$this->_dataModel->selectData($data, $whereData);
		if ($records = $this->_dataModel->fetchRecords()) {
			$this->_dataModel->setTable(PROFILE);
			$data = ['login_id', 'fname', 'lname', 'oname', 'phone', 'gender', 'picture', 'user_type'];
			$this->_dataModel->selectData($data, ['login_id'=>$records['id']]);
			if ($profile = $this->_dataModel->fetchRecords()) {
				$details = array_merge_recursive($records, $profile);
				return $details;
			}
		}
		return false;
	}
	public function getLoginDetails($whereData)
	{
		$data = ['id', 'email', 'authority', 'status', 'date'];
		$this->_dataModel->setTable(LOGIN);
		$this->_dataModel->selectData($data, $whereData);
		if ($records = $this->_dataModel->fetchRecords()) return $records;
		return false;
	}
	/*
	@param associative array $whereData indicates which row should be checked for validation
	@return boolean true if the logger is an user
	*/
	public function userAuthority($whereData, $file='', $line='')
	{
		$authority = $this->getLoginDetails($whereData)['authority'];
		if ($authority == 'user') return true;
		$_SESSION['error'] = "Unauthorized user<br>$file<br>$line";
		header("Location: ".URL.'admin/error/');
	}

	/*
	@param associative array $whereData indicates which row should be checked for validation
	@return boolean true if the logger is an admin
	*/
	public function userAdminAuthority($whereData, $file='', $line='')
	{
		$authority = $this->getLoginDetails($whereData)['authority'];
		$user = $this->getLoginDetails($whereData)['authority'];
		if ($authority == 'admin' || $user == 'user') return true;
		return false;
	}
	/*
	@param associative array $whereData indicates which row should be checked for validation
	@return boolean true if the logger is an admin
	*/
	public function adminAuthority($whereData, $file='', $line='')
	{
		$authority = $this->getLoginDetails($whereData)['authority'];
		if ($authority == 'admin') return true;
		$_SESSION['error'] = "Unauthorized user<br>$file<br>$line";
		header("Location: ".URL.'admin/error/');
	}
	/*
		@param $newStatus is a string
		@param $whereData is array
	*/
	public function chageStatusTo($newStatus, $whereData)
	{
		$oldStatus = $this->getUserDetails($whereData)['status'];
		$this->_dataModel->setTable(LOGIN);
		if ($newStatus != 'active') {
			$this->_dataModel->updateData(['status'=>'inactive'], $whereData);
		}else{
			$this->_dataModel->updateData(['status'=>'active'], $whereData);
		}
		if ($this->_dataModel->getNoAffectedRows()) {
			return true;
		}
		return false;	
	}
	public function encriptPassword($password)
	{
		if ($password) {
			return md5($password.SALT.SITENAME);
		}

	}
	public function repeatPassword($password, $repeatPassword)
	{
		if ($password !== $repeatPassword) return false;
		return true;
	}

	public function logoutUser()
	{
  		$_SESSION = array();
  		session_destroy();
		header("Location: ". URL."admin/");
		exit();
	}
}