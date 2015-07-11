<?php
Interface WAT_OP_Attribute_Interface {
	public function getName();
	public function getType();
	public function getSqlType();
	public function isRequired();
	public function getMaxLength();
	public function getObject();
}
?>