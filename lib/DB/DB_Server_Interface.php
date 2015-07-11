<?PHP
/**
 * Interface for Access to a RDBMS
 *
 */
interface WAT_DB_Server_Interface {
	/**
	 * Constructor
	 *
	 * @param string $user
	 * @param string $password
	 * @param string $host
	 * @param string $db_name
	 */
	public function __construct($user, $password, $host, $db_name);
	
	/**
	 * Connect to RDBMS
	 *
	 */
	public function connect();
	
	/**
	 * Disconnects from RDBMS
	 *
	 */
	public function disconnect();
	
	
	/**
	 * Prepare a sql-statement and return the PreparedStatement-Object
	 * @return WAT_DB_PreparedStatement
	 * @param string $query
	 */
	public function prepareStatement($query);
	
	/**
	 * Executes a query
	 *
	 * @param  string $query
	 * @return WAT_DB_Result $result
	 */
	public function execute($query);
}
?>