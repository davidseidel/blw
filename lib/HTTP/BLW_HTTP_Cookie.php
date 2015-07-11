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
 * represents a http cookie
 *
 */
class BLW_HTTP_Cookie {
	/**
	 * name of the cookie
	 *
	 * @var string
	 */
	protected $name;
	/**
	 * value of the cookie
	 *
	 * @var string
	 */
	protected $value;
	/**
	 * maximum age of the cookie in seconds
	 *
	 * @var int
	 */
	protected $maxage = 86400;
	/**
	 * path of the cookie
	 *
	 * @var string
	 */
	protected $path;
	/**
	 * domain for the cookie
	 *
	 * @var string
	 */
	protected $domain;

	protected $secure;
	
	/**
	 * sets the name of the cookie
	 *
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * returns the name of the cookie
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * sets the value of the cookie
	 *
	 * @param unknown_type $value
	 */
	public function setValue($value) {
		$this->value = $value;
	}
	
	/**
	 * returns the value of the cookie
	 *
	 * @return string
	 */
	public function getValue() {
		return $this->value;
	}
	
	/**
	 * sets the maximum age of the cookie
	 *
	 * @param int $maxage
	 */
	public function setMaxAge($maxage) {
		$this->maxage = $maxage;
	}
	
	/**
	 * returns the maximum age of the cookie
	 *
	 * @return int
	 */
	public function getMaxAge() {
		return $this->maxage;
	}
	
	/**
	 * sets the path of the cookie
	 *
	 * @param string $path
	 */
	public function setPath($path) {
		$this->path = $path;
	}
	
	/**
	 * returns the path of the cookie
	 *
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}
	
	/**
	 * sets the domain name of the cookie
	 *
	 * @param string $domain
	 */
	public function setDomain($domain) {
		$this->domain = $domain;
	}
	
	/**
	 * returns the domain name of the cookie
	 *
	 * @return string
	 */
	public function getDomain() {
		return $this->domain;
	}
	
	public function setSecure($secure) {
		$this->secure = $secure;
	}
	
	public function getSecure() {
		return $this->secure;
	}
}

?>