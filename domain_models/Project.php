<?php 
/** Project
 *	@Description: store a unit of time for work on a project/category
 */
class Project extends AbstractEntity {
	protected $name, 
		$department_id = 0, 
		$team_id = 0, 
		$archived = 0,
		$description, 
		$clarity_reference;

	// local objects
	protected $department, 
		$team;

	public function validate() {
		// validate name
		if (!preg_match('/^(\w|\s|\d){3,}$/', $this->name)) throw new Invalid_Input_Exception('Invalid name set for Project');
		// validate department_id 
		if (!preg_match('/^\d+$/', $this->department_id)) throw new Invalid_Input_Exception('Invalid department_id set for Project');
		// validate team_id 
		if (!preg_match('/^\d+$/', $this->team_id)) throw new Invalid_Input_Exception('Invalid team_id set for Project');
		return true;
	}
}