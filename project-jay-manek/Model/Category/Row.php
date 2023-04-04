<?php
class Model_Category_Row extends Model_Core_Table_Row
{
	const STATUS_ACTIVE = 1;
	const STATUS_ACTIVE_LBL = 'Active';
	const STATUS_INACTIVE = 2;
	const STATUS_INACTIVE_LBL = 'Inactive';
	const STATUS_DEFAULT = 2;

	function __construct()
	{
		parent::__construct();
		$this->setTableClass('Model_Category');
	}

	public function getStatusOptions()
	{
		return [
			self::STATUS_ACTIVE => self::STATUS_ACTIVE_LBL,
			self::STATUS_INACTIVE => self::STATUS_INACTIVE_LBL
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

	public function getStatus()
	{
		if ($this->status) {
			return $this->status;
		}

		return self::STATUS_DEFAULT;
	}

	public function getParentCategories()
	{
		$query = "SELECT `category_id`, `path` FROM `{$this->getTable()->getTableName()}` ORDER BY `path` ASC";
		$categories = $this->getTable()->getAdapter()->fetchPairs($query);
		return $categories;
	}

	public function updatePath()
	{
		if (!$this->getId()) {
			return false;
		}

		$parent = Ccc::getModel('Category_Row')->load($this->parent_id);
		if (!$parent) {
			$this->path = $this->getId();
		}
		else {
			$this->path = $parent . '/' . $this->getId();
		}


	}
}

?>