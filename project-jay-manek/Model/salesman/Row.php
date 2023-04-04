<?php
class Model_Salesman_Row extends Model_Core_Table_Row
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
	const GENDER_DEFAULT = 2;

	function __construct()
	{
		parent::__construct();
		$this->setTableClass('Model_Salesman');
	}

	public function getStatusOptions()
	{
		return [
			self::STATUS_ACTIVE => self::STATUS_ACTIVE_LBL,
			self::STATUS_INACTIVE => self::STATUS_INACTIVE_LBL
		];
	}

	public function getGenderOptions()
	{
		return [
			self::GENDER_MALE => self::GENDER_MALE_LBL,
			self::GENDER_FEMALE => self::GENDER_FEMALE_LBL
		];
	}

	public function getStatusText()
	{
		$statues = $this->getStatusOptions();
		if (array_key_exists($this->status, $statues)) {
			return $statues[$this->status];
		}

		return $statues[self::STATUS_DEFAULT];
	}

	public function getGenderText()
	{
		$genders = $this->getGenderOptions();
		if (array_key_exists($this->gender, $genders)) {
			return $genders[$this->gender];
		}

		return $genders[self::GENDER_DEFAULT];
	}

	public function getStatus()
	{
		if ($this->status) {
			return $this->status;
		}

		return self::STATUS_DEFAULT;
	}

	public function getGender()
	{
		if ($this->gender) {
			return $this->gender;
		}

		return self::GENDER_DEFAULT;
	}
}
?>