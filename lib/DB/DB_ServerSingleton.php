<?php
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DB_ServerFactory.php");

class WAT_DB_ServerSingleton {
	static $instance = null;
	static public function getInstance($dsn = null) {
		if(is_null(self::$instance)) {
			self::$instance = WAT_DB_ServerFactory::getDbConnection($dsn);
			if(is_null(self::$instance)) {
				throw new Exception("Could not connect to database!!!");
			}
		}
		return self::$instance;
	}
}
?>