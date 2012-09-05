<?php

class Base_Controller extends CI_Controller {
	protected $_layout_view = 'layout'; 
	protected $_uri_segment = ''; // url segment for this controller url
	protected $_isAjax;

	public function __construct() {
		parent::__construct();
		session_start();
		
		$this->_uri_segment = $this->uri->segments[1]; // define url segment for controller
				
		// flag if ajax request
		$this->_isAjax = $this->input->is_ajax_request();
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
			$body = $this->load->view($view, $data, true);
			$this->load->view($this->_layout_view, array('body' => $body));
		}
	}
}
?>