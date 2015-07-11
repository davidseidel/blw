<?php
BLW_CORE_ClassLoader::import('app.lib.PSP.components.BLW_PSP_UIInput');

class BLW_PSP_UIInputHidden extends BLW_PSP_UIInput {
	public function getRenderType() {
		return 'inputHidden';
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
		
		if(!is_null($this->getValue('tabindex'))) {
			$attr->offsetSet('tabindex', $this->getValue('tabindex'));
		}
		return $attr;
	}
}
?>