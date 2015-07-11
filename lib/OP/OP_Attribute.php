<?php 
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."OP_Attribute_Interface.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."OP_ValueAdded_Interface.php");
class WAT_OP_Attribute implements WAT_OP_Attribute_Interface, 
									WAT_OP_ValueAdded_Interface {
	/**
	* @var WAT_OP_Object
	*/
	protected $object;
	/**
	* @var WAT_OP_Attribute_Interface
	*/
	protected $metadata = null;

	/**
	* @var mixed
	*/
	protected $value = null;
	
	public function __construct(WAT_OP_Attribute_Interface $metadata) {
		$this->metadata = $metadata;
	}
	
	public function getName() {
		return $this->metadata->getName();
	}
	
	public function getType() {
		return $this->metadata->getType();
	}
	
	public function getSqlType() {
		return $this->metadata->getSqlType();
	}
	
	public function isRequired() {
		return $this->metadata->isRequired();
	}
	
	public function getMaxLength() {
		return $this->metadata->isRequired();
	}
	
	public function getObject() {
		return $object;
	}
	
	public function setObject(WAT_OP_Object $object) {
		$this->object = $object;
	}
	
	public function setValue($value) {
		// check if the primary key-value of a loaded object should be changed
		if($this->object instanceof WAT_OP_Object) {
			if($this->object->__isPrimaryKey($this) && ($this->object->__isLoaded())) {
				throw new Exception("Changing the value of the primary-key of a loaded Object is not permitted");
			}
		}
		$this->value = $value;
		
		
		return true;
	}
	
	public function getValue() {
		return $this->value;
	}
	
}
?>