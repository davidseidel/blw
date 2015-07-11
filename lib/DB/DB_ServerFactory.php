<?PHP
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_Helper.php");

class WAT_DB_ServerFactory {
	static $connection_pool = array();
	/**
	* @param string $dsn
	* @desc empty
	* @return WAT_DB_Server server
	*/
	static function getDbConnection($dsn, $force_new = false) {
		// if no new connection is forced, check if the dsn allready exists in the connection
		if($force_new == false && array_key_exists($dsn, self::$connection_pool)) {
			// if there is an connection return them
			return self::$connection_pool[$dsn];
		}
		
		// storage for the driver-object
		$db_server_obj = null;
		
		// parse the dsn
		$parsed_dsn = WAT_DB_Helper::parseDSN($dsn);
		
		// instanciate the requested driver
		switch(strtolower($parsed_dsn["driver"])) {
			// Driver for MySQL
			case "mysql" : {
				require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_MySQLServer.php");
				$db_server_obj = new WAT_DB_MySQLServer($parsed_dsn["host"], $parsed_dsn["user"], 
												$parsed_dsn["password"], $parsed_dsn["db_name"]);
				break;		
			}
			
			case "pgsql" : {
				require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_PgSQLServer.php");
				$db_server_obj = new WAT_DB_PgSQLServer($parsed_dsn["host"], $parsed_dsn["user"], 
												$parsed_dsn["password"], $parsed_dsn["db_name"]);
				break;		
			}
			
			case "sqlite" : {
				require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_SQLiteServer.php");
				$db_server_obj = new WAT_DB_SQLiteServer($parsed_dsn["host"], $parsed_dsn["user"], 
												$parsed_dsn["password"], $parsed_dsn["db_name"]);
				break;		
			}
			
			default : {
				throw new Exception("database-driver '".$parsed_dsn["driver"]."' is not supported");
			}
		}
		
		// Try to connect to Database-Server
		$db_server_obj->connect();
		
		// store connection in the connection_pool
		self::$connection_pool[$dsn] = $db_server_obj;
		
		// return the driver-object
		return $db_server_obj;
	}
}

?>