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
 * @package blw.lib.PSP
 */
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_StateChangeSupport');

/**
 * manager of the states
 */
class BLW_PSP_StateManager {
	/**
	 * instance of the state manager
	 *
	 * @var BLW_PSP_StateManager
	 */
	protected static $instance;
	/**
	 * support for broadcasting state changes
	 *
	 * @var BLW_PSP_StateChangeSupport
	 */
	protected $state_change_support = null;
	
	/**
	 * initial state
	 *
	 */
	const START = 0;
	/**
	 * build the component tree
	 *
	 */
	const BUILD_TREE = 10;
	/**
	 * load the session
	 *
	 */
	const LOAD_SESSION = 20;
	/**
	 * decode request
	 *
	 */
	const DECODE_REQUEST = 30;
	/**
	 * invoke the application
	 *
	 */
	const INVOKE_APPLICATION = 40;
	/**
	 * render the view for output target
	 *
	 */
	const RENDER_VIEW = 50;
	/**
	 * store the session
	 *
	 */
	const SAVE_SESSION = 60;
	
	/**
	 * marks the actual state
	 *
	 * @var int
	 */
	protected $actual_state = self::START;
	
	/**
	 * Constructor
	 *
	 */
	private function __construct() { 
		$this->state_change_support = new BLW_PSP_StateChangeSupport($this);
	}
	
	/**
	 * returns the state change support
	 *
	 * @return BLW_PSP_StateChangeSupport
	 */
	public final function getStateChangeSupport() {
		return $this->state_change_support;
	}
	
	/**
	 * returns the actual state
	 *
	 * @return int actual state
	 */
	public function getActualState() {
		return $this->actual_state;
	}
	
	/**
	 * changes actual state
	 *
	 * @throws BLW_PSP_IllegalStateChangeException
	 * @param int $new_state new state
	 * @return bool true=state successfully change
	 */
	public function changeState($new_state) {
		if($new_state > $this->getActualState()) {
			$this->state_change_support->fireStateChange($this->actual_state, $new_state);
			$this->actual_state = $new_state;	
			return true;
		}
		throw new BLW_PSP_IllegalStateChangeException();
	}
	
	/**
	 * returns an instance of the state manager
	 *
	 * @return BLW_PSP_StateManager
	 */
	public static function instance() {
		if(!isset(self::$instance)) {
			$a = __CLASS__;
			self::$instance = new $a;
		}
		return self::$instance;
	}
	
	/**
	 * called during the attemp to clone this object. This method allways throws an exception.
	 *
	 * @throws Exception
	 */
	public function __clone() {
		throw new Exception('Cloning is not allowed here!!!');
	}
	
	
}

/**
 * Throw during step backward
 *
 */
class BLW_PSP_IllegalStateChangeException extends Exception {}
?>