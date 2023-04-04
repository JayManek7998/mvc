<?php

require_once 'Model/Core/Table.php';

class Model_Vendor extends Model_Core_Table
{
	const STATUS_ACTIVE = 1;
	const STATUS_ACTIVE_LBL = 'Active';
	const STATUS_INACTIVE = 2;
	const STATUS_INACTIVE_LBL = 'Inactive';
	const STATUS_DEFAULT = 2;
	const GENDER_MALE = 1;
	const GENDER_MALE_LBL = 'Male';
	const GENDER_FEMALE = 2;
	const GENDER_FEMALE_LBL = 'Female';

	function __construct()
	{
		parent::__construct();
		$this->setTableName('vendor')->setPrimaryKey('vendor_id');
	}

}

?>