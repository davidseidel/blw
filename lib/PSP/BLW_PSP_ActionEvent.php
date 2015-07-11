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

BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_FacesEvent');
/**
 * This event is triggered by a face which is source of an action f.e. buttons
 */
class BLW_PSP_ActionEvent extends BLW_PSP_FacesEvent {
	/**
	 * contains the state during the action should be fired.
	 *
	 * @var int state
	 * @see BLW_PSP_StateManager
	 */
	protected $state_id = BLW_PSP_StateManager::INVOKE_APPLICATION;

	/**
	 * broadcast the event to a specific listener
	 *
	 * @param BLW_PSP_ActionListener $listener
	 */
	public function processListener(BLW_PSP_ActionListener $listener) {
   		$listener->perform($this);
   	}
}

?>