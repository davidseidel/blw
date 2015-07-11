<?php
BLW_CORE_ClassLoader::import('app.lib.PSP.validator.BLW_PSP_Validator');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_FacesContext');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_FacesMessage');

class BLW_PSP_RangeValidator implements BLW_PSP_Validator {
	protected $maximum = null;
	protected $minimum = null;
	
	public function setMaximum($max) {
		if(is_numeric($max)) {
			$this->maximum = $max;
			return true;
		}
		throw new BLW_CORE_IllegalArgumentException('numeric', 'max', $max);
	}
	
	public function setMinimum($min) {
		if(is_numeric($min)) {
			$this->minimum = $min;
			return true;
		}
		throw new BLW_CORE_IllegalArgumentException('numeric', 'min', $min);
	}
	
	public function getMinimum() {
		return $this->minimum;
	}
	
	public function getMaximum() {
		return $this->maximum;
	}
	
	public function validate(BLW_PSP_EditableValueHolder $valueHolder, $value = null) {
		if((!is_null($this->getMaximum()) && $this->getMaximum() < $value) || (!is_null($this->getMinimum()) && $this->getMinimum() > $value)) {
			$message = new BLW_PSP_FacesMessage();
			$message->setSummary("Value not in range!!!");
			BLW_PSP_FacesContext::instance()->addMessage($valueHolder->getId(), $message);	
			$valueHolder->setValid(false);
			return false;
		}
		return true;
	}
}
?>