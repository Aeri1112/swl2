<?php 
	
	class chatrooms
	{
		private $id;
		private $userId;
		private $msg;
		private $createdOn;
		protected $dbConn;

		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function setUserId($userId) { $this->userId = $userId; }
		function getUserId() { return $this->userId; }
		function setMsg($msg) { $this->msg = $msg; }
		function getMsg() { return $this->msg; }
		function setCreatedOn($createdOn) { $this->createdOn = $createdOn; }
		function getCreatedOn() { return $this->createdOn; }

		public function __construct() {
			require_once('DbConnect.php');
			$db = new DbConnect();
			$this->dbConn = $db->connect();
		}

		public function saveChatRoom() {
			$send_to = 0;
			$status = 1;
			$stmt = $this->dbConn->prepare('INSERT INTO jedi_user_messages VALUES(null, :send_from, :send_to, :msg, :status, :send)');
			$stmt->bindParam(':send_from', $this->userId);
			$stmt->bindParam(':send_to', $send_to);
			$stmt->bindParam(':status', $status);
			$stmt->bindParam(':msg', $this->msg);
			$stmt->bindParam(':send', $this->createdOn);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}

		public function getAllChatRooms() {
			$stmt = $this->dbConn->prepare("SELECT c.*, u.name FROM chatrooms c JOIN users u ON(c.userid = u.id)");
			$stmt->execute();
			$chatrooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $chatrooms;
		}

	}
 ?>