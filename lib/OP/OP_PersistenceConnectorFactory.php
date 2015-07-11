<?php
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."OP_DBPersistenceConnector.php");

class WAT_OP_PersistenceConnectorFactory {
	static function getConnector() {
		$pc_obj = null;
		switch (strtoupper(WAT_OP_PC_TYPE)) {
			case "DB" : {
				require_once(WAT_OP_DB_LIB_PATH.DIRECTORY_SEPARATOR."DB_ServerFactory.php");
				$db_server_obj = WAT_DB_ServerFactory::getDbConnection(WAT_OP_DB_DSN);
				$pc_obj = new WAT_OP_DBPersistenceConnector($db_server_obj);
				break;
			}
			
			default : {
				throw new Exception("type of PersistenceConnector '".WAT_OP_PC_TYPE."' not supported");
			}
		}
		return $pc_obj;
	}
}
?>