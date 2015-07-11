<?php
class WAT_OP_PrimaryKeyFactory {
	protected $sqlTemplateSelect = '
		SELECT 
			pkey_value
		FROM 
			?0
		WHERE 
			table_name= ?2
		ORDER BY pkey_value DESC';
	
	protected $sqlTemplateInsert = '
		INSERT INTO
			?0(table_name, pkey_value)
		values(
			?1, 
			?2
		)';
	
	protected $sqlTemplateDelete = '
		DELETE FROM
			?0
		WHERE
			table_name = ?1 AND
			pkey_value < ?2';
	
	/**
	* @var integer
	*/
	protected $startValue = 0;
	/**
	* @var WAT_DB_Server
	*/
	protected $db_server = null;
	
	/**
	* @var string
	*/
	protected $table_name = null;
	
	/**
	* @var WAT_OP_PersistenceConnector
	*/
	protected $persistence_connector = null;
	
	public function __construct(WAT_DB_Server $server, $table_name = 'OP_Primary_Key') {
		$this->db_server = & $server;
		$this->table_name = $table_name;
	}
	
	public function setPersistenceConnector(WAT_OP_PersistenceConnector $connector) {
		$this->persistence_connector = $connector;
		return true;
	}
	
	public function getNextId($table_name = null) {
		if($table_name == null && $this->persistence_connector == null) {
			throw new Exception('No table-name defined');
		} else {
			$table_name = $this->persistence_connector->getPackageName()."_".$this->persistence_connector->getObjectName();
		}
		
		$actual_id = $this->getActualId($table_name);
		if($actual_id == null) {
			// try to create the sequence
			$this->createSequence($table_name);
			$actual_id = $this->getActualId($table_name);
			$actual_id = 0; 
		}
		$actual_id++;
		
		while(!$this->updateValue($table_name, $actual_id)) {
			$actual_id++;
		}
		
		$this->cleanup($table_name);
		return (int) $actual_id;
	}
	
	protected function cleanup($table_name) {
		$stmt = $this->db_server->prepareStatement($this->sqlTemplateDelete);
		$stmt->setString(0, $this->table_name);
		$stmt->setString(1, $table_name, true);
		$stmt->setInteger(2, $this->getActualId($table_name));
		return $stmt->execute();
	} 
	
	protected function updateValue($table_name, $id) {
		$stmt = $this->db_server->prepareStatement($this->sqlTemplateInsert);
		$stmt->setString(0, $this->table_name);
		$stmt->setString(1, $table_name, true);
		$stmt->setInteger(2, $id);
		return $stmt->execute();
	}
	
	protected function createSequence($table_name) {
		return $this->updateValue($table_name, $this->startValue);
	}
	
	protected  function getActualId($table_name) {
		$stmt = $this->db_server->prepareStatement($this->sqlTemplateSelect);
		$stmt->setString(0, $this->table_name);
		$stmt->setString(2, $table_name, true);
		$result = $stmt->execute();
		$result_iterator = $result->getIterator();
		if($result_iterator && $result_iterator->next()) {
			$result_row = $result_iterator->current();
			$pkey = $result_row->getInteger('pkey_value');
			return $pkey;
		}
		return 0;
	}
}


?>