<?php
class WAT_OP_RelationAttribute {
	protected $metadata = null;
	protected $name = null;
	protected $object_name = null;
	protected $multiplicity = null;
	/**
	* @var WAT_OP_Object
	*/
	protected $object = null;
	protected $related_name = null;
	protected $related_object_name = null;
	protected $related_multiplicity = null;
	protected $objects = array();
	protected $loaded = false;
	
	public function __construct(WAT_OP_Object $object, $name, WAT_OP_RelationMetadata $metadata) {
		$this->name = $name;
		$this->object_name = $object->__getName();
		$this->object = $object;
		$this->metadata = $metadata;
		if($this->metadata->getAttributeNameForRole1() == $this->name) {
			$this->object_name = $this->metadata->getObjectNameForRole2();
			$this->multiplicity  = $this->metadata->getMultiplicityForRole2();
			$this->related_object_name = $this->metadata->getObjectNameForRole1();
			$this->related_name = $this->metadata->getAttributeNameForRole2();
			$this->related_multiplicity  = $this->metadata->getMultiplicityForRole1();
		} elseif($this->metadata->getAttributeNameForRole2() == $this->name) {
			$this->object_name = $this->metadata->getObjectNameForRole1();
			$this->multiplicity  = $this->metadata->getMultiplicityForRole1();
			$this->related_object_name = $this->metadata->getObjectNameForRole2();
			$this->related_name = $this->metadata->getAttributeNameForRole1();
			$this->related_multiplicity  = $this->metadata->getMultiplicityForRole2();
		}
	}
	
	
	public function getName() {
		return $this->name;
	}
	
	public function getObjectName() {
		return $this->object_name;
	}
	
	public function getRelatedName() {
		return $this->related_name;
	}
	
	public function getRelatedObjectName() {
		return $this->related_object_name;
	}
	
	public function addObject(WAT_OP_Object $object) {
		if(!$this->loaded) {
			$this->loadObjects();
		}
		$key = $object->__getPrimaryKeyString();
		if(!array_key_exists($key, $this->objects)) {
			$this->objects[$key] = $object;
			$related_method_name = "add".ucfirst($this->related_name);
			call_user_func(array($object, $related_method_name), $this->object);
		}
		return true;
	}
	
	public function removeObject(WAT_OP_Object $object) {
		if(!$this->loaded) {
			$this->loadObjects();
		}
		$key = $object->__getPrimaryKeyString();
		if(array_key_exists($key, $this->objects)) {
			unset($this->objects[$key]);
			$related_method_name = "del".ucfirst($this->related_name);
			call_user_func(array($object, $related_method_name), $this->object);
			return true;
		} else {
			return false;
		}
		
	}
	
	public function loadObjects() {
		if(!$this->loaded) {
			$connector = $this->object->__getPersistenceConnector();
			$objects = $connector->loadRelatedObjects($this->metadata->getName(), $this->object, $this->name, $this->related_object_name, $this->related_name); 
			foreach ($objects as $object) {
				$key = $object->__getPrimaryKeyString();
				$this->objects[$key] = $object;
			}
			$this->loaded = true;
			return true;
		} else {
			return false;
		}	
	}
	
	public function getValue() {
		if(!$this->loaded) {
			$this->loadObjects();
		}
		return array_values($this->objects);
	}
}
?>