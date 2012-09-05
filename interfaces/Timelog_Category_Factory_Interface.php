<?php
interface Timelog_Category_Factory_Interface {
	public function getById($id);
	public function get();
	public function getByDepartment($department_id);
}