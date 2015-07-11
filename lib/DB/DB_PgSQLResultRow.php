<?php
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_ResultRow.php");
class WAT_DB_PgSQLResultRow extends WAT_DB_ResultRow {
	public function getString($parameterName) {
		if(!array_key_exists($parameterName, $this->row_data)) {
			throw new Exception("Column with name '".$parameterName."' not exists in the result-row!!!");
		}
		return strval($this->row_data[$parameterName]);
	}
	
	public function getInteger($parameterName) {
		if(!array_key_exists($parameterName, $this->row_data)) {
			throw new Exception("Column with name '".$parameterName."' not exists in the result-row!!!");
		}
		
		if(!is_integer($this->row_data[$parameterName])) {
			throw new Exception("Value of '".$parameterName."' is not of type 'integer'!!!");
		}
		
		return intval($this->row_data[$parameterName]);
	}
	
	public function getBoolean($parameterName) {
		if(!array_key_exists($parameterName, $this->row_data)) {
			throw new Exception("Column with name '".$parameterName."' not exists in the result-row!!!");
		}
		
		if(!in_array($this->row_data[$parameterName], array("true","false","t", "f"))) {
			throw new Exception("Can't cast '".$parameterName."' to type 'bool'!!!");
		}
		
		if($this->row_data[$parameterName] == "true" || $this->row_data[$parameterName] == "t") {
			return true;
		} else {
			return false;
		}
	}
	
	public function getDate($parameterName) {
		if(!array_key_exists($parameterName, $this->row_data)) {
			throw new Exception("Column with name '".$parameterName."' not exists in the result-row!!!");
		}
		
		$date_regex = "=^[1-9]{1}[0-9]{3}\-[0-1]{1}[0-9]{1}\-[1-3]{1}[0-9]{1}$=";
		if(preg_match($date_regex, $this->row_data[$parameterName])) {
			$date_struct = explode("-", $this->row_data[$parameterName]);
			$year = $date_struct[0];
			$month = $date_struct[1];
			$day = $date_struct[2];
			if(checkdate($month, $day, $year)) {
				return array($year, $month, $day);
			} else {
				throw new Exception("date is not valid!!!");
			}
		} else {
			throw new Exception("date is not valid!!!");
		}
		
		$date_string = $this->row_data[$parameterName];
	}
	
	public function getDouble($parameterName) {
		if(!array_key_exists($parameterName, $this->row_data)) {
			throw new Exception("Column with name '".$parameterName."' not exists in the result-row!!!");
		}
		
		if(!is_double($this->row_data[$parameterName])) {
			throw new Exception("Value of '".$parameterName."' is not of type 'double'!!!");
		}
		
		return doubleval($this->row_data[$parameterName]);
	}
	
	public function getFloat($parameterName) {
		if(!array_key_exists($parameterName, $this->row_data)) {
			throw new Exception("Column with name '".$parameterName."' not exists in the result-row!!!");
		}
		
		if(!is_float($this->row_data[$parameterName])) {
			throw new Exception("Value of '".$parameterName."' is not of type 'float'!!!");
		}
		
		return floatval($this->row_data[$parameterName]);
	}
	
	public function getTime($parameterName) {
		if(!array_key_exists($parameterName, $this->row_data)) {
			throw new Exception("Column with name '".$parameterName."' not exists in the result-row!!!");
		}
		
		$time_regex = "=^[0-9]{1,3}:[0-5]{1}[0-9]{1}:[0-5]{1}[0-9]{1}$=";
		
		if(preg_match($time_regex, $this->row_data[$parameterName])) {
			$time_struct = explode(":", $this->row_data[$parameterName]);
			$hours = $time_struct[0];
			$minutes = $time_struct[1];
			$seconds = $time_struct[2];
			
			if(!($hours >= -838 && $hours <= 838 && is_integer($hours))) {
				throw new Exception("Value of '".$parameterName."' is not of type 'time'!!!");
			} 
			
			if(!($minutes >= 0 && $minutes <= 59  && is_integer($minutes))) {
				throw new Exception("Value of '".$parameterName."' is not of type 'time'!!!");
			}
			
			if(!($seconds >= 0 && $seconds <= 59  && is_integer($seconds))) {
				throw new Exception("Value of '".$parameterName."' is not of type 'time'!!!");
			}
			
			return mktime($hours, $minutes, $seconds, $month, $year, $day);
		} else {
			throw new Exception("date is not valid!!!");
		}
	}
	
	public function getTimestamp($parameterName) {
		throw new Exception("Not implemented!!!");
	}
}
?>