<?php
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."OP_Attribute_Interface.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."OP_AttributeDefinition_Interface.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."OP_AttributeTypes.php");

class WAT_OP_AttributeMetadata implements WAT_OP_Attribute_Interface, 
											WAT_OP_AttributeDefinition_Interface {
	protected $name = null;
	protected $type = null;
	protected $sql_type = null;
	protected $required = false;
	protected $max_length = 0;
	/**
	* @var WAT_OP_Object_Interface
	*/
	protected $object = null;
	public function getName() {
		return $this->name;
	} 

	public function setName($name) {
		if(is_string($name)) {
			$this->name = $name;
		} else {
			throw new Exception("Parameter 'name' must be of type 'string'");
		}
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function setType($type) {
		if(in_array($type, WAT_OP_AttributeTypes::getTypes())) {
			$this->type = $type;
		} else {
			throw new Exception("Type '".$type."' not supported");
		}
	}
	
	public function getSqlType() {
		return $this->sql_type;
	}
	
	public function setSqlType($sql_type) {
		#FIX-ME: include the type-check for valid sql-types
		if(is_string($sql_type)) {
			$this->sql_type = $sql_type;
		} else {
			throw new Exception("Parameter 'sql_type' must be of type 'string'");
		}
	}
	
	public function isRequired() {
		return $this->required;
	}
	
	public function setRequired($required) {
	
		if(is_bool($required)) {
			$this->required = $required;
		} else {
			throw new Exception("Parameter 'required' must be of type 'bool'");
		}
	}
	
	public function getMaxLength() {
		return $this->max_length;
	}
	
	public function setMaxLength($max_length) {
		if(is_integer($max_length) && ($max_length >= 0)) {
			$this->max_length = $max_length;
		} else {
			throw new Exception("Parameter 'max_length' must be an integer-value bigger than or equal 0");
		}
	}
	
	public function getObject() {
		return $this->object;
	}
	
	public function setObject(WAT_OP_Object_Interface $object) {
		if($this->object == null) {
			$this->object = $object;
		} else {
			throw new Exception("Object allready set!!!");
		}
	}
}