<?php
class Model_Product_Media extends Model_Core_Table
{
	function __construct()
	{
		parent::__construct();
		$this->setTableName('product_media')->setPrimaryKey('media_id');
	}
	
}
?>