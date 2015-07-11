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

/**
 * This interface marks its implementing class as a source of action events.
 */
interface BLW_PSP_ActionSource {
	/**
	 * adds an listener for the action events
	 *
	 * @param BLW_PSP_ActionListener $listener
	 */
	public function addActionListener(BLW_PSP_ActionListener $listener); 
	
	/**
	 * returns all listeners of the action events
	 * @return ArrayObject
	 */
	public function getActionListeners();
    
	/**
    * returns, if present, an action expression with the class and the static method called by performing this event
    */
    public function getActionExpression();
     
    /**
     * sets the expression which points to the static method which is called during the event
     *
     * @param string $expression
     */
    public function setActionExpression($expression); 	
}
?>