<?php
BLW_CORE_ClassLoader::import('app.lib.codegen.BLW_CODEGEN_TagLibInfo');
class BLW_CODEGEN_PageContainer {
	protected $init_stmts = null;
	protected $import_stmts = null;
	protected $service_stmts = null;
	protected $destroy_stmts = null;
	protected $name = null;
	protected $info = null;
	protected $taglibs = null;
	
	public function __construct() {
		$this->taglibs = new ArrayObject();
		$this->init_stmts = new ArrayObject();
		$this->import_stmts = new ArrayObject();
		$this->service_stmts = new ArrayObject();
		$this->destroy_stmts = new ArrayObject();
	}
	
	public function addTagLib(BLW_CODEGEN_TagLibInfo $taglib, $prefix) {
		if(!$this->taglibs->offsetExists($prefix)) {
			$this->taglibs->offsetSet($prefix, $taglib);
			return true;
		}
		throw new Exception('Prefix "'.$prefix.'" allready used!!!');
	}
	
	public function getTagInfo($prefix, $name) {
		if($this->taglibs->offsetExists($prefix)) {
			return $this->taglibs->offsetGet($prefix)->getTagByName($name);
		}
		return null;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function setInfo($info) {
		$this->info = $info;
	}
	
	public function addServiceStatement($src) {
		return $this->service_stmts->append($src);
	}
	
	public function addImportStatement($src) {
		return $this->import_stmts->append($src);	
	}
	
	public function addInitStatement($src) {
		return $this->init_stmts->append($src);	
	}
	
	public function addDestroyStatement($src) {
		return $this->destroy_stmts->append($src);	
	}
	
	public function isNamespaceRegistered($prefix) {
		if($this->taglibs->offsetExists($prefix)) {
			return true;
		}
		return false;
	}
	
	public function processClassTemplate($src) {
		$inits = $this->init_stmts;
		$inits->append('$this->setName(\''.$this->name.'\');'."\n");
		$inits->append('$this->setInfo(\''.$this->info.'\');'."\n");
		
		$src = str_replace('#{classname}', $this->name, $src);
		
		$import_str = '';
		foreach ($this->import_stmts as $import_stmt) {
			$import_str.= $import_stmt;
		}
		$src = str_replace('#{import}', $import_str, $src);
		
		$init_str = '';
		foreach ($inits as $init_stmt) {
			$init_str.= $init_stmt;
		}
		$src = str_replace('#{init}', $init_str, $src);
		
		$service_str = '';
		foreach ($this->service_stmts as $service_stmt) {
			$service_str.= $service_stmt;
		}
		$src = str_replace('#{service}', $service_str, $src);
		
		$destroy_str = '';
		foreach ($this->destroy_stmts as $destroy_stmt) {
			$destroy_str.= $destroy_stmt;
		}
		$src = str_replace('#{destroy}', $destroy_str, $src);
		
		return $src;
	}
	
}

?>