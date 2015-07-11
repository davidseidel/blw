<?php
BLW_CORE_ClassLoader::import('app.lib.codegen.BLW_CODEGEN_CodeGenerator');
class BLW_CODEGEN_TagCloseCodeGenerator extends BLW_CODEGEN_CodeGenerator {
	const OBJECT_PREFIX = '$tag_';
	
	public function getObjectName() { }
	
	public function generateCode() {
		$code = '';
		
		$code.= $this->getParent().'->doEndTag();'."\n";
		
		$this->pageClassGenerator->addServiceStatement($code);
	}
}
?>