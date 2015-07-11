<?PHP
class WAT_DB_Result_Iterator implements Iterator {
	protected $stmt;
	protected $resultCache = array();
	protected $rowIndex = 0;
	protected $currentIndex = 0;
	protected $max = 0;
	protected $maxRowCount = 0;
	protected $done = false;
	/**
	 * Pointer to the Result-Object
	 *
	 * @var WAT_DB_Result
	 */
	protected $result;
	
	public function __construct(WAT_DB_Result $result) {
		$this->result = $result;
	}
	
	public function rewind() {
		$this->currentIndex = -1;
		$this->done = false;
	}
	
	public function setMaxRowCount($max) {
		if(is_integer($max)) {
			$this->maxRowCount = $max;
		} else {
			throw new Exception("Parameter 'max' must be of type 'integer'");
		}
	}
	
	public function valid() {
		if($this->done && ($this->max == $this->currentIndex)) {
			return false;
		}
		return true;
	}
	
	function key() {
		return $this->currentIndex;
	}
	
	function current() {
		// if whe have no values stored in the result-array
		// try to get one
		if($this->currentIndex == -1) {
			$this->next();
		}
		
		// return the current-value
		return $this->resultCache[$this->currentIndex];
	}
	
	function next() {
		if($this->done) {
			return false;
		}
		
		$offset = $this->currentIndex + 1;
		
		if(!array_key_exists($offset, $this->resultCache)) {
			$row = $this->result->fetchRowObject();
			if(!$row || (($this->maxRowCount > 0) && ($this->maxRowCount == $offset)) ) {
				$this->done = true;
				$this->max = $this->currentIndex;
				return false;
			}
			$this->resultCache[$offset] = $row;
			++$this->rowIndex;
			++$this->currentIndex;
			return $this;
		} else {
			++$this->currentIndex;
			return $this;
		}
	}
	
	public function __destruct() {
		unset($this->resultCache);
	}
}
?>
