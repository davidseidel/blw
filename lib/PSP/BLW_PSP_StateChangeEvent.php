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
BLW_CORE_ClassLoader::import('app.core.BLW_CORE_Event');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_StateChangeListener');

/**
 * This event is throw during a state change
 */
class BLW_PSP_StateChangeEvent {
	/**
	 * actual state manager
	 *
	 * @var BLW_PSP_StateManager
	 */
	protected $state_manager = null;
	/**
	 * old state
	 *
	 * @var int
	 */
	protected $old_state = null;
	/**
	 * new state
	 *
	 * @var int
	 */
	protected $new_state = null;
	
	/**
	 * sets the old state of the state manager
	 *
	 * @param int $state
	 */
	public function setOldState($state) {
		$this->old_state = $state;
	}
	
	/**
	 * returns the old state of the state manager
	 *
	 * @return int
	 */
	public function getOldState() {
		return $this->old_state;
	}
	
	/**
	 * sets the new state of the state manager
	 *
	 * @param unknown_type $state
	 */
	public function setNewState($state) {
		$this->new_state = $state;
	}
	
	/**
	 * returns the new state of the statemanager
	 *
	 * @return int
	 */
	public function getNewState() {
		return $this->new_state;
	}
}
?>