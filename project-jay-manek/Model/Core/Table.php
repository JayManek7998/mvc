<?php
class Model_Core_Table
{
	protected $tableName = null;
	protected $primaryKey = null;
	protected $adapter = null;

	function __construct()
	{

	}
	protected function setAdapter($adapter)
	{
		$this->adapter = $adapter;
		return $this;
	}

	public function getAdapter()
	{
		if ($this->adapter) {
			return $this->adapter;
		}

		$adapter = Ccc::getModel('Core_Adapter');
		$this->setAdapter($adapter);
		return $adapter;
	}

	public function setTableName($tableName)
	{
		$this->tableName = $tableName;
		return $this;
	}

	public function getTableName()
	{
		return $this->tableName;
	}

	public function setPrimaryKey($primaryKey)
	{
		$this->primaryKey = $primaryKey;
		return $this;
	}

	public function getPrimaryKey()
	{
		return $this->primaryKey;
	}

	public function fetchAll($query)
	{
		return $this->getAdapter()->fetchAll($query);
	}

	public function fetchRow($query)
	{
		return $this->getAdapter()->fetchRow($query);
	}

	public function insert($data)
	{
		$keys = array_keys($data);
		$columns = "`".implode("`,`", $keys)."`";
		$values = "'".implode("','", $data)."'";
		$query = "INSERT INTO `{$this->getTableName()}`({$columns}) VALUES ({$values})";
		return $this->getAdapter()->insert($query);
	}

	public function update($data, $conditions)
	{
		$dataString = "";
		foreach($data as $key => $value){
    		$dataString .= "`".$key."` = '".$value."', ";
		}

		$dataString = rtrim($dataString, ", "); 
		if (!is_array($conditions)) {
			$query = "UPDATE `{$this->getTableName()}` SET {$dataString}";
			return $this->getAdapter()->update($query);
		}
		
		$keys = array_keys($conditions);
		$values = array_values($conditions);
		$condition = "";
		if (count($keys) != 1) {
			for ($i=0; $i < count($keys); $i++) {
				$condition .= "`".$keys[$i]."` = '".$values[$i]."' AND ";
			}

			$condition = rtrim($condition, " AND");
		}
		else {
			if (!is_array($values[0])) {
				for ($i=0; $i < count($keys); $i++) { 
					$condition = "`".$keys[$i]."` = '".$values[$i]."'";
				}

			}
			else {
				$valueString = implode(',', $values[0]);
				$condition = "`".$keys[0]."` IN (".$valueString.")";
			}

		}

		$query = "UPDATE `{$this->getTableName()}` SET {$dataString} WHERE {$condition}";
		return $this->getAdapter()->update($query);	
	}

	public function delete($conditions)
	{
		$keys = array_keys($conditions);
		$values = array_values($conditions);
		if (count($keys) != 1) {
			for ($i=0; $i < count($keys); $i++) {
				$condition .= "`".$keys[$i]."` = '".$values[$i]."' AND ";
			}

			$condition = rtrim($condition, " AND");
		}
		else {
			if (!is_array($values[0])) {
				for ($i=0; $i < count($keys); $i++) { 
					$condition = "`".$keys[$i]."` = '".$values[$i]."'";
				}

			}
			else {
				$valueString = implode(',', $values[0]);
				$condition = "`".$keys[0]."` IN (".$valueString.")";
			}

		}
		
		$query = "DELETE FROM `{$this->tableName}` WHERE {$condition}";
		return $this->getAdapter()->delete($query);
	}

}
?>