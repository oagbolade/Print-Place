<?php
/**
 * 
 */
class Payment
{
	protected $_uniqueNo;
	protected $_orders;
	protected $_dataModel;
	function __construct(UniqueNo $uniqueNo, Orders $orders)
	{
		$this->_uniqueNo = $uniqueNo;
		$this->_orders = $orders;
	}
	
	public function customerPay($paymentData, $transactionNo)
	{
		$_dataModel = $this->_orders->_products->_users->_dataModel;
		if ($this->doesOrderExist(['id'=>$paymentData['order_id']])) {
			$paymentData = array_merge_recursive($paymentData, ['payment_date'=>date('Y-m-d h:i:s'), 'transaction_no'=>$transactionNo]);
			if ($this->_orders->setToPaid(['id'=>$paymentData['order_id']])) {
				$_dataModel->setTable(PAYMENT);
				$_dataModel->insertData($paymentData);
				if ($_dataModel->getLastInsertID()) return true;
			}
			
		}
		return false;
	}
	
	public function doesOrderExist($whereData)
	{
		$_dataModel = $this->_orders->_products->_users->_dataModel;
		$_dataModel->setTable(ORDERS);
		$_dataModel->selectData(['id'], $whereData);
		if ($_dataModel->fetchRecords()) return true;
		return false;
	}
}