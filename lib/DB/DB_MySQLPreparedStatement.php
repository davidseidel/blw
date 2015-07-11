<?php
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_PreparedStatement.php");

/**
 * This Class is useable to define an execute prepared statements like
 *              SELECT ?1,?2 FROM myTable WHERE id=?3
 * @see WAT_DB_PreparedStatement_Interface 
 * @package wat.db.mysql
 */
class WAT_DB_MySQLPreparedStatement extends WAT_DB_PreparedStatement {
	/**
	 * @see WAT_DB_PreparedStatement_Interface
	 */
	public function setString($parameterIndex, $value, $quote = false) {
		// quote is bool
		if(!is_bool($quote)) {
			throw new Exception("Parameter 'quote' must be of type 'bool'!!!");
		}
		
		// check value of $parameterIndex
		if(is_integer($parameterIndex)) {
			// check if we have to quote the string
			if($quote) {
				// assign the $value to the parameter
				$value = mysql_escape_string($value);
				$this->parameters[$parameterIndex] = "'".$value."'";
			} else {
				// assign the $value to the parameter
				$this->parameters[$parameterIndex] = mysql_escape_string($value);
			}
			// return success
			return true;
		} else {
			throw new Exception("Parameter 'parameterIndex' must be of type 'integer'");
		}
	}
	
	/**
	 * @see WAT_DB_PreparedStatement_Interface
	 */
	public function setInteger($parameterIndex, $value) {
		// check value of $parameterIndex
		if(is_integer($parameterIndex)) {
			// check if the value is an integer-value
			if(is_integer($value)) {
				// assign the $value to the parameter
				$this->parameters[$parameterIndex] = $value;
				// return success
				return true;
			} else {
				throw new Exception("Parameter 'value' must be of type 'integer'");
			}
		} else {
			throw new Exception("Parameter 'parameterIndex' must be of type 'integer'");
		}
	}
	
	/**
	 * @see WAT_DB_PreparedStatement_Interface
	 */
	public function setDate($parameterIndex, $year, $month, $day) { 
		// check value of $parameterIndex
		if(is_integer($parameterIndex)) {
			// check if the value is a date
			if(checkdate($month, $day, $year)) {
				// assign the $value to the parameter
				$this->parameters[$parameterIndex] = "'".$year."-".$month."-".$day."'";
				// return success
				return true;
			} else {
				throw new Exception("Date is not valid!!!");
			}
		} else {
			throw new Exception("Parameter 'parameterIndex' must be of type 'integer'");
		}
	}
	
	/**
	 * @see WAT_DB_PreparedStatement_Interface
	 */
	public function setDouble($parameterIndex, $value) { 
		// check value of $parameterIndex
		if(is_integer($parameterIndex)) {
			// check if the value is a double-value
			if(is_double($value)) {
				// assign the $value to the parameter
				$this->parameters[$parameterIndex] = $value;
				// return success	
				return true;
			} else {
				throw new Exception("Parameter 'value' must be of type 'double'!!!");
			}
		} else {
			throw new Exception("Parameter 'parameterIndex' must be of type 'integer'");
		}
	}
	
	/**
	 * @see WAT_DB_PreparedStatement_Interface
	 */
	public function setFloat($parameterIndex, $value) { 
		// check value of $parameterIndex
		if(is_integer($parameterIndex)) {
			// check if the value is a float-value
			if(is_float($value)) {
				// assign the $value to the parameter
				$this->parameters[$parameterIndex] = $value;	
				// return success
				return true;
			} else {
				throw new Exception("Parameter 'value' must be of type 'float'!!!");
			}
		} else {
			throw new Exception("Parameter 'parameterIndex' must be of type 'integer'");
		}
	}
	
	/**
	 * @see WAT_DB_PreparedStatement_Interface
	 */
	public function setNull($parameterIndex) { 
		// check value of $parameterIndex
		if(is_integer($parameterIndex)) {
				// assign NULL to the paramet
				$this->parameters[$parameterIndex] = "NULL";	
				// return success
				return true;
		} else {
			throw new Exception("Parameter 'parameterIndex' must be of type 'integer'");
		}
	}
	
	/**
	 * @see WAT_DB_PreparedStatement_Interface
	 */
	public function setTime($parameterIndex, $hours, $minutes, $seconds) { 
		// check the value of hour
		if(!($hours >= -838 && $hours <= 838 && is_integer($hours))) {
			throw new Exception("Value of 'hour' isn't valid!!!");
		} 
		
		// check the value of minutes
		if(!($minutes >= 0 && $minutes <= 59  && is_integer($minutes))) {
			throw new Exception("Value of 'minutes' isn't valid!!!");
		}
		
		// check the value of seconds
		if(!($seconds >= 0 && $seconds <= 59  && is_integer($seconds))) {
			throw new Exception("Value of 'seconds' isn't valid!!!");
		}
		
		// check value of $parameterIndex
		if(is_integer($parameterIndex)) {
			// assign the $value to the parameter
			$this->parameters[$parameterIndex] = "'".$hours.":".$minutes.":".$seconds."'";	
			// return success
			return true;
		} else {
			throw new Exception("Parameter 'parameterIndex' must be of type 'integer'");
		}
	}
	
	/**
	 * @see WAT_DB_PreparedStatement_Interface
	 */
	public function setTimestamp($parameterIndex, $value) { 
		// check if the value is an integer-value
		if(!is_integer($value)) {	
			throw new Exception("Value of 'value' isn't valid!!!");
		}
		
		// check value of $parameterIndex
		if(is_integer($parameterIndex)) {
			// format and assign the $value to the parameter
			$this->parameters[$parameterIndex] = date("%Y%m%d%h%i%s", $value);
			// return success
			return true;
		} else {
			throw new Exception("Parameter 'parameterIndex' must be of type 'integer'");
		}
	}
	
	/**
	 * @see WAT_DB_PreparedStatement_Interface
	 */
	public function setBoolean($parameterIndex, $value) {
		// check value of $parameterIndex
		if(is_integer($parameterIndex)) {
			// check if the value is an bool-value
			if(is_bool($value)) {
				// assign the $value to the parameter
				if($value == true) {
					$this->parameters[$parameterIndex] = 1;
				} else {
					$this->parameters[$parameterIndex] = 0;
				}
				// return success
				return true;
			} else {
				throw new Exception("Parameter 'value' must be of type 'bool'");
			}
		} else {
			throw new Exception("Parameter 'parameterIndex' must be of type 'integer'");
		}
	}
}
?>