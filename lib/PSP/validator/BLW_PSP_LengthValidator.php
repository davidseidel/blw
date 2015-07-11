<?php
BLW_CORE_ClassLoader::import('app.lib.PSP.validator.BLW_PSP_Validator');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_FacesContext');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_FacesMessage');

class BLW_PSP_LengthValidator implements BLW_PSP_Validator {
	protected $max_length = null;

	public function setMaxLength($max) {
		if(is_numeric($max)) {
			$this->max_length = $max;
			return true;
		}
		throw new BLW_CORE_IllegalArgumentException('numeric', 'max', $max);
	}
	
	public function getMaxLength() {
		return $this->max_length;
	}
	
	public function validate(BLW_PSP_EditableValueHolder $valueHolder, $value = null) {
		if(is_string($value)) {
			$length = strlen($value);
		} else {
			throw new BLW_CORE_IllegalArgumentException('string', 'value', $value);
		}
		
		if(($this->getMaxLength() < $length)) {
			$message = new BLW_PSP_FacesMessage();
			$message->setSummary("Value to long!!!");
			BLW_PSP_FacesContext::instance()->addMessage($valueHolder->getId(), $message);	
			$valueHolder->setValid(false);
			return false;
		}
		return true;
	}
}
?>