<?php
require_once 'Model/Core/Table.php';

class Model_Core_Table_Row
{
	protected $tableClass = 'Model_Core_Table';
	protected $data = [];
	protected $table = null;

	function __construct()
	{
		
	}
	public function setTable($table)
	{
		$this->table = $table;
		return $this;
	}

	public function getTable()
	{
		if ($this->table) {
			return $this->table;
		}

		$table = new $this->tableClass();
		$this->setTable($table);
		return $this->table;
	}

	public function getTableName()
	{
		return $this->getTable()->getTableName();
	}

	public function getPrimaryKey()
	{
		return $this->getTable()->getPrimaryKey();
	}

	public function setTableClass($tableClass)
	{
		$this->tableClass = $tableClass;
		return $this;
	}

	public function getTableClass()
	{
		return $this->tableClass;
	}

	public function setId($id)
	{
		$this->data[$this->getTable()->getPrimaryKey()] = (int) $id;
		return $this;
	}

	public function getId()
	{
		$primaryKey = $this->getTable()->getPrimaryKey();
		return (int) $this->$primaryKey;
	}

	public function __set($key, $value)
	{	
		$this->data[$key] = $value;
		return $this;
	}

	public function __get($key)
	{
		if (array_key_exists($key, $this->data)) {
			return $this->data[$key];
		}
		return null;
	}

	public function __unset($key)
	{
		if ($key == null) {
			$this->data = [];
		}
		if (!array_key_exists($key, $this->data)) {
			return null;
		}
		unset($this->data[$key]);
	}

	public function setData($data)
	{
		$this->data = array_merge($this->data, $data);
		return $this;
	}

	public function getData($key = null)
	{
		if ($key == null) {
			return $this->data;
		}
		if (array_key_exists($key, $this->data)) {
			return $this->data[$key];
		}
		return null;
	}

	public function addData($key, $value)
	{
		$this->data[$key] = $value;
		return $this;
	}

	public function removeData($key = null)
	{
		if ($key == null) {
			return $this->data = [];
		}
		if (!array_key_exists($key, $this->data)) {
			return null;
		}
		unset($this->data[$key]);
		return $this;
	}

	public function fetchAll($query)
	{
		$result = $this->getTable()->fetchAll($query);
		if (!$result) {
			return false;
		}
		$rows = [];
		foreach ($result as $row) {
			 $rows[] = (new $this)->setData($row)->setTable($this->getTable());
		}
		return $rows;
	}

	public function fetchRow($query)
	{
		$row = $this->getTable()->fetchRow($query);
		if (!$row) {
			return false;
		}
		$this->setData($row);
		return $this;
	}

	public function load($id, $column = null)
	{
		if (!$column) {
			$column = $this->getPrimaryKey();
		}

		$query = "SELECT * FROM `{$this->getTableName()}` WHERE `{$column}` = '{$id}'";
		$row = $this->getTable()->fetchRow($query);

		if ($row) {
			$this->setData($row);
		}
		return $this;
	}

	public function save()
	{
		if (array_key_exists($this->getPrimaryKey(), $this->getData())) {
			$condition = [$this->getPrimaryKey() => $this->getData($this->getPrimaryKey())];
			$this->removeData($this->getPrimaryKey());
			return $this->getTable()->setTableName($this->getTableName())->setPrimaryKey($this->getPrimaryKey())->update($this->getData(), $condition);
		}
		
		return $this->getTable()->setTableName($this->getTableName())->insert($this->getData());
	}

	public function delete()
	{
		$condition = [$this->getPrimaryKey() => $this->getData($this->getPrimaryKey())];
		if (!array_values($condition)) {
			return false;
		}

		$result = $this->getTable()->setTableName($this->getTableName())->delete($condition);
		if (!$result) {
			return false;
		}

		$this->removeData();
		return true;
	}
	
}
?>