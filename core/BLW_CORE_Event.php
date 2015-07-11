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
 * Base interface for all events.
 * @package blw.core
 * @author David Seidel <david.seidel@me.com>
 */
interface BLW_CORE_Event {
	/**
	 * returns the source of the event
	 * @return mixed source of the triggered event
	 */
	public function getSource();
} 

?>