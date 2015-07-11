<?PHP
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_Result.php");
class WAT_DB_SQLiteResult extends WAT_DB_Result {
	public function fetchRow() {
		if(is_resource($this->result)) {
			return  sqlite_fetch_array ($this->result, SQLITE_NUM);
		} else {
			// throw Exception
		}
	}
	
	public function fetchAssoc() {
		if(is_resource($this->result)) {
			return  sqlite_fetch_array ($this->result, SQLITE_ASSOC);
		} else {
			throw new Exception();
		}
	}
}
?>