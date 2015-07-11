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
 * @package blw.core
 */

require_once('BLW_CORE_ApplicationContext.php');


 /**
 * This class is used to load other classes in a simplier way then require_once()
 * @author David Seidel <david.seidel@me.com>
 * @package blw.core
 */
class BLW_CORE_ClassLoader {
	
	/**
	 * Imports the class-file if not loaded before. "app.core.BLW_CORE_Event" imports <BASE-PATH>/core/BLW_CORE_Event.php
	 * @param string $class_name dot separated class name. 
	 * @return bool true=successfully imported
	 */
	static public function import($class_name) {
		// transform string in an array 
		$import_struct = explode('.', $class_name);
		
		// if class was not allready imported try to do this
		if(!class_exists($import_struct[count($import_struct)-1])) {
			$class_file_name = '';
			
			// get the root-directory for import
			$start_point = array_shift($import_struct);
			switch($start_point) {
				// 'app' means the root-directory of the application
				case 'app' : {
					$class_file_name = BLW_CORE_ApplicationContext::getBasePath();
					break;
				}
			}
			
			// build the file-name for the class file		
			$class_file_name.= DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR, $import_struct).'.php';	
			
			// if the file exists import it
			if(file_exists($class_file_name)) {
				if(require_once($class_file_name)) {
					return true;
				} else {
					throw new Exception('An error occurred while loading "'.$class_file_name.'"');
				}
			} else {
				throw new Exception('File('.$class_file_name.') for Class "'.$class_name.'" not found!');
			}
		}
		return false;
	}
}
?>