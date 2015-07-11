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

BLW_CORE_ClassLoader::import('app.lib.HTTP.BLW_HTTP_Cookie');
BLW_CORE_ClassLoader::import('app.lib.HTTP.BLW_HTTP_ResponseWriter');
/**
 * represents the reponse of a http request.
 *
 */
class BLW_HTTP_Response {
	/**
	 * cookie to be sent.
	 *
	 * @var ArrayObject
	 */
	protected $cookies = null;
	/**
	 * headers to be sent.
	 *
	 * @var ArrayObject
	 */
	protected $headers = null;
	/**
	 * reponse state
	 *
	 * @var int
	 */
	protected $status = 200;
	/**
	 * content sent via reponse
	 *
	 * @var string
	 */
	protected $content = null;
	/**
	 * flag for sent output
	 *
	 * @var bool
	 */
	protected $output_sent = false;
	/**
	 * writer for the reponse
	 *
	 * @var BLW_HTTP_ResponseWriter
	 */
	protected $writer = null;
    
	/**
	 * sets the content of the reponse
	 *
	 * @param string $content
	 */
	public function setContent($content) {
		$this->content = $content;
	}
	
	/**
	 * returns the content of the reponse
	 *
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}
	
    /**
     * Constructor
     *
     */
    public function __construct()
    {
		$this->cookies = new ArrayObject();
		$this->headers = new ArrayObject();
    }

	
     /**
      * returns the reponse writer
      *
      * @return BLW_HTTP_ReponseWriter
      */
     public function getWriter() {
    	if(is_null($this->writer)) {
    		$this->writer = BLW_HTTP_ResponseWriter::instance();
    		$this->writer->setResponse($this);
    	}
    	return $this->writer;
    }
    
	/**
	 * adds a cookie to the reponse
	 *
	 * @param BLW_HTTP_Cookie $cookie
	 */
	public function addCookie(BLW_HTTP_Cookie $cookie) {
		$this->cookies->append($cookie);
	}
 
	/**
	 * @ignore 
	 *
	 * @param unknown_type $name
	 * @param unknown_type $timestamp
	 */
	public function addDateHeader($name, $timestamp) {
		throw new Exception("Not implemented!!!");
	}
        
	/**
	 * adds a header to the reponse
	 *
	 * @param string $name name of the header
	 * @param string $value value of the header
	 */
	public function addHeader($name, $value) {
		$this->headers->offsetSet($name, $value);
	}
 
	/**
	 * @ignore 
	 *
	 * @param unknown_type $name
	 * @param unknown_type $value
	 */
	public function addIntHeader($name, $value) {
		throw new Exception("Not implemented!!!");
	}
     
	public function containsHeader($name) {
		return $this->headers->offsetExists($name);
	}
         
	/**
	 * encodes a url
	 *
	 * @param string $url
	 * @return bool
	 */
	public function encodeURL($url) {
		return urlencode($url);
	}

	/**
	 * sets the reponse state
	 *
	 * @param int $status_code
	 */
	public function setStatus($status_code) {
		$this->status = $status_code;
	}
	
	/**
	 * sends the reponse
	 *
	 * @return bool true=successfully sent, false=allready sent
	 */
	public function send() {
		if($this->output_sent) {
			return false;
		} else {
			$header_names = array_keys($this->headers->getArrayCopy());
			foreach($header_names as $header_name) {
				header($header_name.':'.$this->headers->offsetGet($header_name));
			}
			
			foreach($this->cookies as $cookie) {
				setcookie ( $cookie->getName(), $cookie->getValue(), $cookie->getMaxAge() + time(),
							$cookie->getPath(), $cookie->getDomain(), $cookie->getSecure());
			}
			echo $this->content;
			$this->output_sent = true;
			return true;
		}
	}
}
?>