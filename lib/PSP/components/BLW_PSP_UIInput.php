<?php
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_UIComponent');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_EditableValueHolder');

abstract class BLW_PSP_UIInput extends BLW_PSP_UIComponent implements BLW_PSP_EditableValueHolder {
	/**
	 * Validators, which are called during the call of setSubmittedValue()
	 *
	 * @var ArrayObject
	 */
	protected $validators = null;
	
	/**
	 * Flag which indicates, if the value of 'submitted_value' is valid
	 *
	 * @var bool
	 */
	protected $valid = true;
	
	public function setValid($flag) {
		if(is_bool($flag)) {
			$this->valid = $flag;
			return true;
		} 
		return false;
	}
	
	public function addValidator(BLW_PSP_Validator $validator) {
		if(!($this->validators instanceof ArrayObject)) {
			$this->validators = new ArrayObject();
		}
		return $this->validators->append($validator);
	}
	
	public function getValidators() {
		return $this->validators;
	}
	
	public function isValid() {
		return $this->isValid();
	}
	
	public function setSubmittedValue($value) {
		$this->setValue('submitted_value', $value);
		if($this->validators instanceof ArrayObject) {
			foreach ($this->validators as $validator) {
				$validator->validate($this, $value);
			}
		}
		$this->setValue('value', $value);
	}
	
	public function getSubmittedValue() {
		return $this->getValue('submitted_value');
	}
	
	public function setStoredValue($value) {
		$this->setValue('value', $value);
	}
	
	public function getStoredValue() {
		return $this->getValue('value');
	}
	
	public function getAttributesToRender() {
		$attr = parent::getAttributesToRender();
		$attr->offsetSet('value', $this->getValue('value'));
		return $attr;
	}
}