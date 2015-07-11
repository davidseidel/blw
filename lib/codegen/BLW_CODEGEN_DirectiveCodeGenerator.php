<?php
BLW_CORE_ClassLoader::import('app.lib.codegen.BLW_CODEGEN_CodeGenerator');
BLW_CORE_ClassLoader::import('app.lib.codegen.BLW_CODEGEN_TagLibParser');
class BLW_CODEGEN_DirectiveGenerator extends BLW_CODEGEN_CodeGenerator {
	public function getObjectName() { }
	
	public function generateCode() {
		switch ($this->getTagName()) {
			case 'page' : {
				if(!is_null($this->getAttributeByName('name'))) {
					$this->page_container->setName($this->getAttributeByName('name'));
				}
				
				if(!is_null($this->getAttributeByName('info'))) {
					$this->page_container->setInfo($this->getAttributeByName('info'));
				}
				
				if(!is_null($this->getAttributeByName('import'))) {
					$imports = explode(',', $this->getAttributeByName('import'));
					foreach ($imports as $import) {
						$this->page_container->addImportStatement('BLW_CORE_ClassLoader::import(\''.$import.'\');'."\n");
					}
				}
				break;
			}
			
			case 'taglib' : {
				$tag_lib_parser = new BLW_CODEGEN_TagLibParser();
				if(!is_null($this->getAttributeByName('uri'))) {
					$tag_lib = $tag_lib_parser->getTagLib($this->getAttributeByName('uri'));
				} else {
					throw new Exception('Attribute "uri" is required!!!');
				}
				
				if(!is_null($this->getAttributeByName('prefix'))) {
					$this->page_container->addTagLib($tag_lib, $this->getAttributeByName('prefix'));
				} else {
					throw new Exception('Attribute "uri" is required!!!');
				}
				
				$tags = $tag_lib->getTags();
				foreach ($tags as $tag) {
					$this->page_container->addImportStatement('BLW_CORE_ClassLoader::import(\''.$tag->getImportString().'\');'."\n");
				}
				break;
			}
		}
	}
}
?>