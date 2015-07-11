<?php
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_UIComponent');

class BLW_PSP_UICommandLink extends BLW_PSP_UICommand {
	public function getRenderType() {
		return 'commandLink';
	}
	
	public function getFamily() {
		return 'Command';
	}
	
	public function getAttributesToRender() {
		$attr = parent::getAttributesToRender();
		
		// attribute which should be used to render the component
		if(!is_null($this->getValue('href'))) {
			$attr->offsetSet('href', $this->getValue('href'));
		}
		
		if(!is_null($this->getValue('hreflang'))) {
			$attr->offsetSet('hareflang', $this->getValue('hreflang'));
		}
		
		if(!is_null($this->getValue('charset'))) {
			$attr->offsetSet('charset', $this->getValue('charset'));
		}
		
		if(!is_null($this->getValue('accesskey'))) {
			$attr->offsetSet('accesskey', $this->getValue('accesskey'));
		}
		
		if(!is_null($this->getValue('class'))) {
			$attr->offsetSet('class', $this->getValue('class'));
		}
		
		if(!is_null($this->getValue('lang'))) {
			$attr->offsetSet('lang', $this->getValue('lang'));
		}
		
		if(!is_null($this->getValue('name'))) {
			$attr->offsetSet('name', $this->getValue('name'));
		}
		
		if(!is_null($this->getValue('style'))) {
			$attr->offsetSet('style', $this->getValue('style'));
		}
		
		if(!is_null($this->getValue('tabindex'))) {
			$attr->offsetSet('tabindex', $this->getValue('tabindex'));
		}
		
		if(!is_null($this->getValue('target'))) {
			$attr->offsetSet('target', $this->getValue('target'));
		}
		
		if(!is_null($this->getValue('title'))) {
			$attr->offsetSet('title', $this->getValue('title'));
		}
		
		if(!is_null($this->getValue('type'))) {
			$attr->offsetSet('type', $this->getValue('type'));
		}
		return $attr;
	}
}
?>