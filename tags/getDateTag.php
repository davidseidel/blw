<?php
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_Tag');
class getDateTag extends BLW_PSP_Tag {
	protected $date = null;
	public function doStartTag() {
		$this->date = time();
	}
	
	public function doEndTag() {
	}
	
	public function doAfterBody() {
	}
	
	public function getOutput() {
		$this->pageContext->getResponse()->getWriter()->writeText($this->date);
	}
	
}

?>