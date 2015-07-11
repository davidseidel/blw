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
 * @package blw.lib.PSP
 */
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_StateChangeEvent');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_StateChangeListener');

/**
 * support for broadcasting state change
 */
class BLW_PSP_StateChangeSupport {
	/**
	 * source of the state change events
	 *
	 * @var unknown_type
	 */
	protected $source = null;
	/**
	 * listeners of state changes
	 *
	 * @var ArrayObject
	 */
	protected $listeners = null;
	/**
	 * Constructor
	 *
	 * @param mixed $source source of all state changes
	 */
	public function __construct($source) {
		$this->source = $source;
		$this->listeners = new ArrayObject();
	}
	
	/**
	 * adds a listener for state changes
	 *
	 * @param BLW_PSP_StateChangeListener $listener
	 */
	public function addListener(BLW_PSP_StateChangeListener $listener) {
		$this->listeners->append($listener);
	}
	
	/**
	 * broadcast a state change event to all listeners
	 *
	 * @param int $old_sate
	 * @param int $new_state
	 */
	public function fireStateChange($old_sate, $new_state) {
		$event = new BLW_PSP_StateChangeEvent($this->source);
		$event->setOldState($old_sate);
		$event->setNewState($new_state);
		foreach ($this->listeners as $listener) {
			$listener->perform($event);
		}
	}
}
?>