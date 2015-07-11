<?php
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_EditableValueHolder');

interface BLW_PSP_Validator {
	/**
	 * perform a validation for a given component
	 *
	 * @param BLW_PSP_EditableValueHolder $valueHolder
	 * @param stringe $value
	 * @return bool true = value was successfully validated
	 */
	public function validate(BLW_PSP_EditableValueHolder $valueHolder, $value = null);
}
?>