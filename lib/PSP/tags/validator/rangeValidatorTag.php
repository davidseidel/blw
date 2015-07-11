<?php
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_Tag');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_EditableValueHolder');
BLW_CORE_ClassLoader::import('app.lib.PSP.validator.BLW_PSP_RangeValidator');
BLW_CORE_ClassLoader::import('app.core.BLW_CORE_NullPointerException');
class rangeValidatorTag extends BLW_PSP_Tag {
	public function doStartTag() {
		// get the component to validate
		$component = $this->getParent();
		
		
		if($component instanceof BLW_PSP_EditableValueHolder) {
			// instanciate the validator	
			$validator = new BLW_PSP_RangeValidator();
			
			// setup values for the validator
			$validator->setMinimum($this->getValue('min'));
			$validator->setMaximum($this->getValue('max'));
			
			// add the validator to the component
			$component->addValidator($validator);
		} else {
			throw new BLW_CORE_NullPointerException();
		}
	}
	
	public function doEndTag() { }
	
	public function doAfterBody() { }
	
	public function getOutput() {
		return null;
	}
}

?>