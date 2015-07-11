<?PHP
interface WAT_DB_PreparedStatement_Interface {
	public function __construct(WAT_DB_Server $db_server, $query);
	public function setString($parameterIndex, $value, $quote = false);
	public function setInteger($parameterIndex, $value);
	public function setBoolean($parameterIndex, $value);
	public function setDate($parameterIndex, $year, $month, $day);
	public function setDouble($parameterIndex, $value);
	public function setFloat($parameterIndex, $value);
	public function setNull($parameterIndex);
	public function setTime($parameterIndex, $hours, $minutes, $seconds);
	public function setTimestamp($parameterIndex, $value);
	public function setMaxRows($max);
	public function getMaxRows();
	public function execute();
}
?>