<?php
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_Tag');

class writeBeanTag extends BLW_PSP_Tag {
	public function doStartTag() {
	}
	
	public function doEndTag() {
	}
	
	public function doAfterBody() {
	}
	
	public function getOutput() {
		$value = $this->pageContext->getBean($this->getValue('name'));
		$this->pageContext->getResponse()->getWriter()->writeText($value);
	}
	
}

?>