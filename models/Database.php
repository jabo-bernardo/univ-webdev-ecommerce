<?php
class Database {
	private $conn = null;

	public function __construct($server_name, $database_name, $username, $password) {
		try {
			$this->conn = new PDO("mysql:host=$server_name;dbname=$database_name", $username, $password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
			throw new Exception("Error connecting to database: " . $e->getMessage());
		}
	}

    public function execute_query($query, $params = array()) {
        $query =$this->conn->prepare($query);
        $query->execute($params);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

	public function get_connection() {
		return $this->conn;
	}
}
?>