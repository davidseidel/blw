<?php
BLW_CORE_ClassLoader::import("app.core.BLW_CORE_Assert");

abstract class BLW_CODEGEN_CodeGenerator {
	protected $attributes = null;
	protected $tagName = null;
	protected $uniqueId = null;
	protected $pageClassGenerator = null;
	protected $src = null;
	protected $parent_var = null;
	static $VARNAME_VIEW_ROOT = '$view_root';
	static $VARNAME_PAGE_CONTEXT = '$page_context'; 
	
	public final function __construct(BLW_CODEGEN_PageClassGenerator $pageClassGenerator, $uniqueId, $tagName) {
		// store code-compiler
		$this->pageClassGenerator = $pageClassGenerator;
				
		// store uniqueId
		BLW_CORE_Assert::notNullOrEmptyString("uniqueId", $uniqueId);
		$this->uniqueId = $uniqueId;
		
		// store tag_name
		BLW_CORE_Assert::notNullOrEmptyString("tagName", $tagName);
		$this->tagName = $tagName;
		
		
		// init storage for attributes
		$this->attributes = new ArrayObject();
	}
	
	public function setParent($parent) {
		$this->parent_var = $parent;
	}
	
	public function getParent() {
		return $this->parent_var;
	}
	
	public function setSrc($src) {
		$this->src = $src;
	} 
	
	public function getSrc() {
		return $this->src;
	}
	
	public final function addAttribute($name, $value) {
		// test if the attribute exists - if not store it
		if(!$this->attributes->offsetExists($name)) {
			$this->attributes->offsetSet($name, $value);
			return true;
		}
		throw new Exception('Attribute with name "'.$name.'" allready exists!');
	}
	
	public function getAttributeByName($name) {
		if($this->attributes->offsetExists($name)) {
			return $this->attributes->offsetGet($name);
		}
		return null;
	}
	
	public function getAttributes() {
		return $this->attributes;
	}
	
	public function setTagName($name) {
		$this->tagName = $name;
	}
	
	public function getTagName() {
		return $this->tagName;
	}
	
	public function getUniqueId() {
		return $this->uniqueId;
	}
	
	abstract public function getObjectName();
	
	abstract public function generateCode();
}
?>