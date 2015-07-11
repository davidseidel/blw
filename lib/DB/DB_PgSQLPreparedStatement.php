<?php
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_PreparedStatement.php");

class WAT_DB_PgSQLPreparedStatement extends WAT_DB_PreparedStatement {
	public function setString($parameterIndex, $value, $quote = false) {
		if(!is_bool($quote)) {
			throw new Exception("Parameter 'quote' must be of type 'bool'!!!");
		}
		
		// check value of $parameterIndex
		if(is_integer($parameterIndex)) {
			if($quote) {
				$this->parameters[$parameterIndex] = "'".pg_escape_string($value)."'";
			} else {
				$this->parameters[$parameterIndex] = pg_escape_string($value);
			}
			return true;
		} else {
			throw new Exception("Parameter 'parameterIndex' must be of type 'integer'");
		}
	}
	
	public function setInteger($parameterIndex, $value) {
		// check value of $parameterIndex
		if(is_integer($parameterIndex)) {
			if(is_integer($value)) {
				$this->parameters[$parameterIndex] = $value;
				return true;
			} else {
				throw new Exception("Parameter 'value' must be of type 'integer'");
			}
		} else {
			throw new Exception("Parameter 'parameterIndex' must be of type 'integer'");
		}
	}
	
	public function setDate($parameterIndex, $year, $month, $day) { 
		if(is_integer($parameterIndex)) {
			if(checkdate($month, $day, $year)) {
				$this->parameters[$parameterIndex] = "'".$year."-".$month."-".$day."'";
				return true;
			} else {
				throw new Exception("Date is not valid!!!");
			}
		} else {
			throw new Exception("Parameter 'parameterIndex' must be of type 'integer'");
		}
	}
	
	public function setDouble($parameterIndex, $value) { 
		if(is_integer($parameterIndex)) {
			if(is_double($value)) {
				$this->parameters[$parameterIndex] = $value;	
				return true;
			} else {
				throw new Exception("Parameter 'value' must be of type 'double'!!!");
			}
		} else {
			throw new Exception("Parameter 'parameterIndex' must be of type 'integer'");
		}
	}
	
	public function setFloat($parameterIndex, $value) { 
		if(is_integer($parameterIndex)) {
			if(is_float($value)) {
				$this->parameters[$parameterIndex] = $value;	
				return true;
			} else {
				throw new Exception("Parameter 'value' must be of type 'float'!!!");
			}
		} else {
			throw new Exception("Parameter 'parameterIndex' must be of type 'integer'");
		}
	}
	
	public function setNull($parameterIndex) { 
		if(is_integer($parameterIndex)) {
				$this->parameters[$parameterIndex] = "NULL";	
				return true;
		} else {
			throw new Exception("Parameter 'parameterIndex' must be of type 'integer'");
		}
	}
	
	public function setTime($parameterIndex, $hours, $minutes, $seconds) { 
		if(!($hours >= 0 && $hours <= 23 && is_integer($hours))) {
			throw new Exception("Value of 'hour' isn't valid!!!");
		} 
		
		if(!($minutes >= 0 && $minutes <= 59  && is_integer($minutes))) {
			throw new Exception("Value of 'minutes' isn't valid!!!");
		}
		
		if(!($seconds >= 0 && $seconds <= 59  && is_integer($seconds))) {
			throw new Exception("Value of 'seconds' isn't valid!!!");
		}
		
		if(is_integer($parameterIndex)) {
			$this->parameters[$parameterIndex] = "'".$hours.":".$minutes.":".$seconds."'";	
			return true;
		} else {
			throw new Exception("Parameter 'parameterIndex' must be of type 'integer'");
		}
	}
	
	public function setTimestamp($parameterIndex, $value) { 
		if(!is_integer($value)) {	
			throw new Exception("Value of 'value' isn't valid!!!");
		}
		
		if(is_integer($parameterIndex)) {
			$this->parameters[$parameterIndex] = date("%Y-%m-%d %h:%i:%s", $value);
			return true;
		} else {
			throw new Exception("Parameter 'parameterIndex' must be of type 'integer'");
		}
	}
	
	public function setBoolean($parameterIndex, $value) {
		// check value of $parameterIndex
		if(is_integer($parameterIndex)) {
			if(is_bool($value)) {
				if($value == true) {
					$this->parameters[$parameterIndex] = "true";
				} else {
					$this->parameters[$parameterIndex] = "false";
				}
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