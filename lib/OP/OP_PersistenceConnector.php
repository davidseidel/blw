<?php
require_once("OP_PersistenceConnector_Interface.php");
abstract class WAT_OP_PersistenceConnector implements WAT_OP_PersistenceConnector_Interface {
	/**
	* contains all existing objects
	*
	* @var ArrayObject
	*/
	static $objects = null;
	
	/**
	 * contains the specific object-name, which is controlled by the connector
	 *
	 * @var string
	 */
	protected $object_name = null;
	/**
	 * contains the specific package-name of the connector
	 *
	 * @var string
	 */
	protected $package_name = null;
	/**
	 * Stores all package-metadata to provide them to all connectors
	 *
	 * @var ArrayObject
	 */
	static $packages = null;
	public function __construct() {
		if(!is_object(WAT_OP_PersistenceConnector::$objects)) {
			WAT_OP_PersistenceConnector::$objects = new ArrayObject();
		}
		$class_name_struct = explode('_', get_class($this));
		preg_match("=(.*)Connector=sU", $class_name_struct[count($class_name_struct) - 1], $matches);
		$this->object_name  = $matches[1];
		$this->package_name = $class_name_struct[count($class_name_struct) - 2];
		$this->onInstanciate();
	}
	
	/**
	 * Adds a package-metadata to the connector, so it can be used by any connector
	 *
	 * @param WAT_OP_PackageMetadata $metadata
	 */
	static function addPackageMetadata(WAT_OP_PackageMetadata $metadata) {
		if(!(WAT_OP_PersistenceConnector::$packages instanceof ArrayObject)) {
			WAT_OP_PersistenceConnector::$packages = new ArrayObject();
			
		}
		return WAT_OP_PersistenceConnector::$packages->offsetSet($metadata->getName(), $metadata);
	}
	
	protected abstract function onInstanciate();
	protected abstract function onLoad();
	protected abstract function onRemove(WAT_OP_Object $object);
	protected abstract function onStore(WAT_OP_Object $object);

	public static function addObject(WAT_OP_Object $object) {
		$key = $object->__getPrimaryKeyString();
		if(!WAT_OP_PersistenceConnector::$objects->offsetExists($object->__getName().'/'.$key)) {
			WAT_OP_PersistenceConnector::$objects->offsetSet($object->__getName().'/'.$key, $object);
			return true;
		}
		return false;
	}
	
	public static function removeObject(WAT_OP_Object $object) {
		$key = $object->__getPrimaryKeyString();
		if(!WAT_OP_PersistenceConnector::$objects->offsetExists($object->__getName().'/'.$key)) {
			WAT_OP_PersistenceConnector::$objects->offsetUnset($object->__getName().'/'.$key);
			return true;
		}
		return false;
	}
	
	public function load() {
		$args = func_get_args();
		$pkey = implode(":", $args);
		if(!WAT_OP_PersistenceConnector::$objects->offsetExists($this->object_name.'/'.$pkey)) {
			$object = call_user_func_array(array($this,'onLoad'),   $args);
			$this->addObject($object);
			return $object;
		} else {
			return WAT_OP_PersistenceConnector::$objects->offsetGet($this->object_name.'/'.$pkey);
		}
	}
	
	public function remove(WAT_OP_Object $object) {
		$args = func_get_args();
		if(call_user_func_array(array($this,'onRemove'),   $args)) {
			$this->removeObject($object);
			return true;
		} else {
			return false;
		}
	}
	
	
	public function store(WAT_OP_Object $object) {
		$args = func_get_args();
		return call_user_func_array(array($this,'onStore'),   $args);
	}
	
	/**
	 * returns an instance of the controlled object
	 *
	 * @return WAT_OP_Object
	 */
	public function getInstance() {
		$package = WAT_OP_PersistenceConnector::$packages[$this->package_name];
		$class_name = $package->getName().'_'.$this->object_name;
		$this->object = new $class_name(WAT_OP_PersistenceConnector::$packages[$this->package_name]->getObjectByName($this->object_name));
		$this->object->__setPersistenceConnector($this);
		return $this->object;
	}
	
	protected function switchToCachedObject(WAT_OP_Object $object) {
		$pkey = $object->__getPrimaryKeyString();
		$this->addObject($object);
		return WAT_OP_PersistenceConnector::$objects->offsetGet($this->object_name.'/'.$pkey);
	}
	
	public function create() {
		$args = func_get_args();
		$pkey = implode(":", $args);
		if(WAT_OP_PersistenceConnector::$objects->offsetExists($this->object_name.'/'.$pkey)) {
			WAT_OP_PersistenceConnector::$objects->offsetGet($this->object_name.'/'.$pkey);
			return WAT_OP_PersistenceConnector::$objects->offsetGet($this->object_name.'/'.$pkey);
		}
		// create an new Object
		$object = $this->getInstance();
		
		// get the primary-key-attributes of the object
		$primary_key = $object->__getPrimaryKey();
		
		// check if the count of the primary-key-attributes is the same as the count of the arguments
		if(count($primary_key) == func_num_args()) {
			// get the arguments of the method
			$primary_key_values = func_get_args();
			$primary_key_count = 0;
			foreach ($primary_key as $primary_key_attribute) {
				// set the value of the primary-key-attribute
				$primary_key_attribute->setValue($primary_key_values[$primary_key_count]);
				
				// increment the counter
				$primary_key_count++;
			}
		} else {
			throw new Exception("The Object of Class '".get_class($object)."' has exactly ".count($primary_key)." primary key attributes but ".func_num_args()." given.");
		}
		
		// try to add the object to the object-registry
		if($this->addObject($object)) {
			return $object;
		} else {
			$args = func_get_args();
			$pkey = implode(":", $args);
			return WAT_OP_PersistenceConnector::$objects->offsetGet($this->object_name.'/'.$pkey);
		}
	}
	
	/**
	 * returns the name of the controlled object/class
	 *
	 * @return string
	 */
	public function getObjectName() {
		return $this->object_name;
	}
	
	/**
	 * returns the name of the package of the connector
	 *
	 * @return string
	 */
	public function getPackageName() {
		return $this->package_name;
	}
}


?>