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
 * @package blw.lib.http
 */

class BLW_HTTP_Session {
	const LAST_ACCESSED = 'BLW_SESS_LA';
	const MAX_INACTIVE_INTERVAL = 'BLW_SESS_MII';
	protected $max_inactive_interval = 3600;
	protected static $instance; 
	
	private function __construct() {
		
	}
	
	public static function instance() {
		if(!isset(self::$instance)) {
			$a = __CLASS__;
			self::$instance = new $a;
		}
		return self::$instance;
	}
	
	public function __clone() {
		throw new Exception('Cloning is not allowed here!!!');
	}
	
	public function getMaxInactiveInterval() {
		return $this->max_inactive_interval;
	}
	
	public function setMaxInactiveInterval($interval) {
		if(is_int($interval)) {
			$this->max_inactive_interval = $interval;
			return true;
		}
		return false;
	} 
	
	public function create($id = null)  {
		if(!is_null($id)) {
			session_id($id);
		}
		$ret =  session_start();
		$_SESSION[self::LAST_ACCESSED] = time();
		$_SESSION[self::MAX_INACTIVE_INTERVAL] = $this->getMaxInactiveInterval();
		return $ret;
	}
	
	public function start() {
		if(BLW_HTTP_Request::getRequestedSessionId() == null) {
			throw new BLW_HTTP_NoValidSessionException();
		}
		
		$ret = session_start();
		
		if($this->getLastAccessedTime() + $this->getMaxInactiveInterval() < time()) {
			session_destroy();
			throw new BLW_HTTP_SessionExpiredException();
		} 
		$_SESSION[self::LAST_ACCESSED] = time();
		$_SESSION[self::MAX_INACTIVE_INTERVAL] = $this->getMaxInactiveInterval();
		return $ret;
	}
	
	public function	getLastAccessedTime() {
		if(array_key_exists(self::LAST_ACCESSED, $_SESSION)) {
			return $_SESSION[self::LAST_ACCESSED];
		} else {
			return null;
		}
	}
	
	public function getAttribute($name) {
		if(array_key_exists($name, $_SESSION)) {
			return $_SESSION[$name];
		}
		return null;
	}
	
	public function setAttribute($name, $value) {
		$_SESSION[$name] = $value;
	}
	

	public function getAttributeNames() {
		return array_keys($_SESSION);
	}
	
	public function getId() {
		return session_id();
	}
}

class BLW_HTTP_SessionExpiredException extends Exception {
	
}

class BLW_HTTP_NoValidSessionException extends Exception {
	
}
?>