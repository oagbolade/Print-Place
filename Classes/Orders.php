<?php
/**
 * 
 */
class Orders
{
	public $_products;
	protected $_dataModel;
	function __construct(Products $products)
	{
		$this->_products = $products;
	}
	/*
		@param $data is an array of data to be inserted into orders table
	*/
	public function orderProductWithoutSpec($orderData)
	{
		$_dataModel = $this->_products->_users->_dataModel;
		$_products = $this->_products;
		if ($_products->getAProduct(['id'=>$orderData['product_id']])) { //checks if product exists
			$orderData = array_merge_recursive($orderData, ['order_date'=>date('Y-m-d h:i:s')]);
			$_dataModel->setTable(ORDERS);
			$_dataModel->insertData($orderData);
			if ($orderId = $_dataModel->getLastInsertID()) return $orderId;
		}
		return false;
	}
	public function orderProductWithSpec($designData, $orderData, $file, $line)
	{
		$_dataModel = $this->_products->_users->_dataModel;
		$_products = $this->_products;
		if ($orderId = $this->orderProductWithoutSpec($orderData)) { //checks if product exists
			if ($orderId = $_dataModel->getLastInsertID()) {
				$_dataModel->setTable(DESIGN);
				$designData = array_merge_recursive($designData, ['orders_id'=>$orderId, 'buyer'=>$orderData['buyer']]);
				$_dataModel->insertData($designData);
				if ($designId = $_dataModel->getLastInsertID()) return $designId;
			}
		}
		$_SESSION['error'] = "There was an error on line $line in file $file";
		header("Location: ".URL."admin/error/");
		return false;	
	}
	public function getUserOrders($whereData)
	{
		$_dataModel = $this->_products->_users->_dataModel;
		$_products = $this->_products;
		$_dataModel->setTable(ORDERS);
		$_dataModel->selectData(['id', 'buyer', 'product_id', 'quantity', 'total_cost', 'amount', 'status', 'order_date', 'discount'], $whereData, "ORDER BY order_date DESC");
		$orderRecords = [];
		if($records = $_dataModel->fetchRecords('all')) {
			$_dataModel->setTable(DESIGN);
			foreach ($records as $key => $record) {
				$_dataModel->selectData(['content', 'image'], ['buyer'=>$record['buyer'], 'orders_id'=>$record['id']]);
				if ($designs = $_dataModel->fetchRecords('all')){
					$orderRecords[$key] = array_merge_recursive($record, $designs);
				}else{
					$orderRecords[$key] = array_merge_recursive($record, [NULL]);
				}
			}
			return $orderRecords;
		}
		return false;	
	}
	public function getAllOrders()
	{
		$_dataModel = $this->_products->_users->_dataModel;
		$_products = $this->_products;
		$_dataModel->setTable(ORDERS);
		$_dataModel->selectData(['id', 'buyer', 'product_id', 'discount', 'order_date', 'quantity', 'amount', 'status'], 1, "ORDER BY order_date DESC");
		$orderRecords = [];
		if($records = $_dataModel->fetchRecords('all')) {
			$_dataModel->setTable(DESIGN);
			foreach ($records as $key => $record) {
				$_dataModel->selectData(['content', 'image'], 1);
				if ($designs = $_dataModel->fetchRecords('all')){
					$orderRecords[] = array_merge_recursive($record, $designs);
				}else{
					$orderRecords[] = $record;
				}
			}
			return $orderRecords;
		}
		return false;	
	}
	public function setToPaid($whereData)
	{
		$_dataModel = $this->_products->_users->_dataModel;
		$_products = $this->_products;
		$_dataModel->setTable(ORDERS);
		$_dataModel->updateData(['status'=>'paid'], $whereData);
		if ($_dataModel->getNoAffectedRows()) return true;
		return false;
	}
	public function setToCanceled($whereData)
	{
		$_dataModel = $this->_products->_users->_dataModel;
		$_products = $this->_products;
		$_dataModel->setTable(ORDERS);
		$_dataModel->updateData(['status'=>'cancelled'], $whereData);
		if ($_dataModel->getNoAffectedRows()) return true;
		return false;
	}
	public function sortOrders($whereData)
	{
		$_dataModel = $this->_products->_users->_dataModel;
		$_products = $this->_products;
		$_dataModel->setTable(ORDERS);
		$_dataModel->selectData(['id', 'buyer', 'product_id', 'discount', 'order_date', 'quantity', 'amount', 'status'], $whereData);
		$orderRecords = [];
		if($records = $_dataModel->fetchRecords('all')) {
			$_dataModel->setTable(DESIGN);
			foreach ($records as $key => $record) {
				$_dataModel->selectData(['content', 'image'], 1);
				if ($designs = $_dataModel->fetchRecords('all')){
					$orderRecords[] = array_merge_recursive($record, $designs);
				}else{
					$orderRecords[] = $record;
				}
			}
			return $orderRecords;
		}
		return false;
	}
}