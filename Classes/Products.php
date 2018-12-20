<?php
/**
 * 
 */
class Products
{
	public $_users;
	protected $_dataModel;
	protected $_authority;
	/*
	@param $users is an instantiated Users class
	@param associative array $authority of the user, to be passed liked this ['id'=>$userId]
	*/
	function __construct(Users $users, $authority)
	{
		$this->_users = $users;
		$this->_authority = $authority;
	}
	/*
	@param associative array $data of values to be added to the CATEGORY table
	@return integer $catID of the added CATEGORY
	*/
	public function createCategory($data)
	{
		if (!$this->_users->adminAuthority($this->_authority)) return false;
		if (is_array($data)) {
			$data = array_merge_recursive($data, ['created_date'=>date('Y-m-d h:i:s')]);
			$_dataModel = $this->_users->_dataModel;
			$_dataModel->setTable(CATEGORY);
			$_dataModel->insertData($data);
			if ($catID = $_dataModel->getLastInsertID()) return $catID;
		}
		return false;
	}
	/*
	@param associative array $data of values to be updated in the CATEGORY table
	@param associative array $whereData indicates which row is to be updated in the CATEGORY table
	@return boolean true if CATEGORY table is updated
	*/
	public function updateCategory($data, $whereData)
	{
		if (!$this->_users->adminAuthority($this->_authority)) return false;
		if (is_array($data) && is_array($whereData)) {
			$data = array_merge_recursive($data, ['changed_date'=>date('Y-m-d h:i:s')]);
			$_dataModel = $this->_users->_dataModel;
			$_dataModel->setTable(CATEGORY);
			$_dataModel->updateData($data, $whereData);
			if ($_dataModel->getNoAffectedRows()) return true;
		}
		return false;
	}

	    /*
		@param associative array $data of values to be updated in the PRODUCTS table
		@param associative array $whereData indicates which row is to be updated in the CATEGORY table
		@return boolean true if CATEGORY table is updated
		*/
	    public function updateProduct($data, $whereData)
	    {
	        if (!$this->_users->adminAuthority($this->_authority)) return false;
	        if (is_array($data) && is_array($whereData)) {
	            $data = array_merge_recursive($data, ['edited_date'=>date('Y-m-d h:i:s')]);
	            $_dataModel = $this->_users->_dataModel;
	            $_dataModel->setTable(PRODUCTS);
	            $_dataModel->updateData($data, $whereData);
	            if ($_dataModel->getNoAffectedRows()) return true;
	        }
	        return false;
	    }
	/*
	@param associative array $whereData indicates which row is to be deleted from the CATEGORY table
	@return boolean true if CATEGORY table is deleted
	*/
	public function deleteCategory($whereData)
	{
		if (!$this->_users->adminAuthority($this->_authority)) return false;
		if (is_array($whereData)) {
			$_dataModel = $this->_users->_dataModel;
			$_dataModel->setTable(CATEGORY);
			$_dataModel->deleteData($whereData);
			if ($_dataModel->getNoAffectedRows()) return true;
		}	
		return false;
	}
	/*
	@param associative array $data of values to be added to the SUBCATEGORY table
	@return integer $subCatID of the added SUBCATEGORY
	*/
	public function createSubCategory($data)
	{
		if (!$this->_users->adminAuthority($this->_authority)) return false;
		if (is_array($data)) {
			$data = array_merge_recursive($data, ['created_date'=>date('Y-m-d h:i:s')]);
			$_dataModel = $this->_users->_dataModel;
			$_dataModel->setTable(SUBCATEGORY);
			$_dataModel->insertData($data);
			if ($subCatID = $_dataModel->getLastInsertID()) return $subCatID;
		}
		return false;
	}
	/*
	@param associative array $data of values to be updated in the SUBCATEGORY table
	@param associative array $whereData indicates which row is to be updated in the SUBCATEGORY table
	@return boolean true if SUBCATEGORY table is updated
	*/
	public function updateSubCategory($data, $whereData)
	{
		if (!$this->_users->adminAuthority($this->_authority)) return false;
		if (is_array($data) && is_array($whereData)) {
			$data = array_merge_recursive($data, ['changed_date'=>date('Y-m-d h:i:s')]);
			$_dataModel = $this->_users->_dataModel;
			$_dataModel->setTable(SUBCATEGORY);
			$_dataModel->updateData($data, $whereData);
			if ($_dataModel->getNoAffectedRows()) return true;
		}
		return false;
	}
	/*
	@param associative array $whereData indicates which row is to be deleted from the SUBCATEGORY table
	@return boolean true if SUBCATEGORY table is deleted
	*/
	public function deleteSubCategory($whereData)
	{
		if (!$this->_users->adminAuthority($this->_authority)) return false;
		if (is_array($whereData)) {
			$_dataModel = $this->_users->_dataModel;
			$_dataModel->setTable(SUBCATEGORY);
			$_dataModel->deleteData($whereData);
			if ($_dataModel->getNoAffectedRows()) return true;
		}	
		return false;
	}
	/*
	@param associative array $category of values to be added to the CATEGORY table
	@param associative array $subCategory of values to be added to the SUBCATEGORY table
	@return boolean true if $subcategory is added
	*/
	public function createCatSubCat($category, $subCategory)
	{
		if (!$this->_users->adminAuthority($this->_authority)) return false;
		if ($catID = $this->createCategory($category)) {
			$subCategory = array_merge_recursive($subCategory, ['category_id'=>$catID]);
			if ($this->createSubCategory($subCategory)) return true;
		}
		return false;
	}
	/*
	@param multidimensional associative array $category of values to be updated in the CATEGORY table
	@param associative array $subCategory of values to be updated in the SUBCATEGORY table
	@return boolean true if SUBCATEGORY table is edited
	*/
	public function updateCatSubCat($category, $subCategory)
	{
		if (!$this->_users->adminAuthority($this->_authority)) return false;
		if ($this->updateCategory($category['data'], $category['where'])) {
			$subCategory = array_merge_recursive($subCategory, ['category_id'=>$category['where']['id']]);
			$whereData = array_merge_recursive($subCategory['where'], ['category_id'=>$category['where']['id']]);
			if ($this->updateSubCategory($subCategory['data'], $whereData)) return true;
		}
		return false;
	}
	public function getAllCategories()
	{
		$data = ['id', 'name', 'image', 'created_by', 'created_date', 'changed_by', 'changed_date'];
		$whereData = 1;
		$_dataModel = $this->_users->_dataModel;
		$_dataModel->setTable(CATEGORY);
		$_dataModel->selectData($data, $whereData, "ORDER BY created_date DESC");
		if ($records = $_dataModel->fetchRecords('all')) return $records;
		return false;
	}
	public function getACategory($whereData)
	{
		if (is_array($whereData)) {
			$data = ['id', 'name', 'image', 'created_by', 'created_date', 'changed_by', 'changed_date'];
			$_dataModel = $this->_users->_dataModel;
			$_dataModel->setTable(CATEGORY);
			$_dataModel->selectData($data, $whereData);
			if ($records = $_dataModel->fetchRecords()) return $records;
		}
		return false;
	}
	public function getAllSubCategories()
	{
		$data = ['id', 'category_id', 'name', 'image', 'created_by', 'created_date', 'changed_by', 'changed_date'];
		$whereData = 1;
		$_dataModel = $this->_users->_dataModel;
		$_dataModel->setTable(SUBCATEGORY);
		$_dataModel->selectData($data, $whereData);
		if ($records = $_dataModel->fetchRecords('all')) return $records;
		return false;
	}
	public function isCatAvailable($id)
	{
		$_dataModel = $this->_users->_dataModel;
		$_dataModel->setTable(CATEGORY);
		$_dataModel->selectData(['id'], ['id'=>$id]);
		if ($records = $_dataModel->fetchRecords()) return $records['id'];
		return false;
	}
	public function getASubCategory($whereData)
	{
		if (is_array($whereData)) {
			$data = ['id', 'category_id', 'name', 'image', 'created_by', 'created_date', 'changed_by', 'changed_date'];
			$_dataModel = $this->_users->_dataModel;
			$_dataModel->setTable(SUBCATEGORY);
			$_dataModel->selectData($data, $whereData);
			if ($records = $_dataModel->fetchRecords('all')) return $records;
		}
		return false;
	}
	public function createProducts($data)
	{
		if (!$this->_users->adminAuthority($this->_authority)) return false;
		if(!is_array($data)) return false;
		if ($this->isCatAvailable($data['category'])) {
			$data = array_merge_recursive($data, ['added_date'=>date('Y-m-d h:i:s')]);
			$_dataModel = $this->_users->_dataModel;
			$_dataModel->setTable(PRODUCTS);
			$_dataModel->insertData($data);
			if ($productID = $_dataModel->getLastInsertID()) return $productID;
		}else{
			return false;
		}
		return false;
	}
	
	public function getAllProducts()
	{
		$data = ['id', 'name', 'description', 'material', 'finishing', 'delivery', 'size', 'cost', 'discount', 'image', 'category', 'sub_category', 'designer', 'design_fee', 'added_by', 'added_date', 'edited_by', 'edited_date'];
		$whereData = 1;
		$_dataModel = $this->_users->_dataModel;
		$_dataModel->setTable(PRODUCTS);
		$_dataModel->selectData($data, $whereData, "ORDER BY added_date DESC");
		if ($records = $_dataModel->fetchRecords('all')) return $records;
		return false;	
	}
	public function getAProduct($whereData)
	{
		if (!is_array($whereData)) return false;
		$data = ['id', 'name', 'description', 'material', 'finishing', 'delivery', 'size', 'cost', 'discount', 'image', 'category', 'sub_category', 'designer', 'design_fee', 'added_by', 'added_date', 'edited_by', 'edited_date'];
		$_dataModel = $this->_users->_dataModel;
		$_dataModel->setTable(PRODUCTS);
		$_dataModel->selectData($data, $whereData);
		if ($records = $_dataModel->fetchRecords()) return $records;
		return false;
	}
	/*
    @param associative array $whereData indicates which row is to be deleted from the PRODUCTS table
    @return boolean true if PRODUCTS table is deleted
    */
    public function deleteProduct($whereData)
    {
        if (!$this->_users->adminAuthority($this->_authority)) return false;
        if (is_array($whereData)) {
            $_dataModel = $this->_users->_dataModel;
            $_dataModel->setTable(PRODUCTS);

            if (!$_dataModel->deleteData($whereData)) {
                $_SESSION['error'] = 'Cannot delete a product that has been added to a cart';
                header("Location: ".URL."admin/error/");
                exit();
            }
            if ($_dataModel->getNoAffectedRows()) return true;
        }
        return false;
    }
}