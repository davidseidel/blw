<?php
interface WAT_OP_PackageReader_Interface {
	public function __construct($dsn);
	public function getPackage();
}

?>