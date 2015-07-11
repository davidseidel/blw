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
 * This should be thrown, if a method argument has a illegal type
 * @package blw.core
 * @author David Seidel <david.seidel@me.com>
 */
class BLW_CORE_IllegalArgumentException extends Exception { 
	protected $argument_name = null;
	protected $value = null;
	protected $expected_type = null;
	public function __construct($expected_type = null, $argument_name = null, $value = null) {
		$this->value = $value;
		$this->argument_name = $argument_name;
		$this->expected_type = $expected_type;
		if(!is_null($expected_type) && is_string($expected_type)) {
			if(!is_null($value)) {
				if(is_object($value)) {
					$message = 'Illegal type "'.get_class($value).',';
				} else {
					$message = 'Illegal type "'.get_type($value).',';
				}
			}
			if(!is_null($expected_type) && is_string($expected_type))  {
				$message.= '" expected "'.$expected_type;
			}
			
			if(!is_null($argument_name) && is_string($argument_name))  {
				$message.= '"for argument "'.$argument_name.'"!!!';
			}
			
			parent::__construct($message);
		}
	}
	
	public function getArgumentName() {
		return $this->argument_name;
	}
	
	public function getValue() {
		return $this->value;
	}
}
?>