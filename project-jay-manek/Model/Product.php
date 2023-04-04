<?php
class Model_Product extends Model_Core_Table
{
	function __construct()
	{
		parent::__construct();
		$this->setTableName('product')->setPrimaryKey('product_id');
	}
}
?>