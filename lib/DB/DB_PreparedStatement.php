<?php
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_PreparedStatement_Interface.php");

abstract class WAT_DB_PreparedStatement implements WAT_DB_PreparedStatement_Interface {
	/**
	 * Connection to the RDBMS
	 *
	 * @var WAT_DB_Server
	 */
	protected $db_server = null;
	
	/**
	 * Query-String
	 *
	 * @var string
	 */
	protected $query = null;
	
	/**
	 * Limit for the maximum number of Rows
	 *
	 * @var integer
	 */
	protected $maxRows = 0;
		
	/**
	 * Parameters of Query
	 *
	 * @var array
	 */
	protected $parameters = array();
	
	/**
	 * Constructor
	 *
	 * @param WAT_DB_Server $db_server
	 * @param  string $query f.e. SELECT ?1, name FROM person WHERE id = ?2
	 */
	public function __construct(WAT_DB_Server $db_server, $query) {
		$this->db_server = $db_server;
		$this->query = $query;
	}
	
	public function setMaxRows($max) {
		// check value of $max
		if(is_integer($max)) {
			$this->maxRows = $max;
		} else {
			throw new Exception("Parameter 'max' must be of type 'integer'");
		}
	}
	
	public function getMaxRows() {
		return $this->maxRows;
	}
	
	public function execute() {
		// copy query
		$query_copy = $this->query;
		
		// replace parameters in query
		$parameter_keys = array_keys($this->parameters);
		foreach ($parameter_keys as $parameter_key) {
			$parameter_value = $this->parameters[$parameter_key];
			$parameter_regex = "=\?".$parameter_key."([^0-9]{1}|$)=sU";
			$query_copy = preg_replace($parameter_regex, $parameter_value."\\1", $query_copy);
		}
		
		// execute query
		$result = $this->db_server->execute($query_copy);
		
		// check if the result is a object
		if(is_object($result)) {
			// set maximum limit of rows
			$result->setMaxRows($this->getMaxRows());
		}
		
		// return the result-object
		return $result;
	}
	
	public function __destruct() {
		$this->db_server = null;
		$this->query = null;
	}
}

?>