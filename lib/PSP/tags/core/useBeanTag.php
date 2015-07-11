<?php
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_Tag');
class useBeanTag extends BLW_PSP_Tag {
	public function doStartTag() {
		$scope = $this->getValue('scope');
		$class = $this->getValue('class');
		if(is_null($scope) || $scope == "page") {
			$this->pageContext->setBean($this->getId(), new $class(), BLW_PSP_PageContext::PAGE_SCOPE);
		}
		
		if($this->getValue('scope') == "session") {
			if(is_null($this->pageContext->getBean($this->getId(), BLW_PSP_PageContext::SESSION_SCOPE))) {
				$this->pageContext->setBean($this->getId(), new $class(), BLW_PSP_PageContext::SESSION_SCOPE);
			}
			$bean = $this->pageContext->getBean($this->getId(), BLW_PSP_PageContext::SESSION_SCOPE);
			$this->pageContext->setBean($this->getId(), $bean, BLW_PSP_PageContext::PAGE_SCOPE);
		}
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