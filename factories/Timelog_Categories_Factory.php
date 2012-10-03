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
			'SELECT c.*, d.name AS department
			FROM '.self::$_table_name.' c
			JOIN '.Department_Factory::$_table_name.' d ON (c.department_id = d.id)
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
			'SELECT c.*, d.name AS department 
			FROM '.self::$_table_name.' c
			JOIN '.Department_Factory::$_table_name.' d ON (c.department_id = d.id)
			WHERE (c.department_id = ?)
			ORDER BY name ASC'
		);
		$smt->execute(array($department_id));
		return $smt->fetchAll(PDO::FETCH_CLASS, self::$_fetch_class);
	}
	
	/**
	 * 	Insert Timelog_Category
	 *	@param Timelog_Category $obj - project object containing values to insert into db
	 *	@return Int auto increment id of project just inserted
	 */
	public function insert(Timelog_Category $obj) {
		$smt = $this->_db->prepare(
			"INSERT INTO ".self::$_table_name." (`name`, `department_id`, `clarity_reference`, `created_ts`, `modified_ts`) VALUES (?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)"
		);

		$smt->execute(array(
			$obj->name, $obj->department_id, $obj->clarity_reference
		));

		// return timelog id
		return (int) $this->_db->lastInsertId();
	}

	/**
	 *	Update Timelog_Category in database
	 *	@param Timelog_Category $obj - project object to update in the database
	 *	@return Boolean if update was successful
	 */
	public function update(Timelog_Category $obj) {
		$smt = $this->_db->prepare(
			"UPDATE ".self::$_table_name." SET `name` = ?, `department_id` = ?, `clarity_reference` = ?, modified_ts = CURRENT_TIMESTAMP WHERE id = ?"
		);

		return (bool) $smt->execute(array(
			$obj->name, $obj->department_id, $obj->clarity_reference, $obj->id
		));
	}
}