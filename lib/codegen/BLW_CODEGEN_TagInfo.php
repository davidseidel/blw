<?php
class BLW_CODEGEN_TagInfo {
	protected $name = null;
	protected $tag_class = null;
	protected $attributes = null;
	protected $tag_lib_info = null;
	
	public function __construct($name, $tag_class, BLW_CODEGEN_TagLibInfo $tag_lib_info) {
		$this->name = $name;
		$this->tag_class = $tag_class;
		$this->attributes = new ArrayObject();
		$this->tag_lib_info = $tag_lib_info;
		$this->tag_lib_info->addTag($this);
	}
	
	public function addAttribute($name, $required_string) {
		if(!$this->attributes->offsetExists($name)) {
			if($required_string == "true") {
				$this->attributes->offsetSet($name, true);
			} else {
				$this->attributes->offsetSet($name, false);
			}
			return true;
		} 
		throw new Exception('Attribute allready exists for tag("'.$this->name.'/'.$this->tag_class.'"');
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getTagClass() {
		$tag_class_struct = explode('.', $this->tag_class);
		return array_pop($tag_class_struct); 
	}
	
	public function getImportString() {
		return $this->tag_class;
	}
}
?>