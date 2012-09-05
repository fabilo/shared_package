<?php
class Team_Factory extends Base_PDO_Factory { 
	public static $_table_name = 'teams';
	public static $_fetch_class = 'Team';
	
	public function __construct($db) {
		parent::__construct($db);
	}
	
	public function getById($id) {
		$smt = $this->_db->prepare('SELECT * FROM '.self::$_table_name .' WHERE id = ?');
		$smt->execute(array($id));
		return $smt->fetchObject(self::$_fetch_class);
	}
	
	public function get() {
		$smt = $this->_db->prepare('SELECT * FROM '.self::$_table_name);
		$smt->execute(array());
		return $smt->fetchAll(PDO::FETCH_CLASS, self::$_fetch_class);
	}
	
	public function getByDepartment($department_id) {
		$smt = $this->_db->prepare('SELECT * FROM '.self::$_table_name.' WHERE department_id = ?');
		$smt->execute(array($department_id));
		return $smt->fetchAll(PDO::FETCH_CLASS, self::$_fetch_class);
	}
}