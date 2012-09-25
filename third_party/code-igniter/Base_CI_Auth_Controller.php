<?php
/** 
 *	This file contains our base controller authentication methods and properties
 *
 *	@package BasePackage
 */

class Base_CI_Auth_Controller extends Base_CI_Controller {
	protected $_user;
	
	public function __construct() {
		parent::__construct();
		
		// setup user
		if ($user = $this->session->userdata('user')) {
			$this->_user = $user;
		}
		else {
			// authenticate user
			
			// temporary lol
			$user = new User();
			$this->_user = $user;
			$this->session->set_userdata('user', $user);
		}
	}
}