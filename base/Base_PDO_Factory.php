<?php
class Base_PDO_Factory {
	protected $_db; 
	public static $_table_name;

	public function __construct($db) {		
		// setup db object 
		if (!get_class($db) == 'PDO') { 
			throw new Exception('DB class not recognised for class Transaction_Factory. '.get_class($db));
		}
	
		// force db errors to throw exceptions
		$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		
		$this->_db = $db;
	
		return $this;
	}
	
}