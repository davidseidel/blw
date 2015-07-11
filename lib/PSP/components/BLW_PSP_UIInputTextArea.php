<?php
BLW_CORE_ClassLoader::import('app.lib.PSP.components.BLW_PSP_UIInput');

class BLW_PSP_UIInputTextArea extends BLW_PSP_UIInput {
	public function getRenderType() {
		return 'inputTextArea';
	}
	
	public function getFamily() {
		return 'Input';
	}
	
	public function getAttributesToRender() {
		$attr = parent::getAttributesToRender();
		
		// attribute which should be used to render the component
		if(!is_null($this->getValue('accesskey'))) {
			$attr->offsetSet('accesskey', $this->getValue('accesskey'));
		}
		
		if(!is_null($this->getValue('disabled'))) {
			$attr->offsetSet('disabled', $this->getValue('disabled'));
		}
		
		if(!is_null($this->getValue('maxlength'))) {
			$attr->offsetSet('maxlength', $this->getValue('maxlength'));
		}
		
		if(!is_null($this->getValue('style'))) {
			$attr->offsetSet('style', $this->getValue('style'));
		}
		
		if(!is_null($this->getValue('class'))) {
			$attr->offsetSet('class', $this->getValue('class'));
		}
		
		if(!is_null($this->getValue('tabindex'))) {
			$attr->offsetSet('tabindex', $this->getValue('tabindex'));
		}
		
		if(!is_null($this->getValue('rows'))) {
			$attr->offsetSet('rows', $this->getValue('rows'));
		}
		
		if(!is_null($this->getValue('cols'))) {
			$attr->offsetSet('cols', $this->getValue('cols'));
		}
		
		if(!is_null($this->getValue('wrap'))) {
			$attr->offsetSet('wrap', $this->getValue('wrap'));
		}
		return $attr;
	}
}
?>