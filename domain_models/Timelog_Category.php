<?php 
class Timelog_Category extends AbstractEntity {
	protected $name, 
		$department_id,
		$clarity_reference;
		
	public function validate() {
		// validate name
		if (!preg_match('/^(\w|\s|\d){3,}$/', $this->name)) throw new InvalidInputException('Invalid name set for Project');
		// validate department_id 
		if (!preg_match('/^\d+$/', $this->department_id)) throw new InvalidInputException('Invalid department_id set for Project');
		return true;
	}
}