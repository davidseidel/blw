<?PHP
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_Server_Interface.php");

abstract class WAT_DB_Server implements WAT_DB_Server_Interface {
	protected $user = null;
	protected $password = null;
	protected $host = null;
	protected $db_name = null;
	protected $connection_id = null;
	public function __construct($host, $user, $password, $db_name) {
		$this->user = $user;
		$this->password = $password;
		$this->host = $host;
		$this->db_name = $db_name;
	}
	
	/**
	* Executes a query
	*
	* @param  string $query
	* @return WAT_DB_Result $result
	*/
	public function execute($query) {
		throw new  Exception("method not implemented!!!");
	}
	
	/**
	* Prepares an SQL-Statement
	*
	* @param  string $query
	* @return WAT_DB_PreparedStatement
	*/
	public function prepareStatement($query) {
		throw new  Exception("method not implemented!!!");
	}
}




?>