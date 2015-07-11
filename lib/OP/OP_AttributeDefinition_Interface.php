<?php
interface WAT_OP_AttributeDefinition_Interface {
	public function setName($name);
	public function setType($type);
	public function setSqlType($sql_type);
	public function setRequired($required);
	public function setMaxLength($maxlength);
	public function setObject(WAT_OP_Object_Interface $object);
}
?>