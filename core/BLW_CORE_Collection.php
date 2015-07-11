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
 * @package blw.core
 */
require_once('BLW_CORE_NullPointerException.php');

/**
 * @package blw.core
 * @author David Seidel <david.seidel@me.com>
 */
class BLW_CORE_Collection implements Iterator, ArrayAccess {
	/**
	 * contains the array which is wrapped
	 *
	 * @var array
	 */
	protected $data = null;
	/**
	 * Constructor
	 *
	 * @param array $data
	 */
	public function __construct($data = null) {
		if(is_array($data)) {
			$this->data = $data;
		} else {
			$this->data = array();
		}
	}
	
	/**
	 * rewinds the wrapped array
	 * @see reset
	 * @return bool
	 */
	public function rewind() {
		return reset($this->data);
	}
	
	/**
	 * returns the current key
	 *
	 * @return mixed current key
	 */
	public function key() {
		return key($this->data);
	}
	
	/**
	 * return the current value
	 *
	 * @return mixed
	 */
	public function current() {
		return current($this->data);
	}
	
	/**
	 * walk to the next item
	 *
	 */
	public function next() {
		next($this->data);
	}
	
	/**
	 * test if we are at the end of the wrapped array
	 *
	 * @return boolk
	 */
	public function valid() {
		return (bool) $this->current();
	}
	
	/**
	 * tests of the given key exists in the wrapped array
	 *
	 * @param mixed $offset
	 * @return bool true if the key exists
	 */
	public function offsetExists($offset) {
		return array_key_exists($offset, $this->data);
	} 
	
	/**
	 * returns the value of the given offset, if it exists
	 * @throws BLW_CORE_NullPointerException
	 * @param mixed $offset
	 * @return mixed
	 */
	public function offsetGet($offset) {
		if($this->offsetExists($offset)) {
			return $this->data[$offset];
		}
		throw new BLW_CORE_NullPointerException('Offset "'.$offset.'" not exists in collection');
	}
	
	/**
	 * sets a given offset with a given value
	 *
	 * @param mixed $offset
	 * @param mixed $data
	 */
	public function offsetSet($offset, $data) {
		$this->data[$offset] = $data;
	}
	
	/**
	 * sorts the wrapped array with sort()
	 *
	 * @see sort
	 */
	public function sort() {
		sort($this->data);
	}
	
	/**
	 * deletes a given offset
	 * 
	 * @throws BLW_CORE_NullPointerException
	 * @param mixed $offset
	 * @return mixed
	 */
	public function offsetUnset($offset) {
		if($this->offsetExists($offset)) {
			unset($this->data[$offset]);
			return true;
		} 
		throw new BLW_CORE_NullPointerException('Offset "'.$offset.'" not exists in collection');
	}
	
	/**
	 * return the count of the elements of the wrapped array.
	 *
	 * @return int count of the elements
	 */
	public function count() {
		return count($this->data);
	}
	
	/**
	 * returns the keys of the wrapped array
	 *
	 * @return array keys of the wrapped array
	 */
	public function getKeys() {
		return array_keys($this->data);
	}
	
	/**
	 * returns the values (without keys) of the wrapped array
	 *
	 * @return array values of the wrapped array
	 */
	public function getValues() {
		return array_values($this->data);
	}
	
	/**
	 * appends an element to the array, using array_push
	 * @see array_push
	 * @param mixed $value
	 * @return bool
	 */
	public function append($value) {
		return array_push($this->data, $value);
	}
	
	/**
	 * returns the wrapped array
	 *
	 * @return array
	 */
	public function getArrayCopy() {
		return $this->data;
	}
}
?>