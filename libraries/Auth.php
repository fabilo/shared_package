<?php
/**
 * This file contains a class and logic for authenticating users and their session for the admin app
 */
class Auth extends Base_Render_Library {
	protected $_user_factory; 
	
	public function __construct($user_factory) {
		$this->_user_factory = $user_factory;
	}
	
	/**
	 * Get html for the login form
	 * @param string $username: username to prepopulate the form with
	 * @return html of user login form
	 */
	public function getLoginFormHtml($username, $message='') {
		return $this->renderView('users/login_form', array('username'=>$username, 'message'=>$message));
	}
	
	/**
	 * Authenticate user in database
	 * @param string $username: Username to authenticate user for
	 * @param string $password: Raw password submitted by user
	 * @return boolean of whether user was successfully authenticated or not
	 */
	public function authenticateLogin($username, $password) {
		// hash password - use standard md5 for now
		$hashedPassword = md5($password); 
		return $this->_user_factory->authenticateLogin($username, $hashedPassword);
	}
}