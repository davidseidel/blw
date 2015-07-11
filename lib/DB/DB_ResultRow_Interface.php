<?php
interface WAT_DB_ResultRow_Interface {
	public function __construct($row_data);
	public function getString($parameterName);
	public function getInteger($parameterName);
	public function getBoolean($parameterName);
	public function getDate($parameterName);
	public function getDouble($parameterName);
	public function getFloat($parameterName);
	public function getTime($parameterName);
	public function getTimestamp($parameterName);
}
?>