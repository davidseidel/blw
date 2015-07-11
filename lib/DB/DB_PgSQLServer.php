<?PHP
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_Server.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_PgSQLResult.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_PgSQLPreparedStatement.php");

class WAT_DB_PgSQLServer extends WAT_DB_Server {
	public function connect() {
		// open connection
		if($this->password != null) {
			// with password
			$connection_id = @pg_connect ("host=".$this->host." ".
											"dbname=".$this->db_name." ".
											"user=".$this->user." ".
											"password=".$this->password);
		} else {
			// without password
			$connection_id = @pg_connect ("host=".$this->host." ".
											"dbname=".$this->db_name." ".
											"user=".$this->user);
		}
		
		// if connection-attemp was successful try to select the database
		if(is_resource($connection_id)) {
			$this->connection_id = $connection_id;
			return true;	
		} else {
			throw new Exception("Can't connect to database:".pg_errormessage());
		}
	}
	
	public function disconnect() {
		// check if there is an active connection
		if(is_resource($this->connection_id)) {
			// close connection to database
			pg_close($this->connection_id);
			
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
		$result_pointer = pg_query($this->connection_id, $query);
		
		// check if the query/statement was successfully
		if($result_pointer) {
			// if the query was an update or delete 
			// just returning a success
			if(!is_resource($result_pointer)) {
				return true;
			} else {
				// if the query has an result return it
				$result = new WAT_DB_PgSQLResult($result_pointer);
				return $result;
			}
		} else {
			throw new Exception("Query failed:".@pg_errormessage());
		}
	}
	
	public function prepareStatement($query) {
		return new WAT_DB_PgSQLPreparedStatement($this, $query);
	}
}

?>