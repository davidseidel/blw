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
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_StateChangeListener');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_StateManager');

/**
 * context of while rendering components
 */
class BLW_PSP_RenderContext implements BLW_PSP_StateChangeListener {
	/**
	 * contains the actual trace in the object tree of the user interface
	 *
	 * @var array
	 */
	protected $trace = null;
	/**
	 * contains the actual count of children for a specific component
	 *
	 * @var array
	 */
	protected $counts = null;
	/**
	 * instance of render context
	 *
	 * @var BLW_PSP_RenderContext
	 */
	private static $instance = null;
	/**
	 * contains markers for all components that are rendered
	 *
	 * @var ArrayObject
	 */
	protected $rendered_ids = null;
	
	private function __construct() {
		$state_change_support = BLW_PSP_StateManager::instance()->getStateChangeSupport();
		$state_change_support->addListener($this);
	}
	
	/**
	 * Returns an unique instance of the render-context. Part of singleton-pattern
	 *
	 * @return BLW_PSP_RenderContext
	 */
	public static function instance() {
		if(!isset(self::$instance)) {
			$a = __CLASS__;
			self::$instance = new $a;
		}
		return self::$instance;
	}
	
	/**
	 * called during the attemp to clone this object. This method allways throws an exception.
	 * @throws Exception
	 */
	public function __clone() {
		throw new Exception('Cloning is not allowed here!!!');
	}
	
	/**
	 * callback for state change events.
	 *
	 * @param BLW_PSP_StateChangeEvent $event
	 */
	public function perform(BLW_PSP_StateChangeEvent $event) {
		if($event->getNewState() == BLW_PSP_StateManager::RENDER_VIEW) {
			$this->init();
			$this->rendered_ids = new ArrayObject();
		}
		
		if($event->getOldState() == BLW_PSP_StateManager::RENDER_VIEW) {
			$this->init();
		}
	}
	
	public function init() {
		$this->counts = new ArrayObject();
		$this->trace = array();
	}
	
	public function getCount($container) {
		if(array_key_exists($container->getId(), $this->counts)) {
			return $this->counts[$container->getId()];
		}
		return null;
	} 
	
	/**
	 * registers a new children-count of a tag and adds it to the trace
	 *
	 * @param BLW_PSP_Tag $tag
	 * @param int $count
	 */
	public function registerCount(BLW_PSP_Tag $tag, $count) {
		$this->counts->offsetSet($tag->getId(), $count);
		if(count($this->trace) == 0 || ($this->trace[count($this->trace) - 1] != $tag->getId())) {
			array_push($this->trace, $tag->getId());
		}
	}
	
	/**
	 * removes a tag from the trace
	 *
	 * @param BLW_PSP_Tag $tag
	 */
	public function unregister(BLW_PSP_Tag $tag) {
		if(count($this->trace) > 0 && ($this->trace[count($this->trace) - 1] == $tag->getId())) {
			$this->counts->offsetUnset($tag->getId());
			array_pop($this->trace);
		}
	}
	
	public function getRenderedIds() {
		return $this->rendered_ids;
	}
	
	/**
	 * marks a component as rendered
	 *
	 * @param BLW_PSP_UIComponent $component
	 */
	public function setRendered(BLW_PSP_UIComponent $component) {
		$this->rendered_ids->offsetSet($component->getId(), $component->getId());
		$real_id = $this->getIdWithPrefix($component->getId());	
		$this->rendered_ids->offsetSet($real_id, $real_id);
	}
	
	public function getIdWithPrefix($id) {
		$id_string = '';
		if(is_array($this->trace)) { 
			foreach ($this->trace as $container_id) {
				$id_string.= $container_id.'('.$this->counts->offsetGet($container_id).'):';
			}
		}
		return $id_string.$id;
	}
}
?>