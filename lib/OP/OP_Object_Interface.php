<?php
interface WAT_OP_Object_Interface  {
	public function __getName();
	public function __getPrimaryKey();
	public function __getAttributes();
	public function __getAttributeByName($name);
	public function __getPackage();
	public function __getVersion();
	public function __isPrimaryKey(WAT_OP_Attribute_Interface $attribute);
	public function __isAutocommitted();
}
?>