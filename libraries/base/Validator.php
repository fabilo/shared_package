<?php 
/**
 *	This file contains a class that should be used for defining and excuting validation against our objects. 
 *	
 *	The class works by defining static class variables for the regex patterns to validate for specific field types. 
 *	You can then validate a string against that pattern by calling utilising the __call() magic method on this method. 
 *	ie: to validate an owner number you can call $validator->validateOwnerNumber($owner_number) 
 *	This method doesn't exist on the class, but is dynamically executing via __call(). This way we only have to add new 
 *	variables to the class and can start validating without defining redundant methods. 
 *
 *	Also, keeping the class variables as public and static, we can leverage them in other places (eg: for html5 input validation) 
 *	by simply calling the field name statically; <input pattern="<?php echo Base_Validator::$owner_number_pattern ?>" />
 *
 *	@Author: fabian.snaith@wyn.com
 *	@Date:	2021-08-24
 */
class Base_Validator {
	// owner number pattern - used by WMSP owner payment form
	public static $owner_number_pattern = '^\d{11}$';
	// finance payment contract number - used by WMSP levy payment form
	public static $finance_contract_number_pattern = '^\d{12}$';
	// basic email address pattern
	public static $email_pattern = '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,3}(?:\.[a-zA-z]{2})*$';
	// credit card pattern matching for different card types
	public static $credit_card_pattern = '^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$';
	// person's full name pattern
	public static $full_name_pattern = '^(\w|\s|\'|\.|-)+$';
	// monetary amount
	public static $monetary_amount_pattern = '^\d+(\.\d{1,2})*$';

	/** 
	 *	Magic PHP5 method to catch validate method calls 
	 *
	 *	This method is only called when a method is called on this class that doesn't exist. 
	 *	This way we don't have to create multiple calls for a simple validate call
	 *	Throws an Invalid_Input_Exception is validation fails
	 *	We could set this up for static method calls if we had php >= 5.3
	 *	@param	string	$name - name of function called on class
	 *	@param	array	$arguments - array of arguments passed to method
	 *		$arguments[0] - 1st argument passed to function - value to be validated
	 *		$arguments[1] - 2nd argument passed to function - label to be displayed for error message
	 */
	public function __call($name, $arguments) {
		// check function name starts with 'validate' eg: validateOwnerNumber(12345678901)
		if (!(substr($name, 0, strlen('validate')) == 'validate')) {
			throw new Exception($name.' method doesn\'t exist for class Base_Validator'); 
		}

		// generate varname from method name, ie: validateOwnerNumber -> owner_number
		$field_name = self::from_camel_case(substr($name, strlen('validate')));

		// generate class pattern varname from field name, ie: owner_number -> owner_number_pattern
		$this_field_name = $field_name.'_pattern';

		// now check that pattern field name exists for this class
		if (!in_array($this_field_name, array_keys(get_class_vars('Base_Validator')))) {
			throw new Exception($field_name.' field name not found for class Base_Validator.');
		}

		// set label from 2nd argument else $field_name - used in error message
		$label = ($arguments[1]) ? $arguments[1] : $field_name;

		// now validate value (first argument)
		self::validate($arguments[0], self::$$this_field_name, $label);

		return $this;
	}

	/**
	 *	Validate string with a regex pattern
	 *	Throws an Invalid_Input_Exception if validation fails
	 *	@param String $subject - subject to match against pattern with regex
	 *	@param String $pattern - regex pattern to use for matching
	 *	@param String $field_name (optional) - title/label for $subject param, used for error message
	 *	@return true if validation is successful
	 */
	public static function validate($subject, $pattern, $field_name='') {
		if (!preg_match("/$pattern/", $subject)) {
			// build error message
			$error_message = 'Invalid value'; 
			if ($field_name) $error_message .= ' set for '.$field_name;
			throw new Invalid_Input_Exception($error_message);
		}
		return true; // validation passed
	}

// following classes should be in a helper class =\

  /**
   * Translates a camel case string into a string with underscores (e.g. firstName -&gt; first_name)
   * @param    string   $str    String in camel case format
   * @return    string            $str Translated into underscore format
   */
  public static function from_camel_case($str) {
    $str[0] = strtolower($str[0]);
    $func = create_function('$c', 'return "_" . strtolower($c[1]);');
    return preg_replace_callback('/([A-Z])/', $func, $str);
  }
 
  /**
   * Translates a string with underscores into camel case (e.g. first_name -&gt; firstName)
   * @param    string   $str                     String in underscore format
   * @param    bool     $capitalise_first_char   If true, capitalise the first char in $str
   * @return   string                              $str translated into camel caps
   */
  public static function to_camel_case($str, $capitalise_first_char = false) {
    if($capitalise_first_char) {
      $str[0] = strtoupper($str[0]);
    }
    $func = create_function('$c', 'return strtoupper($c[1]);');
    return preg_replace_callback('/_([a-z])/', $func, $str);
  }
}