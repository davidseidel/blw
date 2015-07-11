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

/**
 * collection of renders for a specific render target 
 */
abstract class BLW_PSP_Renderkit {
	/**
	 * loads the config of the render kit
	 *
	 */
	public abstract function loadConfig();
	
	/**
	 * adds an renderer to the kit
	 *
	 * @param string $family
	 * @param string $rendererType
	 * @param BLW_PSP_Renderer $renderer
	 */
	public abstract function addRenderer($family, $rendererType,  BLW_PSP_Renderer $renderer);
	/**
	 * returns a renderer by given family and type
	 *
	 * @param string $family
	 * @param string $rendererType
	 */
	public abstract function getRenderer($family, $rendererType);
}
?>