<?php
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."OP_XMLPackageReader.php");

class WAT_OP_PackageReaderFactory {
	public static function getPackageObject($dsn) {
		// storage for the new object
		$reader_obj = null;
		
		// parse dsn
		$dsn_struct = explode(":", $dsn);
		
		// check if the dsn is valid
		if(count($dsn_struct) >= 2) {
			// store reader-type
			$reader_type = $dsn_struct[0];
			
			switch($reader_type) {
				case "xml" : {
					$reader_obj = new WAT_OP_XMLPackageReader($dsn);
					break;
				}
				default: {
					throw new Exception("Reader-type '".$reader_type."' not supported!!!");
				}
			}		
		} else {
			throw new Exception("Malformed dsn:'".$dsn."'");
		}
		return $reader_obj;
	}
}
?>