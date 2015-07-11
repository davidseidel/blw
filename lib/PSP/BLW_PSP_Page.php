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
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_Page_Interface');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_PageContext');
BLW_CORE_ClassLoader::import('app.lib.HTTP.BLW_HTTP_Session');

/**
 * Base class for all pages.
 *
 */
abstract class BLW_PSP_Page implements BLW_PSP_Page_Interface {
	/**
	 * contains the name of the page
	 *
	 * @var string
	 */
	protected $name = null;
	/**
	 * contains an info about the page
	 *
	 * @var string
	 */
	protected $info = null;
	
	/**
	 * sets the name of the page
	 *
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * returns the name of the page
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * sets the page info
	 *
	 * @param string $info
	 */
	public function setInfo($info) {
		$this->info = $info;
	}
	
	/**
	 * returns the page info
	 *
	 * @return string
	 */
	public function getInfo() {
		return $this->info;
	}
	
	/**
	 * this method should be called during the initialisation of the page
	 * @see BLW_PSP_Page_Interface
	 */
	public function init() {
		$session = BLW_HTTP_Session::instance();
		// get or create session
		try {
			$session->start();
		} catch (BLW_HTTP_NoValidSessionException $e) {
			$session->create();;
		} catch (BLW_HTTP_SessionExpiredException $e) {
			$session->create();
		}
	}
}
?>