<?php
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_ResultRow_Interface.php");

abstract class WAT_DB_ResultRow implements WAT_DB_ResultRow_Interface {
	
	/**
	 * storage for the fetched row-data
	 *
	 * @var array
	 */
	protected $row_data = array();
	
	public function __construct($row_data) {
		if(is_array($row_data)) {
			$this->row_data = $row_data;
		} else {
			throw new Exception("Parameter 'row_data' must be of type 'array'!!!");
		}
	}
}
?>