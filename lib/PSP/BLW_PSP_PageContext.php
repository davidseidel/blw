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
BLW_CORE_ClassLoader::import('app.lib.HTTP.BLW_HTTP_Request');
BLW_CORE_ClassLoader::import('app.lib.HTTP.BLW_HTTP_Response');
BLW_CORE_ClassLoader::import('app.lib.HTTP.BLW_HTTP_Session');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_StateChangeListener');
BLW_CORE_ClassLoader::import('app.core.BLW_CORE_Collection');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_Page');

/**
 * context of a page
 */
class BLW_PSP_PageContext implements BLW_PSP_StateChangeListener {
	/**
	 * page scope for attribute
	 *
	 */
	const PAGE_SCOPE = 1;
	/**
	 * request scope for attributes
	 *
	 */
	const REQUEST_SCOPE = 2;
	/**
	 * session scope for attributes 
	 *
	 */
	const SESSION_SCOPE = 4;
	/**
	 * application scope for attributes
	 *
	 */
	const APPLICATION_SCOPE = 4;
	/**
	 * key in the session for storing the beans
	 *
	 */
	const BEAN_SESSION_KEY = 'BEAN_';
	/**
	 * key in the session for storing attributes of the tags
	 *
	 */
	const TAG_ATTR_SESSION_KEY = 'TAG_ATTR';
	/**
	 * contains the attributes of the page
	 *
	 * @var BLW_CORE_Collection
	 */
	protected $attributes = null;
	/**
	 * contains the beans
	 *
	 * @var BLW_CORE_Collection
	 */
	protected $beans = null;
	/**
	 * contains the attributes of all tags
	 *
	 * @var BLW_CORE_Collection
	 */
	protected $tag_attributes = null;
	/**
	 * counter for unique ids of tags
	 *
	 * @var int
	 */
	protected $counter = 1;
	/**
	 * contains the view root of the actual page
	 *
	 * @var BLW_PSP_ViewRoot
	 */
	protected $view_root = null;
	/**
	 * contains the actual request
	 *
	 * @var BLW_HTTP_Request
	 */
	protected $request = null;
	/**
	 * contains the actual response
	 *
	 * @var BLW_HTTP_Response
	 */
	protected $response = null;
	/**
	 * containes the page object
	 *
	 * @var BLW_PSP_Page_Interface
	 */
	protected $page = null;
	
	/**
	 * Constructor
	 *
	 * @param BLW_HTTP_Request $request
	 * @param BLW_HTTP_Response $response
	 * @param BLW_PSP_Page $page
	 */
	public function __construct(BLW_HTTP_Request $request, BLW_HTTP_Response $response, BLW_PSP_Page $page) {
		$this->request = $request;
		$this->response = $response;
		$this->attributes = new BLW_CORE_Collection();
		$this->beans = new BLW_CORE_Collection();
		$this->tag_attributes = new BLW_CORE_Collection();
		$state_change_support = BLW_PSP_StateManager::instance()->getStateChangeSupport();
		$state_change_support->addListener($this);
		$this->page = $page;
	}
	
	/**
	 * returns the actual page
	 *
	 * @return BLW_PSP_Page_Interface
	 */
	public function getPage() {
		return $this->page;
	}
	
	/**
	 * callback for state change events
	 *
	 * @param BLW_PSP_StateChangeEvent $event
	 */
	public function perform(BLW_PSP_StateChangeEvent $event) {
		switch ($event->getOldState()) {
			case BLW_PSP_StateManager::RENDER_VIEW : {
				$this->getResponse()->getWriter()->flush();
				$this->getResponse()->send();
				break;
			}
		}
		
		switch ($event->getNewState()) {
			case BLW_PSP_StateManager::LOAD_SESSION : {
				$this->loadViewState();
				break;
			}
			
			case BLW_PSP_StateManager::SAVE_SESSION : {
				$this->saveViewState();
				break;
			}
		}
	}
	
	/**
	 * returns the view root of the page
	 *
	 * @return BLW_PSP_ViewRoot
	 */
	public function getViewRoot() {
		return $this->view_root;
	}

	/**
	 * sets the view root
	 *
	 * @param BLW_PSP_ViewRoot $root
	 */
	public function	setViewRoot(BLW_PSP_ViewRoot $root) {
		$this->view_root = $root;
	}
	
	/**
	 * returns a new unique id for a tag
	 *
	 * @return string
	 */
	public function getNewTagId() {
		return '_id'.(string) ++$this->counter;	
	}
	
	/**
	 * sets an attribute of a tag 
	 *
	 * @param BLW_PSP_Tag $tag
	 * @param string $name
	 * @param string $value
	 * @return bool
	 */
	public function setTagAttribute(BLW_PSP_Tag $tag, $name, $value) {
		if(!$this->tag_attributes->offsetExists($tag->getId())) {
			$this->tag_attributes->offsetSet($tag->getId(), new BLW_CORE_Collection());
		}
		return $this->tag_attributes->offsetGet($tag->getId())->offsetSet($name, $value);
	}
	
	/**
	 * returns all attributes for a tag
	 *
	 * @param BLW_PSP_Tag $tag
	 * @return ArrayObject|null null if the tag was not found
	 */
	public function getTagAttributes(BLW_PSP_Tag $tag) {
		if($this->tag_attributes->offsetExists($tag->getId())) {
			return $this->tag_attributes->offsetGet($tag->getId());
		}
		return null;
	}
	
	/**
	 * returns the value of a attribute by given tag object and attrbute name
	 *
	 * @param BLW_PSP_Tag $tag
	 * @param string $name
	 * @return string|null string of the name was found, null if the attribute was not found
	 */
	public function getTagAttribute(BLW_PSP_Tag $tag, $name) {
		$value = null;

		if($this->tag_attributes->offsetExists($tag->getId()) && 
				$this->tag_attributes->offsetGet($tag->getId())->offsetExists($name)) {
			$value = $this->tag_attributes->offsetGet($tag->getId())->offsetGet($name);
		}
		return $value;
	}

	/**
	 * stores the actual session
	 *
	 * @return bool
	 */
	public function saveViewState() {
		$session = $this->getSession();
		return $session->setAttribute($this->page->getName().'_'.self::TAG_ATTR_SESSION_KEY, $this->tag_attributes);
	}
	
	/**
	 * loads the session
	 *
	 */
	public function loadViewState() {
		// get the attributes which are stored in the session
		$session = $this->getSession();
		$session_attr = $session->getAttribute($this->page->getName().'_'.self::TAG_ATTR_SESSION_KEY);
		if(!is_null($session_attr)) {
			$this->tag_attributes = $session->getAttribute($this->page->getName().'_'.self::TAG_ATTR_SESSION_KEY);
		}
	}
	
	/**
	 * called during the attemp to clone this object. This method allways throws an exception.
	 * @throws Exception
	 */
	public function __clone() {
		throw new Exception('Cloning is not allowed here!!!');
	}
	
	/**
	 * This method forwards 
	 * the current Request and Response 
	 * to another PHP SERVER PAGE.
	 * @ignore 
	 * @return bool
	 */
	public function forward($relativeUrlPath) {
		throw new Exception('Not implemented!!!');
	}
	
	/**
	* returns the actual request
	*
	* @return BLW_HTTP_Request
	*/
	public function getRequest() {
		return $this->request;
	}
	
	/**
	* returns the actual response
	*
	* @return BLW_HTTP_Response
	*/
	public function getResponse() {
		return $this->response;
	}
	
	/**
	* returns the session 
	*
	* @return BLW_HTTP_Session
	*/
	public function getSession() {
		return BLW_HTTP_Session::instance();
	}
	
	/**
	* sets an attribute in a speficied scope
	*
	* @return bool
	*/
	public function setAttribute($name, $value, $scope = self::PAGE_SCOPE) {
		switch($scope) {
			case self::PAGE_SCOPE : {
				return $this->attributes->offsetSet($name, $value);
			}
		}
		return false;
	}
	
	/**
	 * returns the value for an attribute
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function getAttribute($name) {
		if(!is_null($name) && $this->attributes->offsetExists($name)) {
			return $this->attributes->offsetGet($name);
		}
		return null;
	}
	
	/**
	 * @ignore 
	 * @param unknown_type $name
	 */
	public function findAttribute($name) {
		throw new Exception('Not implemented!!!');
	}
	
	/**
	 * removes an attribute from a specified scope
	 *
	 * @param string $name
	 * @param int $scope
	 * @return mixed
	 */
	public function removeAttribute($name, $scope) {
		switch($scope) {
			case self::PAGE_SCOPE : {
				if($this->attributes->offsetExists($name)) {
					$this->attributes->offsetUnset($name);
					return true;
				}
				return false;
			}
		}
		return false;
	}
	
	/**
	* sets a bean for a specified scope
	*
	* @return bool
	*/
	public function setBean($name, $value, $scope = self::PAGE_SCOPE) {
		switch($scope) {
			case self::PAGE_SCOPE : {
				return $this->beans->offsetSet($name, $value);
			}
			
			case self::SESSION_SCOPE : {
				return $this->getSession()->setAttribute(self::BEAN_SESSION_KEY.$name, $value);
			}
		}
		return false;
	}
	
	/**
	 * returns a bean by given name
	 *
	 * @param string $name
	 * @param int $scope scope to search for the bean (default is page scope)
	 * @return BLW_PSP_Bean
	 */
	public function getBean($name, $scope = self::PAGE_SCOPE) {
		switch ($scope) {
			case self::PAGE_SCOPE : {
				if(!is_null($name) && $this->beans->offsetExists($name)) {
					return $this->beans->offsetGet($name);	
				}
				break;
			}
			
			case self::SESSION_SCOPE : {
				return $this->getSession()->getAttribute(self::BEAN_SESSION_KEY.$name);
			}
		}
		return null;
	}
	
	/**
	 * lookup a bean
	 *
	 * @param string $name
	 * @return BLW_PSP_Bean
	 */
	public function findBean($name) {
		if($this->getBean($name) != null) {
			return $this->getBean($name);
		}
		if($this->getSession()->getAttribute(self::BEAN_SESSION_KEY.$name) != null) {
			return $this->getSession()->getAttribute(self::BEAN_SESSION_KEY.$name);
		}
		
		return null;
	}
	
	/**
	 * removes a bean from a specfied scop
	 *
	 * @param string $name name of the bean
	 * @param int $scope
	 * @return bool true=successfully removed bean, false = bean was not removed or not found
	 */
	public function removeBean($name, $scope = self::PAGE_SCOPE) {
		switch($scope) {
			case self::PAGE_SCOPE : {
				if($this->beans->offsetExists($name)) {
					$this->beans->offsetUnset($name);
					return true;
				}
				return false;
			}
		}
		return false;
	}
}
?>