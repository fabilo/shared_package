<?php 
interface Project_Factory_Interface {
	public function getById($id);
	public function get();
	public function getByTeamOrDepartment($team_id, $department_id);
}