<?PHP
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_Result.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_PgSQLResultRow.php");
class WAT_DB_PgSQLResult extends WAT_DB_Result {
	public function fetchRow() {
		if(is_resource($this->result)) {
			return  pg_fetch_row($this->result);
		} else {
			throw new Exception();
		}
	}
	
	public function fetchAssoc() {
		if(is_resource($this->result)) {
			return pg_fetch_assoc($this->result);
		} else {
			throw new Exception();
		}
	}
	
	public function getRowCount() {
		return pg_numrows($this->result);
	}
	
	public function fetchRowObject() {
		if(is_resource($this->result)) {
			$res_array = $this->fetchAssoc();
			if(is_array($res_array)) {
				return new WAT_DB_PgSQLResultRow($res_array);
			} else {
				return null;
			}	
		} else {
			throw new Exception();
		}
	}
}
?>