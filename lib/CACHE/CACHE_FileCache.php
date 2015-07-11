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

class BLW_CACHE_FileCache implements BLW_CACHE_Cache_Interface {
	protected $expiration = null;
	protected $location = null;
	protected $cache = null;
	public function __construct($location, $expiration) {
		$this->expiration = $expiration;
		$this->location = $location;
		if(file_exists($location)) {
			$this->cache = unserialize(implode(file($location)));
		} else {
			throw new Exception("File '".$location."' not found");
		}
	}
	
	public function put($key, $data) {
		$putObj = array('object' => $data, 'time' => time());
		$this->cache[$key] = $putObj;
		return $this->store();
	}
	
	protected function store() {
		$file_handle = fopen($this->location, "w+");
		return fwrite ( $file_handle, serialize($this->cache));
	}
	
	public function get($key) {
		$getObj = $this->cache[$key];
		if(($this->expiration == 0) || ((time() - $getObj["time"]) < $this->expiration)) {
			return $getObj['object'];
		} else {
			$this->delete($key);
			return null;
		}
	}
	
	public function delete($key) {
		unset($this->cache[$key]);
		$this->store();
		return true;
	}
}
?>