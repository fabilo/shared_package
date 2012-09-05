<?php
class Department_Factory extends Base_PDO_Factory { 
	public static $_table_name = 'departments';
	public static $_fetch_class = 'Department';
	
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
}