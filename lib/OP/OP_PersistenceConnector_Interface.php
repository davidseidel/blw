<?php
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."OP_Object.php");
interface WAT_OP_PersistenceConnector_Interface {
	public function load();
	public function loadAll();
	public function store(WAT_OP_Object $object);
	public function remove(WAT_OP_Object $object);
	public function loadRelatedObjects($relation_name, WAT_OP_Object $object,  $attribute_name, $related_object_name, $related_attribute_name);
	public function updateRelations(WAT_OP_Object $object, $only_remove = false);
	public function getObjectName();
	public function getPackageName();
}


?>