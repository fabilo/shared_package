<?php
class Base_Controller extends CI_Controller {
	protected $_layout_view = 'layout'; 
	protected $_uri_segment = ''; // url segment for this controller url
	protected $_isAjax;
	protected $_view_globals = array();

	public function __construct() {
		parent::__construct();
		session_start();
		
		$this->_uri_segment = $this->uri->segments[1]; // define url segment for controller
				
		// flag if ajax request
		$this->_isAjax = $this->input->is_ajax_request();
		
		// setup view global variables
		$this->_view_globals = array(
			'ajax' => $this->_isAjax, // whether request is an ajax request or not
			'top_uri' => site_url($this->_uri_segment), // this current controller name (eg: timelogs)
			'base_uri' => dirname(site_url()).'/' // base url (eg: http://admin)
		);
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
		
		if (isset($options['return_html']) && $options['return_html']) {
			// return html
			$html = $this->load->view($view, $data, true);
			return $html;
		}
		elseif ($this->_isAjax) {
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
}
?>