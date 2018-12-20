<?php
/**
 * 
 */
class Cart
{	
	protected $_product;
	protected $_dataModel;
	/*
	@param $product is an instantiated Product class
	*/
	function __construct(Products $product)
	{
		$this->_product = $product;
	}
	
	
	public function addToCart($cartData)
	{
		$_dataModel = $this->_product->_users->_dataModel;
		if(!is_array($cartData)) return false;
		if(!$this->_product->getAProduct(['id'=>$cartData['product_id']])) return false;
		if($this->isUserProductInCart(['product_id'=>$cartData['product_id'], 'buyer'=>$cartData['buyer']])) return false;
		$_dataModel->setTable(CART);
		$cartData = array_merge_recursive($cartData, ['date'=>date('Y-m-d h:i:s')]);
		$_dataModel->insertData($cartData);
		if ($_dataModel->getLastInsertID()) return true;
		return false;
	}
	public function getCartProducts($whereData)
	{
		$_dataModel = $this->_product->_users->_dataModel;
		if(!is_array($whereData)) return false;
		$_dataModel->setTable(CART);
		$_dataModel->selectData(['buyer', 'product_id', 'quantity', 'date'], $whereData);
		if ($records = $_dataModel->fetchRecords('all')) return $records;
		return false;	
	}
	public function cartNumbers($whereData)
	{
		$_dataModel = $this->_product->_users->_dataModel;
		if(!is_array($whereData)) return false;
		$_dataModel->setTable(CART);
		$_dataModel->selectData(['id'], $whereData);
		if ($records = $_dataModel->fetchRecords('all')){
			$kanter = 0;
			foreach ($records as $record) {
			  ++$kanter;
			}
			return $kanter;
		}
		return false;
	}
	public function isUserProductInCart($whereData)
	{
		$_dataModel = $this->_product->_users->_dataModel;
		if(!is_array($whereData)) return false;
		if(!$whereData['buyer']) return false;
		$_dataModel->setTable(CART);
		$_dataModel->selectData(['id'], $whereData);
		if ($cartId = $_dataModel->fetchRecords()) return $cartId;
		return false;
	}
	public function isProductInCart($whereData)
	{
		$_dataModel = $this->_product->_users->_dataModel;
		if(!is_array($whereData)) return false;
		$_dataModel->setTable(CART);
		$_dataModel->selectData(['id'], $whereData);
		if ($cartId = $_dataModel->fetchRecords()) return $cartId;
		return false;
	}
	public function removeProductFromCart($whereData)
	{
		$_dataModel = $this->_product->_users->_dataModel;
		if(!is_array($whereData)) return false;
		if(!$this->isUserProductInCart($whereData)) return false;
		$_dataModel->setTable(CART);
		$_dataModel->deleteData($whereData);
		if ($_dataModel->getNoAffectedRows()) return true;
		return false;
	}
}