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
 * @package blw.core
 */

require_once('BLW_CORE_ApplicationContext.php');
/**
 * This class is used to load ressources(files) from any location using all supported stream wrappers of php.
 * @package blw.core
 * @author David Seidel <seidel.david@googlemail.com>
 */
class BLW_CORE_RessourceLoader {
	/**
	 * loads a ressource by given uri
	 *
	 * @param string $uri
	 * @return string content of the ressource
	 */
	public static function load($uri) {
		$base_path = BLW_CORE_ApplicationContext::getBasePath();
		$uri_struct = parse_url($uri);
		$tld_xml = '';
		
		$content = null;
		
		if(array_key_exists('scheme', $uri_struct)) {
			$content = file_get_contents($uri);
		} else {
			$content = file_get_contents($base_path.DIRECTORY_SEPARATOR.$uri_struct['path']);
		}
		return $content;
	}
}
?>