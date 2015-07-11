<?php
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_ValueHolder');
BLW_CORE_ClassLoader::import('app.lib.PSP.validator.BLW_PSP_Validator');

interface BLW_PSP_EditableValueHolder extends BLW_PSP_ValueHolder {
	/**
	 * Add a Validator instance to the set associated with this component.
	 *
	 * @param BLW_PSP_Validator $validator
	 */
	public function addValidator(BLW_PSP_Validator $validator);
   
 	/**
 	 *  Return the submittedValue value of this component.
 	 * 
 	 *  @return string
 	 */
	public function getSubmittedValue();
         
	/**
	 * Return the set of registered Validators for this component instance.
	 * @return ArrayObject
	 */
	public function getValidators();
          
 	/**
 	 * Return a flag indicating whether the local value of this component is valid (no conversion error has occurred).
 	 * @return bool
 	 */
 	public function isValid();
	
 	/**
 	 * Set the submittedValue value of this component.
 	 *
 	 * @param string $submittedValue
 	 */
 	public function	setSubmittedValue($submittedValue);

 	/**
 	 * Set a flag indicating whether the local value of this component is valid (no conversion error has occurred).
 	 *
 	 * @param bool $valid
 	 */
 	public function setValid($valid);
          
}
?>