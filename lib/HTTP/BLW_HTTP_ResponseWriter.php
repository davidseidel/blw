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
 * @package blw.lib.http
 */

/**
 * writer for reponse to a http request with support to write tags
 *
 */
class BLW_HTTP_ResponseWriter {
	/**
	 * flag for document was started
	 *
	 * @var bool
	 */
	protected $doc_start = false;
	/**
	 * flag for reaching the end of the document
	 *
	 * @var bool
	 */
	protected $doc_end = false;
	/**
	 * flag which signs an opened tag
	 *
	 * @var bool
	 */
	protected $element_open = false;
	/**
	 * buffer for the content
	 *
	 * @var string
	 */
	protected $buffer = false;
	/**
	 * count of the attributes of the actual started tag
	 *
	 * @var int
	 */
	protected $actual_attributes_count = 0;
	/**
	 * reponse object
	 *
	 * @var BLW_HTTP_Reponse
	 */
	protected $response = null;
	/**
	 * character encoding of the buffer
	 *
	 * @var string
	 */
	protected $encoding = 'ISO-8859-1';
	/**
	 * content type of the buffer
	 *
	 * @var string
	 */
	protected $content_type = 'text/html';
	/**
	 * instance of reponse writer
	 *
	 * @var BLW_HTTP_ResponseWriter
	 */
	protected static $instance; 
	
	private function __construct() {
	}
	
	public function __clone()
    {
        throw new Exception('Not allowed here!!!');
    }
	
	public static function instance() {
		if(!isset(self::$instance)) {
			$a = __CLASS__;
			self::$instance = new $a();
		}
		return self::$instance;
	}
	
	public function endDocument() {
		$this->closeOpenedElements();
		$this->doc_end = true;
	}
       
	public function endElement($name) {
		$this->closeOpenedElements();
		$this->buffer.= '</'.$name.'>';
		return true;
	}
	
	public function setResponse(BLW_HTTP_Response $response) {
		$this->response = $response;
	}
	
	public function getResponse() {
		return $this->response;
	}
	
	public function flush() {
		if($this->doc_start == true && $this->doc_end == false) {
			$this->endDocument();
		}
		
		if($this->response instanceof BLW_HTTP_Response) {
			$this->response->setContent($this->buffer);
			$this->response->addHeader('Content-Type', $this->getContentType().';charset='.$this->getCharacterEncoding());
			$this->buffer = null;	
		} else {
			throw new Exception('No reponse object found!!!');
		}
	}
   
	public function getCharacterEncoding() {
		return $this->encoding;
	}
	
	public function	getContentType() {
		return $this->content_type;
	}
	
	public function startDocument() {
		$this->doc_start = true;
	}
       
	protected function closeOpenedElements() {
		if($this->element_open) {
			$this->buffer.= '>'."\n";
			$this->element_open = false;
		}
		return true;
	}
	
	public function startElement($name) {
		$this->closeOpenedElements();
		$this->buffer.= "\n".'<'.$name;
		$this->element_open = true;
	}
	
	public function	writeAttribute($name, $value) {
		if($this->element_open) {
			$this->buffer.= ' '.$name.'="'.$value.'"';
			return true;
		}
		throw new Exception('No started element found!!!');
	}
	
	public function writeComment($comment) {
		$this->closeOpenedElements();
		$this->buffer.='<!-- '.$comment.' -->';
	}
         
	public function writeText($text, $encode = false) {
		$this->closeOpenedElements();
		if($encode) {
			$this->buffer.= htmlspecialchars ( $text , ENT_QUOTES, $this->encoding);
		} else {
			$this->buffer.= $text;
		}
	}          

	
	public function writeURIAttribute($name, $value) {
		if($this->element_open) {
			$this->buffer.= ' '.$name.'="'.str_replace('&', '&amp;'.$value.'"');
			return true;
		}
		throw new Exception('No started element found!!!');
	}
}
?>