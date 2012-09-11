<?php
/**
 * 	This file contains a class to handle logic for working with the current_timestamp object 
 *	displayed in the right hand side of the template. 
 */
require_once('libraries/base/Base_Auth_Controller.php');

class Current_Timelog_Form_Controller extends Base_Auth_Controller {
	protected $_admin_db, 
		$_timelog_factory;
	
	public function __construct() {
		parent::__construct();
		
		// setup admin db
		$this->_admin_db = new PDO('mysql:host=auqldva1;dbname=admin', 'AusnzMysqlUser', '123qwe!');
		
		// timelog factory
		$this->_timelog_factory = new Timelog_Factory($this->_admin_db, $this->_user->getId());
		
		// check for timelog in session 
		if (isset($_SESSION['current_timelog']) && $timelog = $_SESSION['current_timelog']) {
			$this->_timelog = $timelog; 
		}
		else {
			$this->_timelog = new Timelog();
		}
		
		// add timelog form to global view vars
		$this->_view_globals['current_timelog_form'] = $this->displayTimelogForm($this->_timelog, true, true);
	}

	/** 
	 *	Display form for a timelog
	 *	@var $timelog (timelog class) - timelog to put into the form
	 *	@var $sidebar_form boolean - whether or not the timelog form is for the sidebar
	 *	@var $return_html boolean - whether to return html or display it
	 */
	protected function displayTimelogForm(timelog $timelog, $sidebar_form=false, $return_html=false) {
		$data = array(
			'timelog' => $timelog,
			'projects' => $this->_user->getVisibleProjects(new Project_Factory($this->_admin_db)), 
			'categories' => $this->_user->getVisibleTimelogCategories(new Timelog_Categories_Factory($this->_admin_db)), 
			'sidebar_form' => $sidebar_form
		);
		if ($return_html)
			return $this->display('timelog/form', $data, array('return_html' => 1));
		else 
			$this->display('timelog/form', $data);
	}
}