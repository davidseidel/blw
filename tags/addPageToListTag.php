<?php
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_Tag');
class addPageToListTag extends BLW_PSP_Tag {
	public function doStartTag() {
		$bean = $this->pageContext->getBean("page_lister");
		$bean->append($this->pageContext->getRequest()->getRequestURI());
	}
	
	public function doEndTag() {
	}
	
	public function doAfterBody() {
	}
	
	public function getOutput() {
		return null;
	}
	
}

?>