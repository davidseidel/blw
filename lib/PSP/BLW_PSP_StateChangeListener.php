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

/**
 * This interface marks its implementing class as a listener of state changes
 */
interface BLW_PSP_StateChangeListener {
	/**
	 * call back for performed state changes
	 *
	 * @param BLW_PSP_StateChangeEvent $event
	 */
	public function perform(BLW_PSP_StateChangeEvent $event);
}
?>