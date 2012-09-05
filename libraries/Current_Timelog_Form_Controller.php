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
		$this->display('timelog/form', $data, $returnHtml);
	}
	
	/**
	 * Get current timelog object that will be displayed in side form
	 */
	protected function getCurrentTimelog() {
		
	}
	
	/**
	 *	Display view in layout (or without if ajax request)
	 *	Uses CI view helper 
	 *	@param String $view - name of view to display
	 *	@param Array $data - array of data to render with view
	 */
	protected function display($view, $data) {
		$data['ajax'] = $this->_isAjax; // whether request is an ajax request or not
		$data['top_uri'] = site_url($this->_uri_segment); // this current controller name (eg: timelogs)
		$data['base_uri'] = site_url(); // base url (eg: http://admin)
		
		if ($this->_isAjax) {
			// ajax submission, don't display layout
			$this->load->view($view, $data);
		}
		else {
			
			// standard request wrap view in layout
			$data['current_timelog_form'] = $this->load->view('timelog/form', array_merge($data, array(
				'timelog'=> $this->_timelog, 
				'projects' => $this->_user->getVisibleProjects(new Project_Factory($this->_admin_db)), 
				'categories' => $this->_user->getVisibleTimelogCategories(new Timelog_Categories_Factory($this->_admin_db)),
				'ajax'=>1
			)), true);
			

			$body = $this->load->view($view, $data, true);
			$this->load->view($this->_layout_view, array('body' => $body));
		}
	}
}