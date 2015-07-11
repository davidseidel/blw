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
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_FacesListener');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_ActionEvent');

/**
 * This interface marks its implementing class as a listener for action events.
 *
 */
interface BLW_PSP_ActionListener extends BLW_PSP_FacesListener  { 
	/**
	 * This method is called during the action event.
	 *
	 * @param BLW_PSP_ActionEvent $event
	 */
	public function processAction(BLW_PSP_ActionEvent $event);
}
?>