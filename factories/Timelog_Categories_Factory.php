<?php
class Timelog_Categories_Factory extends Base_PDO_Factory implements Timelog_Category_Factory_Interface {
	public static $_table_name = 'timelog_categories';
	public static $_fetch_class = 'Timelog_Category';
	protected $_user;
	
	public function __construct($db) {
		parent::__construct($db);
	}
	
	public function getById($id) {
		$smt = $this->_db->prepare('SELECT * FROM '.self::$_table_name .' WHERE id = ?');
		$smt->execute(array($id));
		return $smt->fetchObject(self::$_fetch_class);
	}
	
	/** Get timelog categories for user
	 *
	 *	Categories for a user includes all projects for the user's department
	 */
	public function get() {
		$smt = $this->_db->prepare(
			'SELECT * 
			FROM '.self::$_table_name.' 
			WHERE (department_id = ?)
			ORDER BY name ASC'
		);
		$smt->execute(array($this->_user->getDepartmentId()));
		return $smt->fetchAll(PDO::FETCH_CLASS, self::$_fetch_class);
	}
	
	/** Get categories by department_id
	 *
	 *	Timelog categories for a user includes all categories for the user's department
	 *	@return array - project objects
	 */
	public function getByDepartment($department_id) {
		$smt = $this->_db->prepare(
			'SELECT * 
			FROM '.self::$_table_name.' 
			WHERE (department_id = ?)
			ORDER BY name ASC'
		);
		$smt->execute(array($department_id));
		return $smt->fetchAll(PDO::FETCH_CLASS, self::$_fetch_class);
	}
}