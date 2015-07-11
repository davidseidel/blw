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
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_RenderContext');
BLW_CORE_ClassLoader::import('app.lib.PSP.BLW_PSP_StateManager');

/**
 * represents a renderable component of the user interface
 */
abstract class BLW_PSP_UIComponent extends BLW_PSP_Tag {
	public function doStartTag() {
	}
	
	public function doEndTag() {
	}
	
	public function doAfterBody() {
	}

	protected function onInit() {
		parent::onInit();
	}
	
	/**
	 * returns all attributes which should be rendered
	 *
	 * @return ArrayObject
	 */
	public function getAttributesToRender() {
		$id = BLW_PSP_RenderContext::instance()->getIdWithPrefix($this->getId());
		$attributes = new ArrayObject();
		$attributes->offsetSet('id', $id);
		return $attributes;
	}
	
	/**
	 * returns the type of the component
	 *
	 */
	public abstract function getRenderType();
	
	/**
	 * returns the component family
	 *
	 */
	public abstract function getFamily();
	
	/**
	 * @see BLW_PSP_TagInterface::getOutput
	 *
	 */
	public function getOutput() {
		if($this->getValue('visible') == 'false') {
			return;
		}
		$renderer = BLW_PSP_FacesContext::instance()->getRenderKit()->getRenderer($this->getFamily(), $this->getRenderType());
		$renderer->render($this);
		BLW_PSP_RenderContext::instance()->setRendered($this);
	}
}
?>