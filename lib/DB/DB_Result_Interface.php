<?PHP
interface WAT_DB_Result_Interface {
	public function __construct($result);
	public function fetchRow();
	public function fetchAssoc();
	public function fetchRowObject();
	public function fetchAll($type);
	public function getRowCount();
}
?>