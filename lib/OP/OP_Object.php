<?php
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."OP_Attribute.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."OP_RelationAttribute.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."OP_Object_Interface.php");

abstract class WAT_OP_Object implements WAT_OP_Object_Interface {
	/**
	* @var WAT_OP_ObjectMetadata
	*/
	protected $metadata = null;

	/**
	* @var array
	*/
	protected $primary_key = array();

	/**
	* @var bool
	*/
	protected $loaded = false;


	/**
	* @var string
	*/
	protected $pkey_string = null;
	
	/**
	 * Attributes of the object
	 *
	 * @var array
	 */
	protected $attributes = array();

	/**
	 * Relations of the Object
	 *
	 * @var array
	 */
	protected $relation_attributes = array();
	
	/**
	* @var WAT_OP_PersistenceConnector_Interface
	*/
	protected $persistence_connector = null;

	protected final function __validateValue($name, $value) {	
		// check if the field is present
		if(array_key_exists($name, $this->attributes)) {
			$datatype =  $this->__getAttributeByName($name)->getType();
		}
		
		// check if the attribute is required
		$required = $this->__getAttributeByName($name)->isRequired();

		// if the value is empty and not required return a success
		if(!$required && (strlen($value) == 0)) {
			return true;
		}

		// if the value is required and empty return a fault
		if($required && (strlen($value) == 0)) {
			return false;
		}

		if($datatype == "UNDEFINED") {
			return true;
		}
		
		// check if the value is valid for the attribute
		return preg_match(WAT_OP_AttributeTypes::getRegex($datatype), $value);
	}

	public final function __setPersistenceConnector(WAT_OP_PersistenceConnector $connector) {
		$this->persistence_connector = $connector;
	}
	
	public final function __getPersistenceConnector() {
		return $this->persistence_connector;
	}
	
	public function __getPrimaryKeyString() {
		if($this->pkey_string == null) {
			$primary_key = $this->__getPrimaryKey();
			$pkey_values = array();
			foreach ($primary_key as $primary_key_attribute) {
				$pkey_values[] = $primary_key_attribute->getValue();
			}
			$this->pkey_string = implode(':',$pkey_values);
		}
		return $this->pkey_string;
	}

	public final function __getOId() {
		$matches = array();
		preg_match("=Object id #(.*)$=sU", (string) $this, $matches);
		return $matches[1];
	}
	
	public final function __construct(WAT_OP_Object_Interface $metadata) {
		// store the reference of the meta-data to the object
		$this->metadata = $metadata;

		// init attributes
		// get metadata for attributes
		$attributes_metadata = $this->metadata->__getAttributes();
		// instanciate attribute-objects and store them
		foreach ($attributes_metadata as $attribute_metadata) {
			$this->attributes[$attribute_metadata->getName()] = new WAT_OP_Attribute($attribute_metadata);
			$this->attributes[$attribute_metadata->getName()]->setObject($this);
		}
	
		// get all relations
		$relations = $this->metadata->__getPackage()->getRelationsByObject($this);

		// if there are relations create the attributes
		if(is_array($relations)) {
			$relation_attribute_names = array_keys($relations);
			foreach ($relation_attribute_names as $relation_attribute_name) {
				$this->relation_attributes[$relation_attribute_name] = new WAT_OP_RelationAttribute($this, $relation_attribute_name, $relations[$relation_attribute_name]);
			}
		}
	}
	
	public final function __getName() {
		return $this->metadata->__getName();
	}

	public final function __isPrimaryKey(WAT_OP_Attribute_Interface $attribute) {
		return $this->metadata->__isPrimaryKey($attribute);
	}

	public final function __getPrimaryKey() {
		$primary_key = array();
		// define primary key
		$primary_key_names = array_keys($this->metadata->__getPrimaryKey());
		foreach ($primary_key_names as $primary_key_name) {
			$primary_key[$primary_key_name] = $this->attributes[$primary_key_name];
		}
		return $primary_key;
	}

	public function __getAttributes() {
		return $this->attributes;
	}

	public function __getAttributeByName($name) {
		if(array_key_exists($name, $this->attributes)) {
			return $this->attributes[$name];
		} elseif(array_key_exists($name, $this->relation_attributes)) {
			return $this->relation_attributes[$name];
		} else {
			throw new Exception("Object contains no Attribute with name '".$name."'");
		}
	}

	public function __getPackage() {
		return $this->metadata->__getPackage();
	}

	public function __getVersion() {
		return $this->metadata->__getVersion();
	}

	public function __isLoaded() {
		return $this->loaded;
	}
	
	public function __setLoaded($flag) {
		if(is_bool($flag)) {
			$this->loaded = $flag;
		}
	}

	public function __isAutocommitted() {
		return $this->metadata->__isAutocommitted();
	}

	
	public function __call($method_name, $arguments) {
		// storage for the result
		$result = null;
		
		if(strlen($method_name) > 3) {
			// extract the type (set or get)
			$type = substr($method_name, 0, 3);
			// extract the name of the attributes
			$attribute_name = lcfirst(substr($method_name, 3, strlen($method_name) - 3));

			// check if the attribute exists
			if(!array_key_exists($attribute_name, $this->attributes) && !array_key_exists($attribute_name, $this->relation_attributes)) {
				throw new Exception("Call to undefined method '".$method_name."'");
			}
			
			// run the setValue(...) or getValue()
			switch ($type) {
				case "set" : {
					if(array_key_exists($attribute_name, $this->attributes)) {
						if($this->__validateValue($attribute_name, $arguments[0])) {
							$result = $this->__getAttributeByName($attribute_name)->setValue($arguments[0]);
						} else {
							throw new Exception("Mismatched datatype for '".$attribute_name."'");
						}
					} elseif (array_key_exists($attribute_name, $this->relation_attributes)) {
						$result = $this->relation_attributes[$attribute_name]->setValue($arguments[0]);	
					}
					break;
				}

				case "get" : {
					if(array_key_exists($attribute_name, $this->attributes)) {
						$result =  $this->__getAttributeByName($attribute_name)->getValue();
					} elseif (array_key_exists($attribute_name, $this->relation_attributes)) {
						$result = $this->relation_attributes[$attribute_name]->getValue();	
					}
					break;
				}
				
				case "add" : {
					if (array_key_exists($attribute_name, $this->relation_attributes)) {
						$result = $this->relation_attributes[$attribute_name]->addObject($arguments[0]);	
					}
					break;
				}
				
				case "del" : {
					if (array_key_exists($attribute_name, $this->relation_attributes)) {
						$result = $this->relation_attributes[$attribute_name]->removeObject($arguments[0]);	
					}
					break;
				}
			}
			return $result;
		}
		
		return null;
	}
}

if(!function_exists("lcfirst")) {
	function lcfirst($string) {
		if(!is_string($string)) {
			trigger_error("parameter 'string' must be of type string", E_USER_WARNING);
			return null;
		}

		$string_copy = $string;
		if(strlen($string) > 0) {
			$first_character_lc = strtolower($string_copy[0]);
			$string_copy[0] = $first_character_lc;
		}
		return $string_copy;
	}
}
?>
