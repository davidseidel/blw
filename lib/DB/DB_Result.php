<?PHP
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_Result_Interface.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_Result_Iterator.php");

class WAT_DB_FetchTypes {
	const ASSOC = 0;
	const ROW = 1;
	const OBJECT = 2;
}

abstract class WAT_DB_Result implements WAT_DB_Result_Interface, IteratorAggregate {
	protected $query = null;
	protected $result = null;
	protected $maxRowCount = 0;
	
	public function __construct($result, $maxRowCount = 0) {
		// check result-pointer
		if(is_resource($result)) {
			$this->result = $result;
		} else {
			throw new Exception("Parameter 'result' is not a valid ressource-pointer");
		}
		
		if(is_integer($maxRowCount)) {
			$this->maxRowCount = $maxRowCount;
		} else {
			throw new Exception("Parameter 'maxRowCount' must be of type 'integer'");
		}
	}
	
	public function setMaxRows($max) {
		if(is_integer($max)) {
			$this->maxRowCount = $max;
		} else {
			throw new Exception("Parameter 'max' must be of type 'integer'");
		}
	}
	
	public function getMaxRows() {
		return $this->maxRowCount;
	}
	
	public function fetchAll($type) {
		// storage for the fetched data
		$return_value = array();
		
		switch ($type) {
			case WAT_DB_FetchTypes::ASSOC : {
				// fetch every line and store to the return-value
				while ($row = $this->fetchAssoc()) {
					$return_value[] = $row;
				}
				break;
			}
			
			case WAT_DB_FetchTypes::ROW : {
				// fetch every line and store to the return-value
				while ($row = $this->fetchRow()) {
					$return_value[] = $row;
				}
				break;
			}
			
			case WAT_DB_FetchTypes::OBJECT : {
				// fetch every line and store to the return-value
				while ($row = $this->fetchRowObject()) {
					$return_value[] = $row;
				}
				break;
			}
			
			default: {
				// throw Exception, if we found an unsupported type of fethcing
				throw new Exception("Fetch-Type not supported");
			}
		}
		return $return_value;
	}
	
	
	public function getIterator() {
		$iterator = new WAT_DB_Result_Iterator($this);
		$iterator->setMaxRowCount($this->maxRowCount);
		return $iterator;
	}
}
?>