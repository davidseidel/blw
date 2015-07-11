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
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."Cache_Interface.php");

class BLW_CACHE_eAcceleratorCache implements BLW_CACHE_Cache_Interface {
	protected $expiration = null;
	public function __construct($location, $expiration) {
		// check if the extension is loaded
		if(extension_loaded("eAccelerator")) {
			// store expiration-time
			$this->expiration = $expiration;
			// cleanup the cache
			eaccelerator_gc();
		} else {
			throw new Exception("PHP-Extension 'eAccelerator' is required but not loaded!!!");
		}
	}

	public static function isUsable() {
		if(extension_loaded("eAccelerator")) {
			return true;
		}
		return false;
	}

	public function put($key, $data) {
		// cleanup the cache
		eaccelerator_gc();

		// serialize the date to cache
		$putObj = serialize($data);

		// store data to cache
		return eaccelerator_put($key, $putObj, $this->expiration);
	}

	public function get($key) {
		// cleanup the cache
		eaccelerator_gc();

		// get data from cache
		$getObj = eaccelerator_get($key);

		// check if the key exists
		if($getObj != null) {
			// if the key exists return the value
			return unserialize($getObj);
		} else {
			// if the key not exists return null
			return null;
		}
	}

	public function delete($key) {
		// delete the key from the cache
		// and return the result
		return eaccelerator_rm($key);
	}
}
?>