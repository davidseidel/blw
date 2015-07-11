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


/**
 * This class is used to parse ini files and access its contents.
 * @package blw.core
 * @author David Seidel <david.seidel@me.com>
 */
class BLW_CORE_IniParser {
	static $parsed_files = array();
	protected $file_name = null;
	
	/**
	 * Constructor
	 *
	 * @param string $file_name File name of the ini file
	 */
	public function __construct($file_name) {
		if(!array_key_exists($file_name, self::$parsed_files)) {
			self::$parsed_files[$file_name] = parse_ini_file($file_name, true);
		} 
		$this->file_name = $file_name;
	}
	
	/**
	 * Returns the value of an key by given group name and key name.
	 *
	 * @param string $ini_group name of the group
	 * @param string $key name of the key
	 * @return string
	 */
	public function getValue($ini_group, $key) {
		$ini_file_content = self::$parsed_files[$this->file_name];
		
		if(array_key_exists($ini_group, $ini_file_content) && array_key_exists($key, $ini_file_content[$ini_group])) {
			return $ini_file_content[$ini_group][$key];
		}
		throw new Exception('file "'.$this->file_name.'" not containing the key or group');
	}
}

?>