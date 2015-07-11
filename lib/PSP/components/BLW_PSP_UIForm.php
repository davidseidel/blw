<?php
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_UIComponent');

class BLW_PSP_UIForm extends BLW_PSP_UIComponent {
	public function getRenderType() {
		return 'form';
	}
	
	public function getFamily() {
		return 'Form';
	}
	
	public function getAttributesToRender() {
		$attr = parent::getAttributesToRender();
		
		// attribute which should be used to render the component
		if(!is_null($this->getValue('action'))) {
			$attr->offsetSet('action', $this->getValue('action'));
		} else {
			$attr->offsetSet('action', $this->getValue($_SERVER['PHP_SELF']));
		}
		
		if(!is_null($this->getValue('method'))) {
			$attr->offsetSet('method', $this->getValue('method'));
		} else {
			$attr->offsetSet('method', 'post');
		}
		
		if(!is_null($this->getValue('enctype'))) {
			$attr->offsetSet('enctype', $this->getValue('enctype'));
		}
		
		if(!is_null($this->getValue('target'))) {
			$attr->offsetSet('target', $this->getValue('target'));
		}
		
		if(!is_null($this->getValue('onSubmit'))) {
			$attr->offsetSet('onSubmit', $this->getValue('onSubmit'));
		}
		return $attr;
	}
}
?>