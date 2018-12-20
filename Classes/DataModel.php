<?php
/**
 * Author: Oyekunle Opeyemi
   Date: July, 2018
 */
class DataModel
{
	protected $_db;
	protected $_errorPage;
	protected $_rows;
	protected $_lastInsertAble;
	protected $_table;
	private $_records;
	
	/**
	 * Setup a Successful Database Connection on creation of an object from this class
 	* @param string $connection an instant of PDO
 	* @param string $errorPage the error page's url
	*/
	public function __construct(PDO $db, $errorPage)
	 {
		$this->_errorPage=$errorPage;
	    $this->_db = $db;
	}
	
	/**
	 * The destructor is used to close the database connection
	 */
	public function __destruct()
	 {
  		$this->_db=NULL;
	}
	/**
	 * Retrieve the error page's url where app user are directed to when there is database related error
	 * @return	string
	*/
	public function getErrorPage()
	 {
		return $this->_errorPage;
	}
	/**
	* Retrieve the table DBWorker is using
	* @return string $table the active table
	*/
	public function getTable()
	 {
		return $this->_table;
	}
	/**
	 * Change the table DBWorker is using
	 * @param string $table the table name
	*/
    public function setTable($table)
     {
    	$this->_table = $table;
    }
	public function loopParameters($userDetails)
	{
		$columns = ""; $kanter = 0; $valueParams = ""; $whereClause = ""; $updateClause = "";
		foreach($userDetails as $key => $parameter){
			if ($kanter == 0) {
				$valueParams .= ":$key";
				$columns .= "$key";
				$updateClause .= "$key = '$parameter'";
				$whereClause .= "$key = :$key"; 
			}else{
			  $valueParams .= ", :$key";
			  $columns .= ", $key";
			  $updateClause .= ", $key = '$parameter'";
			  $whereClause .= " AND $key = :$key";
			}
			++$kanter;
		}		
		return ['columns'=>$columns, 'values parameters'=>$valueParams, 'where clause'=>$whereClause, 'update clause'=>$updateClause];
	}
    public function prepareAndExecute($sql, $userDetails)
    {
    	try{
			$stmt = $this->_db->prepare($sql);
			if (!$stmt) {
				header("Location: $this->_errorPage/");
	    		exit();
			}
			if (is_array($userDetails)) {
				$result = $stmt->execute($userDetails);
			}else{
				$result = $stmt->execute();
			}
			if(!$result){
				return false;
				// return $error=$stmt->errorInfo();
			}else{
				$this->saveRecords($stmt->fetchAll(PDO::FETCH_ASSOC));
				$this->_rows=$stmt->rowCount();
				$stmt->closeCursor();
				return $result;
			}
		}catch(PDOExtension $e){
			echo $e->getmessage();
		}
    }
	public function insertData($insertData)
	{
		$columns = $this->loopParameters($insertData)['columns'];
		$valueParams = $this->loopParameters($insertData)['values parameters'];
		$table = $this->getTable();
		$sql = "INSERT INTO $table($columns) VALUES($valueParams)";
		if ($this->prepareAndExecute($sql, $insertData)) {
			$this->_lastInsertAble=TRUE;
			return TRUE;
		}else{
			return FALSE;
		}
	}
	public function selectData($column, $whereData, $otherQuery = '')
	{	
		$columns = $column;
		if (is_array($column)) {
			$columns = implode(",", $column);
		}
		$whereClause = 1;
		if ($whereData != 1) {
			$whereClause = $this->loopParameters($whereData)['where clause'];
		}
		$table = $this->getTable();
		$sql = "SELECT $columns FROM $table WHERE $whereClause $otherQuery";
		$result = $this->prepareAndExecute($sql, $whereData);
		if ($result) {
			return $result;
		}
		return FALSE;
	}
	public function updateData($updateData, $whereData)
	 {
		$updateClause = $this->loopParameters($updateData)['update clause'];
		$whereClause = $this->loopParameters($whereData)['where clause'];
		$table = $this->getTable();
		$sql = "UPDATE $table SET $updateClause WHERE $whereClause";
		$result = $this->prepareAndExecute($sql, $whereData);
		if ($result) {
			return $result;
		}
		return FALSE;
	}
	public function deleteData($whereData)
	 {
		$whereClause = $this->loopParameters($whereData)['where clause'];
		$table = $this->getTable();
		$sql = "DELETE FROM $table WHERE $whereClause";
		$result = $this->prepareAndExecute($sql, $whereData);
		if ($result) {
			return $result;
		}
		return FALSE;
	}

	public function saveRecords($rows)
	 {
		$this->_records = $rows;
	}
	public function fetchRecords($all='')
	 {
	 	if ($this->_records) {
		 	if ($all === 'all') {
		 		return $this->_records;	
		 	}
			return $this->_records[0];
	 	}
	}
	/**
	 * Get the nos of record affected after a insert or update or delete query
	 * @return integer $_rows the numbers of affected rows or FALSE on failure 
	*/
	public function getNoAffectedRows(){
	  	if(isset($this->_rows)){
	  		return $this->_rows;	
	  	}
		else {
			return FALSE;
		}
	}	
	/**
	 * Get the ID of the last insert record/row
	 * @return integer the ID of the latest insert record or FALSE on failure 
	*/
	public function getLastInsertID(){
		if($this->_lastInsertAble){
	  		return $this->_db->lastInsertId(); 	
	  	}
		else{
			return FALSE;
		}
	}
}