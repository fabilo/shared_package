<?php
/** 
 *	This file contains our base controller authentication methods and properties
 *
 *	@package BasePackage
 */

class Base_CI_Auth_Controller extends Base_CI_Admin_Controller {
	
	public function __construct() {
		parent::__construct();
		
		// setup user
		if ($user_id = $this->session->userdata('user_id')) {
			$this->_user = $this->_user_factory->getById($user_id);
		}
		else {
			// redirect user to login 
			redirect('users/login');
		}
	}
}