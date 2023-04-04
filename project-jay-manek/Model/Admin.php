<?php
class Model_Admin extends Model_Core_Table
{
	function __construct()
	{
		parent::__construct();
		$this->setTableName('admin')->setPrimaryKey('admin_id');
	}
}
?>