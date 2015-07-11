<?PHP
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_Server.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_MySQLResult.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_MySQLPreparedStatement.php");

class WAT_DB_MySQLServer extends WAT_DB_Server {
	public function connect() {
		// open connection
		if($this->password != null) {
			// with password
			$connection_id = @mysql_connect($this->host, $this->user, $this->password);
		} else {
			// without password
			$connection_id = @mysql_connect($this->host, $this->user);
		}
		
		// if connection-attemp was successful try to select the database
		if(is_resource($connection_id)) {
			if(mysql_selectdb($this->db_name, $connection_id)) {
				// if the selection of the database was a success 
				// store the connection-id and return success
				$this->connection_id = $connection_id;
				
				// return success
				return true;	
			} else {
				throw new Exception("Can't connect to database:".mysql_error());
			}
		} else {
			throw new Exception("Can't connect to database:".mysql_error());
		}
	}
	
	public function disconnect() {
		// check if there is an active connection
		if(is_resource($this->connection_id)) {
			// close connection to database
			mysql_close($this->connection_id);
			
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
		$result_pointer = @mysql_query($query, $this->connection_id);
		
		// check if the query/statement was successfully
		if($result_pointer) {
			// if the query was an update or delete 
			// just returning a success
			if(!is_resource($result_pointer)) {
				return true;
			} else {
				$result = new WAT_DB_MySQLResult($result_pointer);
				return $result;
			}
			
		} else {
			throw new Exception("Query failed: ".mysql_error($this->connection_id));
		}
	}
	
	public function prepareStatement($query) {
		return new WAT_DB_MySQLPreparedStatement($this, $query);
	}
}

?>