<?php

class User extends AbstractEntity implements User_Interface {
	protected $team_id,
		$department_id;
	
	public function __construct() {
	}

	/** Get ID for user 
	 *
	 *	@return int - id
	 */
	public function getId() {
		return $this->id;
	}
	
	/** Get ID for user's current team 
	 *
	 *	@return int - id
	 */
	public function getTeamId() {
		return $this->team_id;
	}
	
	/** Get ID for user's current department 
	 *
	 *	@return int - id
	 */
	public function getDepartmentId() {
		return $this->department_id;
	}
	
	/** Get all projects visible to user
	 *	
	 *	Get projects that belong to user's team/department
	 *	@return - array of Project objects 
	 */
	public function getVisibleProjects(Project_Factory_Interface $project_factory, $show_archived=0) {
		return $project_factory->getByTeamOrDepartment($this->getTeamId(), $this->getDepartmentId(), $show_archived);
	}
	
	/** Get all deparments visible to user
	 *	
	 *	Get projects that belong to user
	 *	@return - array of Department objects 
	 */
	public function getVisibleDepartments($department_factory) {
		return $department_factory->get();
	}
	
	/** Get all teams visible to user
	 *	
	 *	Get projects that belong to user's team/department
	 *	@return - array of Project objects 
	 */
	public function getVisibleTeams($team_factory) {
		return $team_factory->getByDepartment($this->getDepartmentId());
	}
	
	/** Get all timelog categories visible to user
	 *	
	 *	Get projects that belong to user's team/department
	 *	@return - array of Project objects 
	 */
	public function getVisibleTimelogCategories(Timelog_Category_Factory_Interface $timelog_category_factory) {
		return $timelog_category_factory->getByDepartment($this->getDepartmentId());
	}
}