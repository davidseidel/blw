<?php
interface WAT_OP_Package_Interface {
	public function getName();
	public function setName($name);
	public function getVersion();
	public function setVersion($version);
	public function getObjects();
	public function addObject(WAT_OP_Object_Interface $object);
	public function getObjectByName($name);
	public function addRelation(WAT_OP_RelationMetadata $metadata);
	public function getRelationsByObject(WAT_OP_Object_Interface $object);
	public function setConnectorParameters($params);
	public function getConnectorParameter($connector_name, $parameter_name);
	
}
?>