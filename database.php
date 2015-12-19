<?php
class Database {
    public function getConnection() {
		$servername = 'fabigler.mysql.db.internal';
		$username = 'fabigler_fabian';
		$password = 'yeUt39N2';
		$dbname = 'fabigler_maribelle';
		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 

        return $conn;
    }
}
?>