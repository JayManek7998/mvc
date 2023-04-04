<?php
class Model_Customer extends Model_Core_Table
{
	function __construct()
	{
		parent::__construct();
		$this->setTableName('customer')->setPrimaryKey('customer_id');
	}
}
?>