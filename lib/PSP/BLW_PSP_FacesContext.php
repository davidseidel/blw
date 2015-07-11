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
BLW_CORE_ClassLoader::import('app.lib.PSP.renderKits.BLW_PSP_RenderKitFactory');
BLW_CORE_ClassLoader::import('app.core.BLW_CORE_NullPointerException');

/**
 * Context for the faces 
 */
class BLW_PSP_FacesContext {
	/**
	 * view root for all faces in the context
	 *
	 * @var BLW_PSP_ViewRoot
	 */
	protected $view_root = null;
	/**
	 * property for singleton pattern
	 *
	 * @var BLW_PSP_FacesContext
	 */
	private static $instance = null;
	/**
	 * all messages sended by the faces in the context
	 *
	 * @var ArrayObject
	 */
	protected $messages = null;
	/**
	 * the renderkit for the faces
	 *
	 * @var BLW_PSP_RenderKit
	 */
	protected $render_kit = null;
	/**
	 * Id for the render kit ('HTML' by default)
	 *
	 * @var string
	 */
	protected $render_kit_id = 'HTML';
	
	/**
	 * Constructor
	 *
	 */
	private function __construct() {
		$this->messages = new ArrayObject();
	}
	
	/**
	 * returns a instance of the faces context
	 *
	 * @return BLW_PSP_FacesContext
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
	 * adds an message for a specific client
	 *
	 * @param string $clientId client which depends to the message
	 * @param BLW_PSP_FacesMessage $message
	 */
	public function	addMessage($clientId, BLW_PSP_FacesMessage $message) {
		if(!$this->messages->offsetExists($clientId)) {
			$this->messages->offsetSet($clientId, new ArrayObject());
		}
		
		$this->messages->offsetGet($clientId)->append($message);
	}
	
	
	/**
	 * Enter description here...
	 * @ignore
	 */
	public function getApplication() {
		throw new Exception('Not implemented!!!');
	}

	/**
	 * returns an ArrayObject with all messages for the faces of this context
	 *
	 * @return ArrayObject messages for the faces of this context
	 */
	public function getClientIdsWithMessages() {
		return $this->messages;
	}
  
     
	/**
	 * returns all messages for an spefic client id. If no client id is given, it returns all messages.
	 *
	 * @param string $client_id
	 * @return ArrayObject
	 */
	public function getMessages($client_id = null) {
		$ret = new ArrayObject();
		if(is_null($client_id)) {
			foreach ($this->messages as $messages_for_client_id) {
				foreach ($messages_for_client_id as $message) {
					$ret->append($message);
				}
			}
		} else {
			if($this->messages->offsetExists($client_id)) {
				$messages_for_client_id = $this->messages->offsetGet($client_id);
				if($messages_for_client_id instanceof ArrayObject) {
					foreach ($messages_for_client_id as $message) {
						$ret->append($message);
					}
				}
			}
		}
		return $ret;
	}
	
	/**
	 * sets the id of the render kit, which should be used to render the faces
	 * 
	 * @throws BLW_PSP_NullPointerException
	 * @param string $render_kit_id
	 * @return bool
	 */
	public function setRenderKitId($render_kit_id) {
		$factory = BLW_PSP_RenderKItFactory::instance();
		if(in_array($render_kit_id, $factory->getRenderKitIds())) {
			$this->render_kit_id = $render_kit_id;
			return true;
		}
		throw new BLW_CORE_NullPointerException('Render Kit with id "'.$render_kit_id.'" not found or not supportd by the application!!!');
	}

	/**
	 * returns the id of the actual render kit
	 *
	 * @return string
	 */
	public function getRenderKitId() {
		return $this->render_kit_id;
	}
	
	/**
	 * returns the actual render kit
	 *
	 * @return BLW_PSP_RenderKit
	 */
	public function getRenderKit() {
		$factory = BLW_PSP_RenderKitFactory::instance();
		if(in_array($this->getRenderKitId(), $factory->getRenderKitIds())) {
			$renderKit =  $factory->getRenderKit($this->getRenderKitId());
			$renderKit->loadConfig();
			return $renderKit;
		}
		throw new Exception('Render Kit with id "'.$this->render_kit_id.'" not found or not supportd by the application!!!');
	}

	/**
	 * returns the view root
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
}
?>