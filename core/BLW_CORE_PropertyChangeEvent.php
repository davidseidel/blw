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
BLW_CORE_ClassLoader::import('app.core.BLW_CORE_Event');

/**
 * This event is performed to its listeners by a class which contains a property change support.
 * @see BLW_CORE_PropertyChangeSupport
 * @package blw.core
 * @author David Seidel <seidel.david@googlemail.com>
 */
class BLW_CORE_PropertyChangeEvent implements BLW_CORE_Event {
	/**
	 * old value of the property
	 *
	 * @var mixed
	 */
	protected $old_value = null;
	/**
	 * new value of the property
	 *
	 * @var mixed
	 */
	protected $new_value = null;
	/**
	 * name of the property
	 *
	 * @var string
	 */
	protected $name = null;
	/**
	 * source of the event
	 *
	 * @var mixed
	 */
	protected $source = null;
	
	/**
	 * Constructor
	 *
	 * @param mixed $source
	 * @param string $name
	 * @param mixed $old_value
	 * @param mixed $new_value
	 */
	public function __construct($source, $name, $old_value, $new_value) {
		$this->source = $source;
		$this->name = $name;
		$this->old_value = $old_value;
		$this->new_value = $new_value;
	}
	
	/**
	 * returns the old value of the property
	 *
	 * @return mixed old value of the property
	 */
	public function getOldValue() {
		return $this->old_value;
	}
	
	/**
	 * return the new value of the property
	 *
	 * @return mixed new value of the property
	 */
	public function getNewValue() {
		return $this->new_value;
	}
	
	/**
	 * returns the name of the property
	 *
	 * @return string name of the property
	 */
	public function getPropertyName() {
		return $this->name;
	}
	
	/**
	 * returns the source of the event
	 *
	 * @return mixed source of the event
	 */
	public function getSource() {
		return $this->source;
	}
}
?>