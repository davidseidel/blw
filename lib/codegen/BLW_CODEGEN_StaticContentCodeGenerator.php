<?php
BLW_CORE_ClassLoader::import('app.lib.codegen.BLW_CODEGEN_CodeGenerator');
class BLW_CODEGEN_StaticContentCodeGenerator extends BLW_CODEGEN_CodeGenerator {
	const OBJECT_PREFIX = '$content_';
	
	public function getObjectName() {
		return self::OBJECT_PREFIX.$this->getUniqueId();
	}
	
	public function generateCode() {
		$code = '';
		
		// static content
		$code.= $this->getObjectName().' = new BLW_PSP_StaticContent();'."\n";
		$code.= $this->getObjectName().'->setParent('.$this->getParent().');'."\n";
		$code.= $this->getObjectName().'->setContent(\''.$this->getSrc().'\');'."\n";
		$code.= $this->getObjectName().'->setPageContext('.self::$PAGE_CONTEXT.');'."\n";
		$this->page_container->addServiceStatement($code);
	}
}
?>