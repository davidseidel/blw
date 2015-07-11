<?PHP
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_Result.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_MySQLResultRow.php");

class WAT_DB_MySQLResult extends WAT_DB_Result {
	/**
	 * @see WAT_DB_Result_Interface::fetchRow()
	 */
	public function fetchRow() {
		// check if we have an valid ressource-pointer
		if(is_resource($this->result)) {
			// fetch one result-row into an array
			// and return it
			return mysql_fetch_row($this->result);
		} else {
			throw new Exception();
		}
	}
	
	/**
	 * @see WAT_DB_Result_Interface::fetchAssoc()
	 */
	public function fetchAssoc() {
		// check if we have an valid ressource-pointer
		if(is_resource($this->result)) {
			// fetch one result-row into an associative array
			// and return it
			return mysql_fetch_assoc($this->result);
		} else {
			throw new Exception();
		}
	}
	
	public function getRowCount() {
		return mysql_numrows($this->result);
	}
	
	public function fetchRowObject() {
		// fetch an result-row
		$res_array = $this->fetchAssoc();
		
		// check if the result-row is valid array
		if(is_array($res_array)) {
			// instanciate the Result-Row-Object
			// and return it
			return new WAT_DB_MySQLResultRow($res_array);
		} else {
			// if there is no further result-row
			// return a null-pointer
			return null;
		}
	}
	
	public function __destruct() {
		mysql_free_result($this->result);
		$this->iterator = null;
	}
}
?>