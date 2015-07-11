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
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_Tag');

/**
 * wrapper tag for static content
 */
class BLW_PSP_StaticContent extends BLW_PSP_Tag {
	/**
	 * contains the content
	 *
	 * @var string
	 */
	protected $content;
	/**
	 * sets the content
	 *
	 * @param unknown_type $content
	 */
	public function setContent($content) {
		$this->content = $content;
	}
	
	public final function doStartTag() {
	}
	
	public final function doEndTag() {
	}
	
	public final function doAfterBody() {
	}
	
	public function getOutput() {
		$this->pageContext->getResponse()->getWriter()->writeText($this->content);
	}
	
}

?>