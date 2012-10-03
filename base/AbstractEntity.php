<?php

class AbstractEntity
{
    protected $id;
    protected $created_ts;
    protected $modified_ts;

    public function __construct($arr = null) {
    	if (is_array($arr)) {
    		$this->fromArray($arr);
    	}
    }

    // Map calls to protected/private fields to accessors when
    // defined. Otherwise, map them to the fields.
    public function __get($field) {
				// check that field exists on this object
        $this->checkField($field);
				// now build accessory name
        $accessor = "get".$this->to_camel_case($field);

        return method_exists($this, $accessor) && is_callable(array($this, $accessor))
            ? $this->$accessor()
            : $this->$field;
    }

    // Map calls to undefined mutators/accessors to the corresponding
    // fields
    public function __call($method, $arguments) {
        if (strlen($method) < 3) {
            throw new BadMethodCallException(
                "The mutator or accessor '$method' is not valid for this entity.");
        }
				// extract field name from method name
        $field = $this->from_camel_case(substr($method, 3));
				// check field exists
        $this->checkField($field);
				// now return field if this was a getter method call
        if (strpos($method, "get") === 0) {
            return $this->$field;
        }
    }


    // Make sure IDs are positive integers and assigned only once
    public function setId($id) {
			$id = $id*1;
        if ($this->id !== null) {
            throw new BadMethodCallException(
                "The ID for this entity has been set already.");
        }
        if (!is_int($id) || $id < 1) {
            throw new InvalidArgumentException(
              "The ID for this entity is invalid.".is_int($id));
        }
        $this->id = $id;
        return $this;
    }

    public function getId() {
        return $this->id;
    }


	public function isNew() {
		return $this->id == null; 
	}

    // Get the entity fields as an array
    public function toArray() {
        return get_object_vars($this);
    }

		// saturate obj from array
		public function fromArray($arr) {
			foreach ($arr AS $k => $v) {
				if ($v != null) {
					/** check if field has a mutator method **/
					// build method name
					$mutator = 'set'.str_replace(' ', '', ucwords(str_replace('_', ' ', $k)));
					if (method_exists($this, $mutator)) {
						$this->$mutator($v);	
					}
					// otherwise check for property
					elseif (property_exists($this, $k)) {
						$this->$k = $v;
					}
				}
			}
		}

    // Check if the given field exists in the entity
    protected function checkField($field) {
        if (!property_exists($this, $field)) {
            throw new InvalidArgumentException(
                "Setting or getting the field '$field' is not valid for this entity.");
        }
				return true;
    }

    // Validate and sanitize a string
    protected function sanitizeString($value, $min = 2, $max = null) {
        if (!is_string($value) || empty($value)) {
            throw new InvalidArgumentException(
              "The value of the current field must be a non-empty string.");
        }
        if (strlen($value) < (integer) $min || $max ? strlen($value) > (integer) $max : false) {
            throw new InvalidArgumentException(
              "Trying to assign an invalid string to the current field.");
        }
        return htmlspecialchars(trim($value), ENT_QUOTES);
    }

	protected function sanatizeTime($time) {
		$time = trim($time);
		$time = str_replace(':', '', $time);
		if (!preg_match('/^[0-2]?[0-9][0-5][0-9]$/', $time)) {
			throw new InvalidArgumentException(
          "Trying to assign invalid time (".$time.") for ".get_class($this));
		}
		return $time;
	}

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