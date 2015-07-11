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
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."DBMCache.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."eAcceleratorCache.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."FileCache.php");

class BLW_CACHE_CacheFactory {
	static function getCacheByDSN($dsn, $expiration = null) {
		if(is_null($expiration)) {
			$expiration = time() + 3600;
		}
		$dsn_parsed = parse_url($dsn);
		$cache = null;
		switch (strtolower($dsn_parsed['scheme'])) {
			case 'apc' : {
				require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."APCCache.php");
				if(BLW_CACHE_APCCache::isUsable()) {
					$cache = new BLW_CACHE_APCCache(null, $expiration);
				}
				break;
			}
		}
		return $cache;
	}
}

?>