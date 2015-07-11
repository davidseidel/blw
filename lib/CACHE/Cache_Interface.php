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
interface BLW_CACHE_Cache_Interface {
	public function __construct($location, $expiration);
	public function put($key, $data);
	public function get($key);
	public function delete($key);
	public static function isUsable();
}
?>