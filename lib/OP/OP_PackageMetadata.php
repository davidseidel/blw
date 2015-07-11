<?php
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."OP_Package_Interface.php");

class WAT_OP_PackageMetadata implements WAT_OP_Package_Interface {
	protected $name = null;
	protected $version = null;
	protected $objects = array();
	protected $relations = array();
	protected $connector_params = array();
	
	public function setName($name) {
		if(is_string($name)) {
			$this->name = $name;
		} else {
			throw new Exception("Parameter 'name' must be of type 'string'");
		}
	}
	
	public function setConnectorParameters($params) {
		$this->connector_params = $params;
	}
	
	public function getConnectorParameter($connector_name, $parameter_name) {
		if(array_key_exists($connector_name, $this->connector_params)) {
			if(array_key_exists($parameter_name, $this->connector_params[$connector_name])) {
				return $this->connector_params[$connector_name][$parameter_name];
			} else {
				throw new Exception("The Connector with name '".$connector_name."' has not parameter with name '".$parameter_name."' in package '".$this->name."'");
			}
		} else {
			throw new Exception("A Connector with name '".$connector_name."' is not defined in package '".$this->name."'");
		}
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function addRelation(WAT_OP_RelationMetadata $metadata) {
		$this->relations[$metadata->getObjectNameForRole1()][$metadata->getAttributeNameForRole2()] = $metadata;
		$this->relations[$metadata->getObjectNameForRole2()][$metadata->getAttributeNameForRole1()] = $metadata;
	}
	
	public function getRelationsByObject(WAT_OP_Object_Interface $object) {
		if(array_key_exists($object->__getName(), $this->relations)) {
			return $this->relations[$object->__getName()];
		} else {
			return null;
		}
	}
	
	public function setVersion($version) {
		if(is_string($version)) {
			$this->version = $version;
		} else {
			throw new Exception("Parameter 'version' must be of type 'string'");
		}
	}
	
	public function getVersion() {
		return $this->version;
	}
	
	public function getObjects() {
		return $this->objects;
	}
	
	public function getObjectByName($name) {
		if(array_key_exists($name, $this->objects)) {
			return $this->objects[$name];
		} else {
			throw new Exception("object with name '".$name."' not exists in package !!!");
		}
	}
	
	public function addObject(WAT_OP_Object_Interface $object) {
		if($object instanceof WAT_OP_ObjectMetadata) {
			if(!array_key_exists($object->__getName(), $this->objects)) {
				$this->objects[$object->__getName()] = $object;
				$object->__setPackage($this);
			} else {
				throw new Exception("object with name '".$object->__getName()."' allready exists in package !!!");
			}
		} else {
			throw new Exception("parameter 'object' must be a instance of WAT_OP_ObjectMetadata");
		}
	}
}
?>