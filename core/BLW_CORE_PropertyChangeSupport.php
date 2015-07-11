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

require_once('BLW_CORE_ClassLoader.php');
BLW_CORE_ClassLoader::import('app.core.BLW_CORE_PropertyChangeEvent');
BLW_CORE_ClassLoader::import('app.core.BLW_CORE_PropertyChangeListener');

/**
 * @package blw.core
 * @author David Seidel <david.seidel@me.com>
 */
class BLW_CORE_PropertyChangeSupport {
	/**
	 * source of the property changes
	 *
	 * @var mixed
	 */
	protected $object = null;
	/**
	 * listeners of the property change
	 *
	 * @var array
	 */
	protected $listeners = null;
	/**
	 * Constructor
	 *
	 * @param mixed $object
	 */
	public function __construct($object) {
		$this->object = $object;
		$this->listeners = new ArrayObject();
	}
	
	/**
	 * adds a listener for the fired property change events
	 *
	 * @param BLW_CORE_PropertyChangeListener $listener
	 */
	public function addListener(BLW_CORE_PropertyChangeListener $listener) {
		$this->listeners->append($listener);
	}
	
	/**
	 * broadcast a property change to all listeners
	 *
	 * @param string $name
	 * @param mixed $old_value
	 * @param mixed $new_value
	 */
	public function firePropertyChange($name, $old_value, $new_value) {
		$event = new BLW_CORE_PropertyChangeEvent($this->object, $name, $old_value, $new_value);
		foreach ($this->listeners as $listener) {
			$listener->propertyChange($event);
		}
	}
}
?>