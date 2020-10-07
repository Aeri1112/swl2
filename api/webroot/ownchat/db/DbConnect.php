<?php 
	class DbConnect {
		private $host = 'mysql2e77.netcup.net';
		private $dbName = 'jtg';
		private $user = 'user1';
		private $pass = 'eKzi96%4';

		public function connect() {
			try {
				$conn = new PDO('mysql:host=' . $this->host . '; dbname=' . $this->dbName, $this->user, $this->pass);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $conn;
			} catch( PDOException $e) {
				echo 'Database Error: ' . $e->getMessage();
			}
		}
	}
 ?>