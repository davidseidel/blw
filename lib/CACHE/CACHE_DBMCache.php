<?php
/**
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the BSD License.
 *
 * Copyright(c) 2006 by David Seidel. All rights reserved.
 *
 * To contact the author write to {@link mailto:seidel.david@googlemail.com David Seidel}
 * The latest version of BlueWonder can be obtained from: {@link http://www.bluewonder-framework.de/}
 *
 * @author David Seidel <seidel.david@googlemail.com>
 * @package blw.lib.cache
 */
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."CACHE_Cache_Interface.php");

class BLW_CACHE_DBMCache implements BLW_CACHE_Cache_Interface {
	protected $expiration = null;
	protected $dbm_handle = null;
	public function __construct($location, $expiration) {
		$this->expiration = $expiration;
		$this->dbm_handle = dba_open($location, "w");
	}
	
	public function put($key, $data) {
		$putObj = array('object' => $data, 'time' => time());
		return dba_replace($key, serialize($putObj), $this->dbm_handle);
	}
	
	public function get($key) {
		$getObj = unserialize(dba_fetch($key, $this->dbm_handle));
		if(($this->expiration == 0) || ((time() - $getObj["time"]) < $this->expiration)) {
			return $getObj['object'];
		} else {
			$this->delete($key);
			return null;
		}
	}
	
	public function delete($key) {
		return dba_delete($key, $this->dbm_handle);
	}
	
	public function __destruct() {
		dba_optimize ($this->dbm_handle);
		dba_close($this->dbm_handle);
	}
}
?>