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

/**
* @author David Seidel <seidel.david@googlemail.com>
* @package blw.core
* This class represents the application context for the page. 
*/
class BLW_CORE_ApplicationContext {
	/**
	 * stores the cached base path
	 *
	 * @var string
	 */
	static $base_path = null;
	/**
	 * calculates and returns the base path for the application.
	 *
	 * @return string
	 */
	static public function getBasePath() {
		// test if the base-path was allready computed
		if(self::$base_path == null) {
			// get an array with the actual path
			$path_struct = explode(DIRECTORY_SEPARATOR, dirname(__FILE__));
			
			// the base-path should be one level higher
			// so remove the lowest level
			if(is_array($path_struct) && count($path_struct) > 1) {
				array_pop($path_struct);
				self::$base_path = implode(DIRECTORY_SEPARATOR, $path_struct);
			} else {
				throw new Exception('Error while computing base path!');
			}
		}
		return self::$base_path;
	}
}
?>