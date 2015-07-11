<?php
class BLW_CORE_Assert {
	static function notNull($name, $value) {
		if ($value != null) {
			return;
		}
		throw new Exception ( $name." must not be null or empty!" );
	}
	static function notNullOrEmptyString($name, $value) {
		if (!is_null($value) && strlen(trim($value)) > 0) {
			return;
		}
		throw new Exception ( $name." must not be null or empty!" );
	}
}
?>