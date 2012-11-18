<?php
/**
 * 	This file contains a class for base login for the admin app controllers 
 */
class Base_CI_Admin_Controller extends Base_CI_Controller {
	protected $_timesheet, 
		$_admin_db, 
		$_timelog_factory,
		$_user_factory, 
		$_user;
	
	public function __construct() {
		parent::__construct();
		
		// setup admin db
		$this->load->database();
		$this->_admin_db = new PDO('mysql:host='.$this->db->hostname.';dbname='.$this->db->database, $this->db->username, $this->db->password);
		// define factory classes
		$this->_user_factory = new User_Factory($this->_admin_db);
	
		$this->_javascript_includes[]= 'global';
	}
}