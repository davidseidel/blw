<?php
class BLW_CODEGEN_UnboundNamespaceException extends Exception {
	protected $namespace = null;
	public function __construct($namespace = null) {
		$this->namespace = $namespace;
		if(!is_null($namespace) && is_string($namespace)) {
			parent::__construct('Ubound namespace "'.$namespace.'" found!!!');
		}
	}
	
	public function getNamespace() {
		return $this->namespace;
	}
}
?>