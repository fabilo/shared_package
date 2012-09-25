<?php
class Base_Render_Library {
	public $_view_globals = array();
	
	/**
	 *	Get html for a view rendered with data parameters
	 *	@param string $view_template - filename of view template to render
	 *	@param array $data - array of variables to render with view
	 */
	protected function renderView($view_template, $data) {
		extract($data);
		extract($this->_view_globals);
		ob_start();
		include $view_template.'.php';
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
}