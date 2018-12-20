<?php
/**
 * 
 */
class Products
{
	public $_users;
	function __construct(DataModel $users)
	{
		$this->_users = $users;
	}
	public function createCategory()
	{
		var_dump($this->_users);
	}
	public function createSubCategory($value='')
	{
		
	}
	public function getCategories()
	{
		
	}
	public function getSubCategories($value='')
	{
		
	}
	public function createProducts($value='')
	{
		
	}
	public function getProducts($value='')
	{
		# code...
	}
}