<?php
class WAT_OP_RelationMetadata {
	protected $name = null;
	protected $role_1_name = null;
	protected $role_1_object_name = null;
	protected $role_1_multiplicity = null;
	protected $role_1_attribute_name = null;
	protected $role_2_name = null;
	protected $role_2_object_name = null;
	protected $role_2_multiplicity = null;
	protected $role_2_attribute_name = null;
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function setNameForRole1($name) {
		$this->role_1_name = $name;
	}
	
	public function setObjectNameForRole1($name) {
		$this->role_1_object_name = $name;
	}
	
	public function setMultiplicityForRole1($multiciplity) {
		$this->role_1_multiplicity = $multiciplity;
	}
	
	public function setAttributeNameForRole1($name) {
		$this->role_1_attribute_name = $name;
	}
	
	public function setNameForRole2($name) {
		$this->role_2_name = $name;
	}
	
	public function setObjectNameForRole2($name) {
		$this->role_2_object_name = $name;
	}
	
	public function setMultiplicityForRole2($multiciplity) {
		$this->role_2_multiplicity = $multiciplity;
	}
	
	public function setAttributeNameForRole2($name) {
		$this->role_2_attribute_name = $name;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getNameForRole1() {
		return $this->role_1_name;
	}
	
	public function getObjectNameForRole1() {
		return $this->role_1_object_name;
	}
	
	public function getMultiplicityForRole1() {
		return $this->role_1_multiplicity;
	}
	
	public function getAttributeNameForRole1() {
		return $this->role_1_attribute_name;
	}
	
	public function getNameForRole2() {
		return $this->role_2_name;
	}
	
	public function getObjectNameForRole2() {
		return $this->role_2_object_name;
	}
	
	public function getMultiplicityForRole2() {
		return $this->role_2_multiplicity;
	}
	
	public function getAttributeNameForRole2() {
		return $this->role_2_attribute_name;
	}
}
?>