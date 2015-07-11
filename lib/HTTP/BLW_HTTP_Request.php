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

/**
 * represents a http request
 *
 */
class BLW_HTTP_Request {
    /**
     * Constructor
     *
     */
    public function __construct()
    {
    }
    
    /**
     * returns the names of the attribute variables
     *
     * @return array
     */
    public function getAttributeNames() {
    	return array_keys($_REQUEST);
    }
    
    /**
     * returns the value of a request variable
     *
     * @param string $name
     * @return string|null
     */
    public function getAttribute($name) {
    	if(array_key_exists($name, $_REQUEST)) {
    		return $_REQUEST[$name];
    	}
    	return null;
    }
    
    /**
     * returns all names of the request variables set via post-method.
     *
     * @return array
     */
    public function getPostAttributeNames() {
    	return array_keys($_POST);
    }
    
    /**
     * returns all names of the request variables set via get-method.
     *
     * @return array
     */
    public function getGetAttributeNames() {
    	return array_keys($_GET);
    }
    
    /**
     * returns all cookie names
     *
     * @return array
     */
    public function getCookieAttributeNames() {
    	return array_keys($_COOKIE);
    }
	
	/**
	 * returns the authentication type
	 *
	 * @return string
	 */
	public function getAuthType() {
		return $_SERVER['AUTH_TYPE'];
	}
	
	/**
	 * returns the request-method
	 *
	 * @return string
	 */
	public function getMethod() {
		return $_SERVER['REQUEST_METHOD'];
	}
	
	/**
	 * returns the path info
	 *
	 * @return string
	 */
	public function getPathInfo() {
		if(array_key_exists('PATH_INFO',$_SERVER)) {
			return $_SERVER['PATH_INFO'];
		}
		return null;
	}
	
	/**
	 * returns the translated path of the request
	 *
	 * @return string
	 */
	public function getPathTranslated() {
		if(array_key_exists('PATH_TRANSLATED',$_SERVER)) {
			return $_SERVER['PATH_TRANSLATED'];
		}
		return null;
	}
	
	/**
	 * returns the query string
	 *
	 * @return string
	 */
	public function getQueryString() {
		return $_SERVER['QUERY_STRING'];
	}
	
	/**
	 * returns the authenticated user of the session
	 *
	 * @return string
	 */
	public function getAuthUser() {
		if(array_key_exists('PHP_AUTH_USER', $_SERVER)) {
			return $_SERVER['PHP_AUTH_USER'];
		}
		return null;
	}
	
	/**
	 * returns the value of the session id of the request.
	 *
	 * @return string|null
	 */
	public function getRequestedSessionId() {
		$id = ini_get('session.name');
		if(array_key_exists($id, $_REQUEST)) {
			return $_REQUEST[$id];
		}
		return null;
	}
	
	/**
	 * returns the requested uri
	 *
	 * @return string
	 */
	public function getRequestURI() {
		return $_SERVER['REQUEST_URI'];
	}
	
	/**
	 * returns the actual session
	 *
	 * @param bool $create true=create new session
	 * @return BLW_HTTP_Session
	 */
	public function getSession($create = false) {
		$session = BLW_HTTP_Session::instance();
		if($create) {
			$session->create();
		} else {
			$session->start();
		}
		return $session;
	}
	
	/**
	 * returns if the session id comes from a cookie var.
	 *
	 * @return bool true=session id is stored in a cookie
	 */
	public function isRequestedSessionIdFromCookie() {
		$id = ini_get('session.name');
		return array_key_exists($id, $_COOKIE);
	}
	
	/**
	 * returns if the session id comes from the url.
	 *
	 * @return bool true=session id is stored in the url
	 */
	public function isRequestedSessionIdFromURL() {
		$id = ini_get('session.name');
		return array_key_exists($id, $_GET);
	}
}

?>