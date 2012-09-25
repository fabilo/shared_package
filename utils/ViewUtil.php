<?php 
/**
 *	This file contains a static utility class for rendering html 
 */
class ViewUtil {
	/**
	 *	Get html for a view rendered with data parameters
	 *	@param string $view_template - filename of view template to render
	 *	@param array $data - array of variables to render with view
	 */
	public static function render($viewFilename, $data) {
		// define full filename to template 
		$filename = $viewFilename.'.phtml';

		// extract data array so template can render them
		extract($data);

		// render phtml template with data vars
		ob_start();
		require($filename);
		// get html
		$html = ob_get_contents();
		ob_end_clean();

		// return html
		return $html;
	}
}