<?php
BLW_CORE_ClassLoader::import('app.lib.PSP.components.BLW_PSP_UICommand');

class BLW_PSP_UICommandButton extends BLW_PSP_UICommand {
	public function getRenderType() {
		return 'commandButton';
	}
	
	public function getFamily() {
		return 'Command';
	}
	
	public function getAttributesToRender() {
		$attr = parent::getAttributesToRender();
		
		// attribute which should be used to render the component
		if(!is_null($this->getValue('type'))) {
			$attr->offsetSet('type', $this->getValue('type'));
		}
		
		if(!is_null($this->getValue('name'))) {
			$attr->offsetSet('name', $this->getValue('name'));
		}
		
		if(!is_null($this->getValue('value'))) {
			$attr->offsetSet('value', $this->getValue('value'));
		}
		
		if(!is_null($this->getValue('accesskey'))) {
			$attr->offsetSet('accesskey', $this->getValue('accesskey'));
		}
		
		if(!is_null($this->getValue('disabled'))) {
			$attr->offsetSet('disabled', $this->getValue('disabled'));
		}
		
		if(!is_null($this->getValue('lang'))) {
			$attr->offsetSet('lang', $this->getValue('lang'));
		}
		
		if(!is_null($this->getValue('style'))) {
			$attr->offsetSet('style', $this->getValue('style'));
		}
		
		if(!is_null($this->getValue('tabindex'))) {
			$attr->offsetSet('tabindex', $this->getValue('tabindex'));
		}
		
		if(!is_null($this->getValue('title'))) {
			$attr->offsetSet('title', $this->getValue('title'));
		}
		
		if(!is_null($this->getValue('onClick'))) {
			$attr->offsetSet('onClick', $this->getValue('onClick'));
		}
		return $attr;
	}
}
?>