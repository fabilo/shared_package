<?php
/**
 * 	This file contains a class to handle logic for working with the current_timestamp object 
 *	displayed in the right hand side of the template. 
 */
class Current_Timelog_Form_Controller extends Base_CI_Auth_Controller {
	protected $_timesheet, 
		$_admin_db, 
		$_timelog_factory;
	
	public function __construct() {
		parent::__construct();
		
		// setup admin db
		$this->load->database();
		$this->_admin_db = new PDO('mysql:host='.$this->db->hostname.';dbname=admin', $this->db->username, $this->db->password);
		// define timelog factory
		$this->_timelog_factory = new Timelog_Factory($this->_admin_db, $this->_user->getId());
		
		// setup timesheet object
		$this->_timesheet = new Timesheet( 
			$this->_timelog_factory,
			new Project_Factory($this->_admin_db), 
			new Timelog_Categories_Factory($this->_admin_db),
			new Department_Factory($this->_admin_db),
			new Team_Factory($this->_admin_db),
			$this->_user
		);
		$this->_timesheet->_view_globals = $this->_view_globals;
		
		// check for timelog in session 
		if (isset($_SESSION['current_timelog']) && $timelog = $_SESSION['current_timelog']) {

		}
		else {
			$timelog = new Timelog();
		}
		
		// add timelog form to global view vars
		$this->_view_globals['current_timelog_form'] = $this->_timesheet->getTimelogFormHtml($timelog, array('sidebar_form'=>true));
		// include form.js for sidebar form
		$this->_javascript_includes[]= 'timelog_form';
	}
}