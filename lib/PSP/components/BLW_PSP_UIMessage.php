<?php
BLW_CORE_ClassLoader::import('app.lib.PSP.components.BLW_PSP_UIInput');

class BLW_PSP_UIMessage extends BLW_PSP_UIComponent {
	public function getRenderType() {
		return 'message';
	}
	
	public function getFamily() {
		return 'Output';
	}
	
	
	public function getAttributesToRender() {
		$attr = parent::getAttributesToRender();
		
		// attribute which should be used to render the component
		if(!is_null($this->getValue('style'))) {
			$attr->offsetSet('style', $this->getValue('style'));
		}
		
		// init message string
		$message_string = '';
		
		// lookup error-messages of the client client-component
		$client_id = $this->getValue('for');
		if(strlen($client_id) > 0) { 
			$messages = BLW_PSP_FacesContext::instance()->getMessages($this->getValue('for'));
			foreach ($messages as $message) {
				$message_string.= $message->getSummary();
			}
		}
		
		$attr->offsetSet('value', $message_string);
		return $attr;
	}
}
?>