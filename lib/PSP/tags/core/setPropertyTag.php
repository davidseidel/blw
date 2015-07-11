<?php
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_Tag');
class setPropertyTag extends BLW_PSP_Tag {
	public function doStartTag() {
		$bean = $this->pageContext->getBean($this->getValue('name'));
		call_user_func_array(array($bean, 'set'.ucfirst($this->getValue('property'))), array($this->getValue('value')));
	}
	
	public function doEndTag() { }
	
	public function doAfterBody() { }

	public function getOutput() { }
	
}

?>