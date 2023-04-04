<?php
class Model_Category extends Model_Core_Table
{
	function __construct()
	{
		parent::__construct();
		$this->setTableName('category')->setPrimaryKey('category_id');
	}
}

?>