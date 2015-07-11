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
BLW_CORE_ClassLoader::import('app.core.BLW_CORE_PropertyChangeSupport');

/**
 * base class of all beans. mainly gives a base property change support
 */
abstract class BLW_PSP_Bean {
	/**
	 * property change support for the bean
	 *
	 * @var BLW_CORE_PropertyChangeSupport
	 */
	protected $pcs = null;
	/**
	 * Constructor
	 *
	 */
	public final function __construct() {
		$this->pcs = new BLW_CORE_PropertyChangeSupport($this);
		$this->onInit();
	}
	
	/**
	 * called by the constructor of the bean
	 *
	 */
	public abstract function onInit();
	
	/**
	 * returns the property change support of the bean
	 *
	 * @return BLW_CORE_PropertyChangeSupport property change support of the bean
	 */
	public final function getPropertyChangeSupport() {
		return $this->pcs;
	}
	
	/**
	 * called during serializing of the the bean.
	 *
	 * @return array properties which should be serialized
	 */
	public final function __sleep() {
		$properties_to_serialize = array();
		$refl = new ReflectionClass($this);
		$properties = $refl->getProperties();
		foreach ($properties as $property) {
			if($property->getName() != 'pcs') {
				$properties_to_serialize[] = $property->getName();	
			}
		}
		return $properties_to_serialize;
	}
	
	/**
	 * called during the unserializing of the bean
	 *
	 */
	public final function __wakeup() {
		$this->pcs = new BLW_CORE_PropertyChangeSupport($this);
	}
}
?>