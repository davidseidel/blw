<?php
abstract class BLW_CODEGEN_CodeGenerator {
	protected $attributes = null;
	protected $tag_name = null;
	protected $unique_id = null;
	/**
	 * 
	 *
	 * @var BLW_CODEGEN_PageContainer
	 */
	protected $page_container = null;
	protected $src = null;
	protected $parent_var = null;
	static $VIEW_ROOT = '$view_root';
	static $PAGE_CONTEXT = '$page_context'; 
	
	public final function __construct(BLW_CODEGEN_PageContainer $page_container, $unique_id, $tag_name) {
		// store code-compiler
		$this->page_container = $page_container;
		
		// store unique_id
		if(!is_null($unique_id) && strlen(trim($unique_id)) > 0) {
			$this->unique_id = $unique_id;
		} else {
			throw new Exception ('Empty unique id found!!!');
		}
		
		// store tag_name
		if(!is_null($tag_name) && strlen(trim($tag_name)) > 0) {
			$this->tag_name = $tag_name;
		} else {
			throw new Exception ('Empty tag name found!!!');
		}
		
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
		$this->tag_name = $name;
	}
	
	public function getTagName() {
		return $this->tag_name;
	}
	
	public function getUniqueId() {
		return $this->unique_id;
	}
	
	abstract public function getObjectName();
	
	abstract public function generateCode();
}
?>