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

/**
 * This interface marks its implementing class as an listener for an property change event.
 * @see BLW_CORE_PropertyChangeEvent
 * @package blw.core
 * @author David Seidel <david.seidel@me.com>
 */
interface BLW_CORE_PropertyChangeListener {
	/**
	 * The implementation of this method should contain the code executed during of a property change.
	 *
	 * @param BLW_CORE_PropertyChangeEvent $e
	 */
	public function propertyChange(BLW_CORE_PropertyChangeEvent $e);
}
?>