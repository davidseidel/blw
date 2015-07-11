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

BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_Tag');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_FacesContext');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_PageContext');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_RenderContext');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_StateChangeListener');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_ActionEvent');

/**
 * root of all components and tags
 */
class BLW_PSP_ViewRoot extends BLW_PSP_Tag implements BLW_PSP_StateChangeListener {
	/**
	 * contains all tags
	 *
	 * @var ArrayObject
	 */
	protected $tags = null;
	/**
	 * faces context
	 *
	 * @var BLW_PSP_FacesContext
	 */
	protected $faces_context = null;
	/**
	 * queue for events decode from the request
	 *
	 * @var ArrayObject
	 */
	protected $events = null;
	/**
	 * regular expression for encode ids
	 *
	 */
	const REQUEST_VAR = '=^([^(]+)(\(([^)]*)\)|$)=';

	protected function onInit() {	
		BLW_PSP_RenderContext::instance();	
		BLW_PSP_FacesContext::instance()->setViewRoot($this);	
		
		$this->tags = new ArrayObject();
		
		$this->events = new ArrayObject();
		$state_change_support = BLW_PSP_StateManager::instance()->getStateChangeSupport();
		$state_change_support->addListener($this);
	}
	
	/**
	 * @see BLW_PSP_TagInterface::setPageContext
	 *
	 * @param BLW_PSP_PageContext $pageContext
	 */
	public function setPageContext(BLW_PSP_PageContext $pageContext) {
		parent::setPageContext($pageContext);
		$this->pageContext->setViewRoot($this);	
	}
	
	/**
	 * queues an event
	 *
	 * @param BLW_PSP_FacesEvent $event
	 */
	public function queueEvent(BLW_PSP_FacesEvent $event) {
		if(!$this->events->offsetExists($event->getStateId())) {
			$this->events->offsetSet($event->getStateId(),new ArrayObject());
		}
		$this->events->offsetGet($event->getStateId())->append($event);
	}
	
	/**
	 * callback for state change events: calls decodeRequest(), triggers all events queue for the state which is leaved.
	 *
	 * @param BLW_PSP_StateChangeEvent $event
	 * @return bool true=at least one action was performed
	 */
	public function perform(BLW_PSP_StateChangeEvent $event) {
		if($this->events->offsetExists($event->getOldState())) {		
			foreach ($this->events->offsetGet($event->getOldState()) as $event_to_broadcast) {
				$component = $event_to_broadcast->getComponent();
				$component->broadcast($event_to_broadcast);
				
				if(($component instanceof BLW_PSP_ActionSource) 
							&& !is_null($component->getActionExpression()) && (strlen($component->getActionExpression()) > 0)) {
					$action_express = $component->getActionExpression();
					$expression_struct = explode('::',$action_express);
					if(count($expression_struct) == 2) {
						$class_name = $expression_struct[0];
						$method_name = $expression_struct[1];
						call_user_func_array(array($class_name, $method_name), array($event_to_broadcast));
					}
				}
			}
			$this->events->offsetUnset($event->getOldState());
			return true;
		}
		
		switch ($event->getNewState()) {
			case BLW_PSP_StateManager::DECODE_REQUEST : {
				$this->decodeRequest();
				break;
			}
		}
		return false;
	}
	
	public final function doStartTag() {	
	}
	
	public final function doEndTag() {
	}
	
	public final function doAfterBody() {
	}
	
	/**
	 * registers a tag
	 *
	 * @param string $id
	 * @param BLW_PSP_Tag_Interface $tag
	 */
	public function registerTag($id, BLW_PSP_Tag_Interface $tag) {
		if(!($this->tags instanceof ArrayObject)) {
			$this->tags = new ArrayObject();
		} 
		$this->tags->offsetSet($id, $tag);
	} 
	
	/**
	 * returns a tag object by given id
	 *
	 * @param string $id
	 * @return BLW_PSP_TagObject|null
	 */
	public function getTagById($id) {
		if(($this->tags instanceof ArrayObject) && $this->tags->offsetExists($id)) {
			return $this->tags->offsetGet($id);
		}
	}
	
	/**
	 * decodes the actual request and queues all events
	 *
	 */
	public function decodeRequest() {
		// get request
		$request = $this->pageContext->getRequest();
		
		// get all request-vars(get vars, post vars, cookies)
		$request_vars = $request->getAttributeNames();
		
		$events = array();
		$i = 0;
	
		
		foreach ($request_vars as $request_var) {
			// decode request-var
			$request_var_struct = explode(':', $request_var);
			
			$component = $this->getTagById($request_var_struct[count($request_var_struct)-1]);
			
			foreach ($request_var_struct as $request_id) {
				preg_match(self::REQUEST_VAR, $request_id, $matches);

				$id = trim($matches[1]);
				$component = $this->getTagById($id);
				
				if($component instanceof BLW_PSP_ActionSource) {
					$events[++$i] = new BLW_PSP_ActionEvent($component);
					$events[$i]->queue();
				} elseif($component instanceof BLW_PSP_EditableValueHolder) {
					if(count($matches) == 4) {
						$component->setSubmittedValue($matches[3]);
					} else {
						$component->setSubmittedValue($request->getAttribute($request_var));
					}
				}
			}
		}
	}
	
	public function getOutput() {
		$output = '';
		foreach ($this->children as $child) {
			$output.= $child->getOutput();
		}
		return $output;
	}
	
	public function getPageContext() {
		return $this->pageContext;
	}	
}

?>