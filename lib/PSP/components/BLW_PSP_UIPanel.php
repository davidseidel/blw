<?php
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_UIComponent');


class BLW_PSP_UIPanel extends BLW_PSP_UIComponent {
	public function getRenderType() {
		return 'panel';
	}
	
	public function getFamily() {
		return 'Panel';
	}
	
	public function getAttributesToRender() {
		$attr = parent::getAttributesToRender();
		
		// attribute which should be used to render the component
		if(!is_null($this->getValue('class'))) {
			$attr->offsetSet('class', $this->getValue('class'));
		}
		
		if(!is_null($this->getValue('style'))) {
			$attr->offsetSet('style', $this->getValue('style'));
		}
		
		
		return $attr;
	}
}
?>