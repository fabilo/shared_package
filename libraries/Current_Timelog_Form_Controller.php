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
		$this->_admin_db = new PDO('mysql:host=localhost;dbname=admin', 'root', '');
		
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
		$this->_view_globals['current_timelog_form'] = $this->getTimelogSidebarFormHtml($this->_timelog);
	}
	
	/** 
	 *	Display form for a timelog
	 *	@var $timelog (timelog class) - timelog to put into the form
	 */
	private function displayTimelogForm(timelog $timelog, $returnHtml = false) {
		$data = array(
			'timelog' => $timelog,
			'projects' => $this->_user->getVisibleProjects(new Project_Factory($this->_admin_db)), 
			'categories' => $this->_user->getVisibleTimelogCategories(new Timelog_Categories_Factory($this->_admin_db))
		);
		$this->display('timelog/form', $data, array('return_html'=>true));
	}
	
	/**
	 * 	Get HTML for timelog form in sidebar
	 *
	 *	@param Timelog $timelog - Timelog object to display in form
	 */
	protected function getTimelogSidebarFormHtml(timelog $timelog) {
		return $this->load->view('timelog/form', array_merge($this->_view_globals, array(
			'timelog'=> $timelog, 
			'projects' => $this->_user->getVisibleProjects(new Project_Factory($this->_admin_db)), 
			'categories' => $this->_user->getVisibleTimelogCategories(new Timelog_Categories_Factory($this->_admin_db)),
			'ajax'=>1
		)), true);
	}
}