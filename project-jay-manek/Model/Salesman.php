<?php


class Model_Salesman extends Model_Core_Table
{

function __construct()
	{
		parent::__construct();
		$this->setTableName('salesman')->setPrimaryKey('salesman_id');
	}
}

?>
