<?php
BLW_CORE_ClassLoader::import('app.lib.codegen.BLW_CODEGEN_CodeGenerator');
BLW_CORE_ClassLoader::import('app.lib.codegen.BLW_CODEGEN_UnboundNamespaceException');
class BLW_CODEGEN_TagEmptyCodeGenerator extends BLW_CODEGEN_CodeGenerator {
	const OBJECT_PREFIX = '$tag_';
	
	public function getObjectName() {
		return self::OBJECT_PREFIX.$this->getUniqueId();
	}
	
	public function generateCode() {
		$code = '';
		$object_name = $this->getObjectName();
		
		
		// extract prefix and tag name
		$tag_struct = explode(':', $this->getTagName());
		
		// load tag info
		$tag_info = $this->page_container->getTagInfo($tag_struct[0], $tag_struct[1]);
		
		// check if the namespace is registered
		if(!$this->page_container->isNamespaceRegistered($tag_struct[0])) {
			throw new BLW_CODEGEN_UnboundNamespaceException($tag_struct[1]);
		}
		
		// write init code for tag
		$code.= $this->getObjectName().' = new '.$tag_info->getTagClass().'();'."\n";
		$code.= $this->getObjectName().'->setPageContext('.self::$PAGE_CONTEXT.');'."\n";
		$code.= $this->getObjectName().'->setParent('.$this->getParent().');'."\n";
		if($this->getAttributeByName('id') != null) {
			$code.= $this->getObjectName().'->setId("'.$this->getAttributeByName('id').'");'."\n";
		}
		
		// attributes
		// example: $tag_1->setAttribute('uri', 'file://./taglibs/core.tld'); 
		$attributes = $this->getAttributes()->getArrayCopy();
		
		$attribute_names = array_keys($attributes);
		
		foreach($attribute_names as $attribute_name) {
			$code.= $this->getObjectName().'->setValue(\''.$attribute_name.'\', "'.$attributes[$attribute_name].'");'."\n";
		}
		
		$code.= $this->getObjectName().'->doStartTag();'."\n";
		$code.= $this->getObjectName().'->doEndTag();'."\n";
		
		$this->page_container->addServiceStatement($code);
	}
}
?>