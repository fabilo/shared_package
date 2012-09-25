<?php
/** 
 *	This file contains our base domain model methods and properties
 *
 *	@package BasePackage
 */

class Base_Domain_Model {
	protected $data = array();
	protected $factory_class;
	
	public function __construct($obj=null) {		
		// flip data array and clear
		$this->data = array_fill_keys($this->data, null);
		
		// initialise obj
		if (is_object($obj)) $obj = to_array($obj); // force obj to array if it's an object
		if (is_array($obj)) {
			// now loop obj and fill data properties with values
			foreach ($this->data AS $field_name => $value) {
				if (array_key_exists($field_name, $obj)) {
					// set data 
					$this->data[$field_name] = $obj[$field_name];
				}
			}
		}
	}
	
	/** __call
	 *	@Description: catch all undefined method calls against our data array and action them 
	 *		if they method name starts with 'get' or 'set'. Eg: get_id(), or set_id($id)
	 */
	public function __call($method, $args)  
	{
		$pattern = '/^[g|s]et_(.*)$/'; // regex pattern for method name
		$match = array(); // match method call for regex pattern
		
		// check called function is a 'get_fieldname() or set_fieldname()' method
		if (preg_match($pattern, $method, $match)) {
			 // method name matches pattern
			list($original_method_name, $property_name) = $match;
			
			// check that property exists in data array
			if (array_key_exists($property_name, $this->data)) {
				// check if 'set' or 'get' method called
				if (substr($method, 0, 1) == 'g') {
					// get property
					return $this->data[$property_name];
				}
				else {
					// set property - assuming it's a primitive type for now
					$this->data[$property_name] = $args[0];
					return true;
				}
			}

			// fieldname isn't a property
			throw new Exception ('No data field found ('.$property_name.') for getter/setter magic function: ' . $method);
		}
		
		throw new Exception ('Call to undefined method/class function: ' . $method);
	}
}