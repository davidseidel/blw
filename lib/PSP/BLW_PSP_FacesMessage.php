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

/**
 * container for a message of a face
 *
 */
class BLW_PSP_FacesMessage {
	/**
	 * localized detail
	 *
	 * @var string
	 */
	protected $detail = null;
	/**
	 * localized summary
	 *
	 * @var string
	 */
	protected $summary = null;

	/**
	 * Return the localized detail text.
	 *
	 * @return string
	 */
	public function getDetail() {
		return $this->detail;
	}
	
	/**
	* Set the localized detail text.
	*/
	public function setDetail($detail) {
		$this->detail = $detail;
	}
          
          
	/**
	* Return the localized summary text.
	*
	* @return string
	*/
	public function getSummary() {
		return $this->summary;
	}
     
	/**
	* Set the localized summary text.
	*
	*/
	public function setSummary($summary) {
		$this->summary = $summary;
	}
}
?>