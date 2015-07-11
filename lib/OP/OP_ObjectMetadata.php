<?php
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."OP_Object_Interface.php");

class WAT_OP_ObjectMetadata implements WAT_OP_Object_Interface {
	protected $name = null;
	protected $version = null;
	protected $primary_key = array();
	protected $attributes = array();
	protected $autocommit = false;
	/**
	* @var WAT_OP_Package_Interface
	*/
	protected $package = null;
	
	public function __construct() {
	}
	
	public function __getName() {
		return $this->name;
	}
	
	public function __setName($name) {
		if(is_string($name)) {
			$this->name = $name;
		} else {
			throw new Exception("Parameter 'name' must be of type 'string'");
		}
	}
	
	public function __getPrimaryKey() {
		return $this->primary_key;
	}
	
	public function __isPrimaryKey(WAT_OP_Attribute_Interface $attribute) {
		$attribute_name = $attribute->getName();
		if(in_array($attribute_name, array_keys($this->primary_key))) {
			return true;
		} else {
			return false;
		}
	}
	
	public function __isAutocommitted() {
		return $this->autocommit;
	}
	
	public function __setAutocommit($flag) {
		if(is_bool($flag)) {
			$this->autocommit = $flag;
		} else {
			throw new Exception("Parameter 'flag' must be of type 'bool'");
		}
	}

	public function __addToPrimaryKey(WAT_OP_Attribute_Interface $attribute) {
		if($attribute instanceof WAT_OP_AttributeMetadata) {
			if(!array_key_exists($attribute->getName(), $this->primary_key)) {
				// if the attribute not exists in the object add it
				if(!array_key_exists($attribute->getName(), $this->attributes)) {
					$this->addAttribute($attribute);
					$this->primary_key[$attribute->getName()] = $attribute;
				} else {
					// if the attribute exists in the object store use the internal reference
					$this->primary_key[$attribute->getName()] = $this->attributes[$attribute->getName()];
				}
			} else {
				throw new Exception("attribute with name '".$attribute->getName()."' allready exists in primary_key !!!");
			}
		} else {
			throw new Exception("parameter 'attribute must be a instance of WAT_OP_AttributeMetadata");
		}
	}
	
	public function __getAttributes() {
		return $this->attributes;
	}
	
	public function __getAttributeByName($name) {
		if(array_key_exists($name, $this->attributes)) {
			return $this->attributes[$name];
		} else {
			throw new Exception("attribute with name '".$name()."' not exists in object !!!");
		}
	}
	
	public function __addAttribute(WAT_OP_Attribute_Interface $attribute) {
		if($attribute instanceof WAT_OP_AttributeMetadata) {
			if(!array_key_exists($attribute->getName(), $this->attributes)) {
				$attribute->setObject($this);
				$this->attributes[$attribute->getName()] = $attribute;
			} else {
				throw new Exception("attribute with name '".$attribute->getName()."' allready exists in object !!!");
			}
		} else {
			throw new Exception("parameter 'attribute must be a instance of WAT_OP_AttributeMetadata");
		}
	}
	
	public function __getPackage() {
		return $this->package;
	}
	
	public function __setPackage(WAT_OP_Package_Interface $package) {
		if($this->package == null) {
			$this->package = $package;
		} else {
			throw new Exception("Package allready set!!!");
		}
	}
	
	public function __getVersion() {
		return $this->version;
	}
	
	public function __setVersion($version) {
		if(is_string($version)) {
			$this->version = $version;
		} else {
			throw new Exception("Parameter 'version' must be of type 'string'");
		}
	}
}
?>