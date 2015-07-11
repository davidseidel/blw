<?PHP
class WAT_DB_Helper {
	/**
	* @return array parsed dnsn
	* @param string $dsn
	* @desc parsind a db-connection-string (dsn) to a associative array
	*/
	static function parseDSN($dsn) {
		// storage for the return-value
		$return_value = array();
		
		// regular expression for dsn
		$dsn_regEx = "=(.*)://(.*)@(.*)/(.+)=";
		if(preg_match($dsn_regEx, $dsn)) {
			$dsn_parsed = null;
			preg_match_all($dsn_regEx, $dsn, $dsn_parsed);
	
			// store driver-name
			$return_value["driver"] = $dsn_parsed[1][0];
			
			// parse authentication-string - user-name and (maybe) password
			$auth_struct = explode(":",$dsn_parsed[2][0]);
			
			if(count($auth_struct) == 2) {
				// if there is a user-name and a password store them
				$return_value["user"] = $auth_struct[0];
				$return_value["password"] = $auth_struct[1];
			} elseif(count($auth_struct) == 1) {
				// if there is only a user-name store it
				$return_value["user"] = $auth_struct[0];
				$return_value["password"] = null;
			} else {
				throw new Exception("Can't parse DSN: Invalid string for username/password!!!");
			}
			
			// store host-name
			$return_value["host"] = $dsn_parsed[3][0];
			
			// store password
			$return_value["db_name"] = $dsn_parsed[4][0];
			
			// return the dsn parsed into a associative array
			return $return_value;
		} else {
			// throw Exception
			throw new Exception("Can't parse DSN!!!");
		}
	}
}

?>