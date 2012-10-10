<?php
class Base_CI_Controller extends CI_Controller {
	protected $_layout_view = 'layout'; 
	protected $_uri_segment = ''; // url segment for this controller url
	protected $_isAjax;
	protected $_view_globals = array();
	protected $_meta_title;
	protected $_heading; 
	protected $_message;
	protected $_body;
	protected $_javascript_includes = array();
	protected $_flash_messages = array();

	public function __construct() {
		parent::__construct();
		session_start();
		
		if (isset($this->uri->segments[1]))
			$this->_uri_segment = $this->uri->segments[1]; // define url segment for controller
				
		// flag if ajax request
		$this->_isAjax = $this->input->is_ajax_request();
		
		// setup view global variables
		$this->_view_globals = array(
			'ajax' => $this->_isAjax, // whether request is an ajax request or not
			'top_uri' => site_url($this->_uri_segment), // this current controller name (eg: timelogs)
			'base_uri' => $this->config->item('base_url') // base url (eg: http://admin)
		);
		
		// get flash messages from previous request
		if (isset($_SESSION['flash_messages'])) $this->_flash_messages = $_SESSION['flash_messages'];
		// clear session of flash messages redy to add new
		$_SESSION['flash_messages'] = array();
	}
	
	/**
	 *	Display view in layout (or without if ajax request)
	 *	Uses CI view helper 
	 *	@param String $view - name of view to display
	 *	@param Array $data - array of data to render with view
	 */
	protected function display($view, $data, $options=array()) {
		// merge data array with global variables 
		// parameter array takes precidence over globals if array keys match 
		$data = array_merge($this->_view_globals, $data);
		
		if ($this->_isAjax) {
			// ajax submission output to browser without layout view
			$this->load->view($view, $data);
		}
		else {
			// standard request wrap view in layout
			$layout_data = array(
				'body' => $this->load->view($view, $data, true)
			);
			if (isset($data['heading'])) $layout_data['heading'] = $data['heading'];
			if (isset($data['page_heading'])) $layout_data['page_heading'] = $data['page_heading'];
			
			$this->load->view($this->_layout_view, $layout_data);
		}
	}
	
	protected function display2() {
		// setup view variables
		$data = $this->_view_globals;
		// meta title for html page
		$data['meta_title'] = $this->_meta_title;
		// heading for page to be displayed at top of view
		$data['heading'] = $this->_heading;
		// feedback message 
		$data['message'] = $this->_message;
		// body to displayed in the layout view
		$data['body'] = $this->_body; 
		
		// check for javascript file includes
		$data['javascript_includes'] = $this->_javascript_includes;
		
		$this->load->view($this->_layout_view, $data);
	}	
	
	/**
	 * Add message to flash_messages session variable
	 */
	protected function flash_message($message) {
		$message = trim($message);
		// check message isn't empty
		if (empty($message)) return false; 
		// add message
		$_SESSION['flash_messages'][]= $message;
	}
	
	protected function get_flash_messages() {
		return $this->_flash_messages;
	}
}
?>