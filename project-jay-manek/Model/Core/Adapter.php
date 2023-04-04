<?php 
class Model_Core_Adapter
{
	public $config = [
		'host' => 'localhost',
		'username' => 'root',
		'password' => '',
		'dbname' => 'project-jay-manek'
	];

	public $connect = null;

	public function connect(){
		if ($this->connect != null) {
			return $this->connect;
		}

		$connect = mysqli_connect(
			$this->config['host'],
			$this->config['username'],
			$this->config['password'],
			$this->config['dbname']
		);
		$this->connect = $connect;
		return $connect;
	}

	public function fetchAll($query){
		$connect = $this->connect();
		$result = mysqli_query($connect, $query);
		if ($result->num_rows > 0) {
			return $result->fetch_all(MYSQLI_ASSOC);	
		}

		return false;
	}

	public function fetchRow($query){
		$connect = $this->connect();
		$result = mysqli_query($connect, $query);
		if ($result->num_rows > 0) {
			return $result->fetch_assoc();	
		}

		return false;
	}

	public function fetchPairs($query){
		$connect = $this->connect();
		$result = mysqli_query($connect, $query);
		if (!$result) {
			return false;
		}

		$data = $result->fetch_all();
		$column1 = array_column($data, '0');
		$column2 = array_column($data, '1');
		if (!$column2) {
			$column2 = array_fill(0, count($column1), null);
		}

		return array_combine($column1, $column2);
	}

	public function fetchOne($query){
		$connect = $this->connect();
		$result = mysqli_query($connect, $query);
		if (!$result) {
			return false;
		}
		
		$row = $result->fetch_array();
		return (array_key_exists(0, $row)) ? $row[0] : null;	
	}

	public function delete($query){
		$connect = $this->connect();
		$result = mysqli_query($connect, $query);
		if (!$result) {
			return false;
		}

		return true;
	}

	public function insert($query){
		$connect = $this->connect();
		$result = mysqli_query($connect, $query);
		if ($result) {
			return $connect->insert_id;
		}

		return false;
	}

	public function update($query){
		$connect = $this->connect();
		$result = mysqli_query($connect, $query);
		if (!$result) {
			return false;
		}
		
		return true;
	}
}
?>