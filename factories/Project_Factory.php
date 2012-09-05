<?php
class Project_Factory extends Base_PDO_Factory  implements Project_Factory_Interface { 
	public static $_table_name = 'projects';
	public static $_fetch_class = 'Project';
	
	public function getById($id) {
		$smt = $this->_db->prepare('SELECT * FROM '.self::$_table_name .' WHERE id = ?');
		$smt->execute(array($id));
		return $smt->fetchObject(self::$_fetch_class);
	}
	
	/** Get projects
	 *
	 *	@return array - project objects
	 */
	public function get() {
		$smt = $this->_db->prepare(
			'SELECT * 
			FROM '.self::$_table_name.' 
			ORDER BY name ASC'
		);
		$smt->execute();
		$projects = $smt->fetchAll(PDO::FETCH_CLASS, self::$_fetch_class);

		return $projects;
	}
	
	/**
	 * 	Insert Project
	 *	@param Project $obj - project object containing values to insert into db
	 *	@return Int auto increment id of project just inserted
	 */
	public function insert(Project $obj) {
		$smt = $this->_db->prepare(
			"INSERT INTO ".self::$_table_name." (`name`, `department_id`, `team_id`, `description`, `created_ts`, `modified_ts`) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)"
		);
		
		$smt->execute(array(
			$obj->name, $obj->department_id, $obj->team_id, $obj->description
		));
		
		// return timelog id
		return (int) $this->_db->lastInsertId();
	}
	
	/**
	 *	Update Project in database
	 *	@param Project $obj - project object to update in the database
	 *	@return Boolean if update was successful
	 */
	public function update(Project $obj) {
		$smt = $this->_db->prepare(
			"UPDATE ".self::$_table_name." SET `name` = ?, `department_id` = ?, `team_id` = ?, `description` = ?, modified_ts = CURRENT_TIMESTAMP WHERE id = ?"
		);
		
		return (bool) $smt->execute(array(
			$obj->name, $obj->department_id, $obj->team_id, $obj->description, $obj->id
		));
	}
	
	
	/** Get projects by team_id or department_id
	 *
	 *	Projects for a user includes all projects for the user's team, and department
	 *	Include 
	 *	@return array - project objects
	 */
	public function getByTeamOrDepartment($team_id, $department_id) {
		$smt = $this->_db->prepare(
			'SELECT p.*, d.name AS department_name, t.name AS team_name
			FROM '.self::$_table_name.' p
			JOIN '.Department_Factory::$_table_name.' d
				ON (d.id = p.department_id)
			LEFT JOIN '.Team_Factory::$_table_name.' t
				ON (t.id = p.team_id)
			WHERE (p.team_id = ?)
			OR (p.team_id = 0 AND p.department_id = ?)
			ORDER BY p.name ASC'
		);
		$smt->execute(array($team_id, $department_id));
		$projects = $smt->fetchAll(PDO::FETCH_CLASS, self::$_fetch_class);
		
		return $projects;
	}

	/** Saturate projects with child objects
	 * 
	 *	@var $projects (array) - projects to saturate
	 */
	private function saturate($projects) {

		/** include child objects **/
		$department_factory = new Department_Factory($this->_db);
		$team_factory = new Team_Factory($this->_db);

		// department objects
		$departments = array();
		$tmp_ids = array(); // store ids to array to retrieve objects in single query
		// loop projects and get department ids
		foreach ($projects AS $project) {
			if (!in_array($project->getDepartmentId(), $tmp_ids)) {
				$tmp_ids[]= $project->getDepartmentId();
			}
		}
		// now get departments for ids
		die(print_r($tmp_ids));

		// team objects
		$teams = array();
	}
}