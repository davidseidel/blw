<?php
/**
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the BSD License.
 *
 * Copyright(c) 2015 by David Seidel. All rights reserved.
 *
 * To contact the author write to {@link mailto:david.seidel@me.com David Seidel}
 * The latest version of BlueWonder can be obtained from: {@link https://github.com/davidseidel/blw}
 *
 * @author David Seidel <david.seidel@me.com>
 * @package blw.lib.cache
 */
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."Cache_Interface.php");


class BLW_CACHE_APCCache implements BLW_CACHE_Cache_Interface {
	protected $expiration = null;
	public function __construct($location, $expiration) {
		// check if the extension is loaded
		if(extension_loaded("APC") || extension_loaded("apc")) {
			// store expiration-time
			$this->expiration = $expiration;
		} else {
			throw new Exception("PHP-Extension 'APC' is required but not loaded!!!");
		}
	}

	public static function isUsable() {
		if(extension_loaded("APC")) {
			return true;
		}
		return false;
	}

	public function put($key, $data) {
		return apc_store($key, $data);
	}

	public function get($key) {
		return apc_fetch ($key);
	}

	public function delete($key) {
		return apc_delete ($key);
	}
}



?>