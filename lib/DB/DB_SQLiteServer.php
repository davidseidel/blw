<?PHP
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_Server.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_SQLiteResult.php");

class WAT_DB_SQLiteServer extends WAT_DB_Server {
	public function connect() {
		// open connection
		$connection_id = @sqlite_open ($this->db_name, 666, $errormessage);
		// if connection-attemp was successful try to select the database
		if(is_resource($connection_id)) {
			$this->connection_id = $connection_id;
			return true;	
		} else {
			throw new Exception("Can't connect to database:".$errormessage);
		}
	}
	
	public function disconnect() {
		// check if there is an active connection
		if(is_resource($this->connection_id)) {
			// close connection to database
			sqlite_close($this->connection_id);
			
			// delete connection-id
			$this->connection_id = null;
			
			// return success
			return true;
		} else {
			throw new Exception("Can't disconnect: No connection found!!!");
		}
	}
	
	public function execute($query) {
		// execute the query
		$result_pointer = sqlite_query($this->connection_id, $query);
		
		// check if the query/statement was successfully
		if($result_pointer) {
			// if the query was an update or delete 
			// just returning a success
			if(!is_resource($result_pointer)) {
				return true;
			} else {
				$result = new WAT_DB_SQLiteResult($result_pointer);
				return $result;
			}
		} else {
			throw new Exception("Query failed:".sqlite_error_string(sqlite_last_error ($this->connection_id)));
		}
	}
	
	public function getRowCount() {
		return sqlite_numrows($this->result);
	}
	
	public function prepareStatement($query) {
		throw new Exception("Method not implemented!!!");
	}
}

?>