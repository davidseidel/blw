<?php
class BLW_CODEGEN_TagLibInfo {
	protected $version = null;
	protected $short_name = null;
	protected $tags = null;
	public function __construct($short_name, $version) {
		$this->version = $version;
		$this->short_name = $short_name;
		$this->tags = new ArrayObject();
	}
	
	public function addTag(BLW_CODEGEN_TagInfo $tag) {
		$this->tags->offsetSet($tag->getName(), $tag);
		return true;
	}
	
	public function getTagByName($name) {
		if($this->tags->offsetExists($name)) {
			return $this->tags->offsetGet($name);
		}
		throw new Exception('Tag with name "'.$name.'" not exists in the tag-library !');
	}
	
	public function getTags() {
		return $this->tags;
	}
}
?>