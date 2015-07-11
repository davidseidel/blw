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
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_StateManager');
/**
 * super class for all events for faces
 */
class BLW_PSP_FacesEvent implements BLW_CORE_Event {
	/**
	 * source of the event
	 *
	 * @var BLW_PSP_UIComponent
	 */
	protected $component = null;
	/**
	 * id of the state during the event should be performed
	 *
	 * @see BLW_PSP_StateManager
	 * @var int
	 */
	protected $state_id = BLW_PSP_StateManager::DECODE_REQUEST;
	/**
	 * Constructor
	 *
	 * @param BLW_PSP_UIComponent $component source of the event
	 */
	public function __construct(BLW_PSP_UIComponent $component) {
		$this->component = $component;
	}
	
	/**
	 * Wrapper for getComponent
	 *
	 * @see BLW_PSP_FacesEvent::getComponent
	 * @return BLW_PSP_UIComponent
	 */
	public function getSource() {
		return $this->getComponent();
	}
       
  	/**
  	 * returns the source for the event
  	 *
  	 * @return BLW_PSP_UIComponent
  	 */
  	public function getComponent() {
  		return $this->component;
  	}
    
  	/**
  	 * returns the state during the event is performed
  	 *
  	 * @return int id of the state
  	 */
  	public function getStateId() { 
  		return $this->state_id;
  	}
   
   	public function processListener( BLW_PSP_FacesEventListener $listener) {
   		$listener->perform($this);
   	}
    
   	/**
   	 * inserts the event in the queue
   	 *
   	 */
   	public function queue() {
   		$view_root = BLW_PSP_FacesContext::instance()->getViewRoot();
   		$view_root->queueEvent($this);
   	}
   	
   	/**
   	 * sets the state during the event is performed
   	 *
   	 * @param int $state_id
   	 */
   	public function setStateId($state_id) {
   		$this->state_id = $state_id;
    }
} 
?>